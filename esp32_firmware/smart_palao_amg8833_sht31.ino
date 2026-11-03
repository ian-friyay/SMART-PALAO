/*
 * =========================================================================
 * Smart Palao Incubation Telematics - ESP32 Firmware
 * Hardware:
 *   - ESP32 Development Board
 *   - Panasonic AMG8833 8x8 Thermal Infrared Sensor (I2C: SDA=21, SCL=22)
 *   - Sensirion SHT31 Temperature & Humidity Sensor (I2C: 0x44)
 *   - Solid-State Relay (SSR) Heating Element: GPIO 18
 *   - PWM Exhaust / Circulation Fan: GPIO 19 (LEDC Channel 0)
 *
 * Endpoint: POST /api/telemetry/push
 * =========================================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <ArduinoJson.h>
#include <Adafruit_AMG88xx.h>
#include <Adafruit_SHT31.h>

// WiFi Configuration
const char* WIFI_SSID     = "YOUR_WIFI_SSID";
const char* WIFI_PASSWORD = "YOUR_WIFI_PASSWORD";

// Smart Palao API Host
const char* SERVER_ENDPOINT = "http://192.168.1.100:8000/api/telemetry/push";
const char* API_KEY         = "palao_esp32_secret_key_2026";
const int   CHAMBER_ID      = 1;

// Pin Definitions
#define HEATER_RELAY_PIN  18
#define FAN_PWM_PIN       19
#define PWM_CHANNEL       0
#define PWM_FREQ          25000 // 25 kHz for quiet PC fan PWM
#define PWM_RESOLUTION    8     // 0-255

// Sensors
Adafruit_AMG88xx amg;
Adafruit_SHT31 sht31 = Adafruit_SHT31();

float pixels[64];
unsigned long lastTelemetryTime = 0;
const unsigned long TELEMETRY_INTERVAL = 2500; // 2.5 seconds

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("\n[Smart Palao] Initializing Incubation Telematics Node...");

  // Initialize GPIOs
  pinMode(HEATER_RELAY_PIN, OUTPUT);
  digitalWrite(HEATER_RELAY_PIN, LOW);

  // Setup PWM for fan
  ledcSetup(PWM_CHANNEL, PWM_FREQ, PWM_RESOLUTION);
  ledcAttachPin(FAN_PWM_PIN, PWM_CHANNEL);
  ledcWrite(PWM_CHANNEL, 128); // 50% default

  // I2C bus
  Wire.begin(21, 22);

  // Initialize AMG8833
  if (!amg.begin(0x69)) {
    Serial.println("[ERROR] AMG8833 not detected at 0x69! Check wiring.");
  } else {
    Serial.println("[OK] AMG8833 8x8 IR Thermal array online.");
  }

  // Initialize SHT31
  if (!sht31.begin(0x44)) {
    Serial.println("[ERROR] SHT31 not detected at 0x44! Check wiring.");
  } else {
    Serial.println("[OK] SHT31 Ambient Temp & Humidity sensor online.");
  }

  // Connect to WiFi
  Serial.print("[WiFi] Connecting to: ");
  Serial.println(WIFI_SSID);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.print("\n[OK] Connected! IP Address: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\n[WARN] WiFi connection timeout. Will retry in loop.");
  }
}

void loop() {
  if (millis() - lastTelemetryTime >= TELEMETRY_INTERVAL) {
    lastTelemetryTime = millis();
    transmitTelemetry();
  }
}

void transmitTelemetry() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[WiFi] Reconnecting...");
    WiFi.reconnect();
    return;
  }

  // Read SHT31
  float ambientTemp = sht31.readTemperature();
  float humidity = sht31.readHumidity();

  // Read AMG8833 (8x8 = 64 pixels)
  amg.readPixels(pixels);

  // Build JSON Document
  StaticJsonDocument<2048> doc;
  doc["chamber_id"] = CHAMBER_ID;
  doc["api_key"] = API_KEY;
  doc["ambient_temp"] = isnan(ambientTemp) ? 37.5 : round(ambientTemp * 100.0) / 100.0;
  doc["humidity"] = isnan(humidity) ? 65.0 : round(humidity * 10.0) / 10.0;
  
  JsonArray grid = doc.createNestedArray("thermal_grid");
  for (int i = 0; i < 64; i++) {
    grid.add(round(pixels[i] * 100.0) / 100.0);
  }

  String jsonPayload;
  serializeJson(doc, jsonPayload);

  // Send HTTP POST
  HTTPClient http;
  http.begin(SERVER_ENDPOINT);
  http.addHeader("Content-Type", "application/json");

  int httpCode = http.POST(jsonPayload);

  if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_CREATED) {
    String response = http.getString();
    
    // Parse PID instructions from server response
    StaticJsonDocument<512> respDoc;
    DeserializationError err = deserializeJson(respDoc, response);

    if (!err) {
      bool isAlert = respDoc["alert"] | false;
      bool heaterState = respDoc["actuators"]["heater_state"] | false;
      int fanPwmPercent = respDoc["actuators"]["fan_pwm"] | 50;
      float setpoint = respDoc["actuators"]["setpoint"] | 37.5;

      // Apply Actuator Directives
      digitalWrite(HEATER_RELAY_PIN, heaterState ? HIGH : LOW);
      
      int pwmDuty = map(fanPwmPercent, 0, 100, 0, 255);
      ledcWrite(PWM_CHANNEL, pwmDuty);

      Serial.printf("[TELEMETRY] Amb: %.2f C | Hum: %.1f %% | Heat: %s | Fan: %d %% | Alert: %s\n",
        ambientTemp, humidity, heaterState ? "ON" : "OFF", fanPwmPercent, isAlert ? "YES" : "NO");
    }
  } else {
    Serial.printf("[HTTP ERROR] POST failed, code: %d\n", httpCode);
  }

  http.end();
}
