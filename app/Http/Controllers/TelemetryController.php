<?php

namespace App\Http\Controllers;

use App\Events\NewSensorReadingEvent;
use App\Models\Chamber;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelemetryController extends Controller
{
    /**
     * Lightweight ESP32 telemetry ingestion endpoint.
     * POST /api/telemetry/push
     */
    public function push(Request $request)
    {
        // Lightweight validation for microcontrollers
        $chamberId = $request->input('chamber_id', 1);
        $chamber = Chamber::find($chamberId);

        if (!$chamber) {
            $chamber = Chamber::create([
                'name' => 'Incubator Alpha',
                'location' => 'Gloria (Main Farm)',
                'setpoint' => 37.50,
                'critical_temp' => 38.50,
                'mode' => 'AUTO',
                'fan_pwm' => 50,
                'heater_state' => false,
                'batch_name' => 'BATCH-2026-D01',
                'batch_day' => 14,
                'duck_breed' => 'Pateros Mallard (Itik Pinas)',
                'egg_count' => 120,
            ]);
        }

        $ambientTemp = (float) $request->input('ambient_temp', 37.5);
        $humidity = (float) $request->input('humidity', 65.0);

        // AMG8833 provides an 8x8 matrix (64 floats)
        $thermalGrid = $request->input('thermal_grid');
        if (!is_array($thermalGrid) || count($thermalGrid) !== 64) {
            // Generate synthetic 8x8 around ambient temp if omitted or partial
            $thermalGrid = $this->generateGridAround($ambientTemp, 0.4);
        }

        // Determine thermal runaway
        $maxCellTemp = max($thermalGrid);
        $criticalThreshold = (float) $chamber->critical_temp;
        $isThermalRunaway = ($maxCellTemp >= $criticalThreshold) || ($ambientTemp >= $criticalThreshold);

        // Hardware actuator states reported or computed
        $heaterActive = $request->has('heater_active') 
            ? (bool) $request->input('heater_active') 
            : (bool) $chamber->heater_state;
        
        $fanPwm = $request->has('fan_pwm') 
            ? (int) $request->input('fan_pwm') 
            : (int) $chamber->fan_pwm;

        // Autonomous safety interlock: if thermal runaway occurs, kill heater and max fan
        if ($isThermalRunaway && $chamber->mode === 'AUTO') {
            $heaterActive = false;
            $fanPwm = 100;
            $chamber->update([
                'heater_state' => false,
                'fan_pwm' => 100,
            ]);
        } elseif ($chamber->mode === 'AUTO') {
            // Simple closed-loop PID-like heuristic for incubation
            if ($ambientTemp < $chamber->setpoint - 0.2) {
                $heaterActive = true;
                $fanPwm = 35; // gentle circulation
            } elseif ($ambientTemp > $chamber->setpoint + 0.2) {
                $heaterActive = false;
                $fanPwm = 75; // cooling exhaust
            }
            $chamber->update([
                'heater_state' => $heaterActive,
                'fan_pwm' => $fanPwm,
            ]);
        }

        // Store reading
        $reading = SensorReading::create([
            'chamber_id' => $chamber->id,
            'thermal_grid_json' => $thermalGrid,
            'ambient_temp' => $ambientTemp,
            'humidity' => $humidity,
            'heater_active' => $heaterActive,
            'fan_pwm' => $fanPwm,
            'is_thermal_runaway' => $isThermalRunaway,
        ]);

        // Broadcast event for WebSockets / Reverb / Pusher
        try {
            event(new NewSensorReadingEvent($reading));
        } catch (\Throwable $e) {
            // Log broadcast exception gracefully if socket daemon is offline
            Log::warning('Broadcast failed: ' . $e->getMessage());
        }

        // Return lightweight response with PID actuation parameters for ESP32
        return response()->json([
            'status' => 'ok',
            'reading_id' => $reading->id,
            'alert' => $isThermalRunaway,
            'critical_temp' => $criticalThreshold,
            'actuators' => [
                'heater_state' => (bool) $chamber->heater_state,
                'fan_pwm' => (int) $chamber->fan_pwm,
                'setpoint' => (float) $chamber->setpoint,
                'mode' => $chamber->mode,
            ],
            'timestamp' => now()->timestamp,
        ], 200);
    }

    /**
     * Get the latest telemetry and chamber status.
     * GET /api/telemetry/latest/{chamberId?}
     */
    public function latest($chamberId = 1)
    {
        $chamber = Chamber::find($chamberId);
        if (!$chamber) {
            $chamber = Chamber::first() ?? Chamber::create([
                'name' => 'Incubator Alpha',
                'location' => 'Gloria (Main Farm)',
                'setpoint' => 37.50,
                'critical_temp' => 38.50,
                'mode' => 'AUTO',
                'fan_pwm' => 50,
                'heater_state' => true,
                'batch_name' => 'BATCH-2026-D01',
                'batch_day' => 14,
                'duck_breed' => 'Pateros Mallard (Itik Pinas)',
                'egg_count' => 120,
            ]);
        }

        $latestReading = SensorReading::where('chamber_id', $chamber->id)
            ->latest('id')
            ->first();

        // If no reading yet, create an initial baseline reading
        if (!$latestReading) {
            $grid = $this->generateGridAround((float) $chamber->setpoint, 0.35);
            $latestReading = SensorReading::create([
                'chamber_id' => $chamber->id,
                'thermal_grid_json' => $grid,
                'ambient_temp' => $chamber->setpoint,
                'humidity' => 64.8,
                'heater_active' => true,
                'fan_pwm' => 45,
                'is_thermal_runaway' => false,
            ]);
        }

        $grid = $latestReading->thermal_grid_json;
        $maxTemp = !empty($grid) ? max($grid) : $latestReading->ambient_temp;
        $minTemp = !empty($grid) ? min($grid) : $latestReading->ambient_temp;
        $avgTemp = !empty($grid) ? (array_sum($grid) / count($grid)) : $latestReading->ambient_temp;

        return response()->json([
            'success' => true,
            'chamber' => $chamber,
            'reading' => [
                'id' => $latestReading->id,
                'ambient_temp' => (float) $latestReading->ambient_temp,
                'humidity' => (float) $latestReading->humidity,
                'thermal_grid' => $grid,
                'heater_active' => (bool) $latestReading->heater_active,
                'fan_pwm' => (int) $latestReading->fan_pwm,
                'is_thermal_runaway' => (bool) $latestReading->is_thermal_runaway,
                'created_at' => $latestReading->created_at->toIso8601String(),
                'time_ago' => $latestReading->created_at->diffForHumans(),
            ],
            'stats' => [
                'max_temp' => round($maxTemp, 2),
                'min_temp' => round($minTemp, 2),
                'avg_temp' => round($avgTemp, 2),
                'temp_delta' => round($latestReading->ambient_temp - $chamber->setpoint, 2),
                'is_critical' => $latestReading->is_thermal_runaway,
            ],
        ]);
    }

    /**
     * Get recent telemetry history for charts.
     * GET /api/telemetry/history/{chamberId?}
     */
    public function history($chamberId = 1)
    {
        $readings = SensorReading::where('chamber_id', $chamberId)
            ->latest('id')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'success' => true,
            'history' => $readings->map(function ($r) {
                return [
                    'id' => $r->id,
                    'ambient_temp' => (float) $r->ambient_temp,
                    'humidity' => (float) $r->humidity,
                    'fan_pwm' => (int) $r->fan_pwm,
                    'heater_active' => (bool) $r->heater_active,
                    'is_thermal_runaway' => (bool) $r->is_thermal_runaway,
                    'time' => $r->created_at->format('H:i:s'),
                ];
            }),
        ]);
    }

    /**
     * Update chamber controls (setpoint, critical_temp, PID parameters, batch info).
     * POST /api/telemetry/controls/{chamberId?}
     */
    public function updateControls(Request $request, $chamberId = 1)
    {
        $chamber = Chamber::findOrFail($chamberId);

        $validated = $request->validate([
            'setpoint' => 'nullable|numeric|between:30,45',
            'critical_temp' => 'nullable|numeric|between:35,50',
            'mode' => 'nullable|in:AUTO,MANUAL',
            'heater_state' => 'nullable|boolean',
            'fan_pwm' => 'nullable|integer|between:0,100',
            'batch_name' => 'nullable|string|max:50',
            'batch_day' => 'nullable|integer|between:1,35',
            'duck_breed' => 'nullable|string|max:100',
            'egg_count' => 'nullable|integer|min:0',
        ]);

        $chamber->update(array_filter($validated, fn($value) => !is_null($value)));

        return response()->json([
            'success' => true,
            'message' => 'Chamber configuration and PID parameters updated.',
            'chamber' => $chamber->fresh(),
        ]);
    }

    /**
     * Live Simulation Generator for test & demonstration.
     * POST /api/telemetry/simulate
     */
    public function simulate(Request $request)
    {
        $chamberId = $request->input('chamber_id', 1);
        $chamber = Chamber::find($chamberId) ?? Chamber::first();
        $isSpike = $request->boolean('spike', false);

        if ($isSpike) {
            // Thermal runaway simulation: temps exceed critical_temp (e.g. 39.4°C)
            $ambient = 39.2;
            $humidity = 58.0;
            $grid = [];
            for ($i = 0; $i < 64; $i++) {
                // Hotspot in center (rows 3-4, cols 3-4)
                $row = intdiv($i, 8);
                $col = $i % 8;
                $distFromCenter = sqrt(pow($row - 3.5, 2) + pow($col - 3.5, 2));
                $spikeTemp = 39.8 - ($distFromCenter * 0.35) + (mt_rand(-10, 10) / 100);
                $grid[] = round($spikeTemp, 2);
            }
        } else {
            // Normal healthy incubation: closely tracks setpoint (~37.5°C)
            $setpoint = (float) ($chamber ? $chamber->setpoint : 37.5);
            $ambient = round($setpoint + (mt_rand(-20, 20) / 100), 2);
            $humidity = round(64.5 + (mt_rand(-30, 30) / 10), 1);
            $grid = $this->generateGridAround($ambient, 0.4);
        }

        // Push through same pipeline
        $subRequest = Request::create('/api/telemetry/push', 'POST', [
            'chamber_id' => $chamber ? $chamber->id : 1,
            'ambient_temp' => $ambient,
            'humidity' => $humidity,
            'thermal_grid' => $grid,
            'heater_active' => $chamber ? $chamber->heater_state : true,
            'fan_pwm' => $chamber ? $chamber->fan_pwm : 50,
        ]);

        return $this->push($subRequest);
    }

    /**
     * Helper to generate a realistic 8x8 thermal matrix with slight center-to-edge gradient.
     */
    private function generateGridAround(float $baseTemp, float $variance = 0.3): array
    {
        $grid = [];
        for ($i = 0; $i < 64; $i++) {
            $row = intdiv($i, 8);
            $col = $i % 8;
            // Center is slightly warmer where egg cluster resides
            $distFromCenter = sqrt(pow($row - 3.5, 2) + pow($col - 3.5, 2));
            $temp = $baseTemp + (0.2 - ($distFromCenter * 0.08)) + (mt_rand(-15, 15) / 100);
            $grid[] = round($temp, 2);
        }
        return $grid;
    }
}
