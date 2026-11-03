<?php

namespace App\Console\Commands;

use App\Events\NewSensorReadingEvent;
use App\Models\Chamber;
use App\Models\SensorReading;
use Illuminate\Console\Command;

class SimulatePalaoTelemetry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'palao:simulate {--chamber=1} {--spike : Inject thermal runaway spike} {--count=1 : Number of packets to generate} {--delay=2 : Delay in seconds between packets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate ESP32 telemetry packet transmission for Smart Palao incubation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chamberId = (int) $this->option('chamber');
        $chamber = Chamber::find($chamberId) ?? Chamber::first();

        if (!$chamber) {
            $this->error("Chamber #{$chamberId} not found. Please run seeders first.");
            return 1;
        }

        $count = (int) $this->option('count');
        $isSpike = (bool) $this->option('spike');
        $delay = (int) $this->option('delay');

        $this->info("Simulating ESP32 telemetry for Chamber: {$chamber->name} (Setpoint: {$chamber->setpoint}°C, Critical: {$chamber->critical_temp}°C)");

        for ($k = 0; $k < $count; $k++) {
            if ($isSpike) {
                $ambient = 39.4;
                $humidity = 58.2;
                $grid = [];
                for ($i = 0; $i < 64; $i++) {
                    $row = intdiv($i, 8);
                    $col = $i % 8;
                    $dist = sqrt(pow($row - 3.5, 2) + pow($col - 3.5, 2));
                    $grid[] = round(39.9 - ($dist * 0.3) + (mt_rand(-10, 10) / 100), 2);
                }
                $isRunaway = true;
                $heater = false;
                $fan = 100;
            } else {
                $ambient = round((float) $chamber->setpoint + (mt_rand(-25, 25) / 100), 2);
                $humidity = round(64.5 + (mt_rand(-20, 20) / 10), 1);
                $grid = [];
                for ($i = 0; $i < 64; $i++) {
                    $row = intdiv($i, 8);
                    $col = $i % 8;
                    $dist = sqrt(pow($row - 3.5, 2) + pow($col - 3.5, 2));
                    $grid[] = round((float) $chamber->setpoint + (0.2 - ($dist * 0.08)) + (mt_rand(-12, 12) / 100), 2);
                }
                $isRunaway = max($grid) >= $chamber->critical_temp;
                $heater = $ambient < $chamber->setpoint;
                $fan = $heater ? 35 : 65;
            }

            $reading = SensorReading::create([
                'chamber_id' => $chamber->id,
                'thermal_grid_json' => $grid,
                'ambient_temp' => $ambient,
                'humidity' => $humidity,
                'heater_active' => $heater,
                'fan_pwm' => $fan,
                'is_thermal_runaway' => $isRunaway,
            ]);

            // Broadcast event
            try {
                event(new NewSensorReadingEvent($reading));
            } catch (\Throwable $e) {
                // Ignore if broadcasting driver not configured
            }

            $maxT = max($grid);
            $minT = min($grid);
            $statusStr = $isRunaway ? '<fg=white;bg=red> CRITICAL THERMAL RUNAWAY </>' : '<fg=black;bg=green> OPTIMAL </>';

            $this->line(sprintf(
                "[%s] Ambient: %.2f°C | Hum: %.1f%% | Grid: [%.2f°C ~ %.2f°C] | Fan: %d%% | Heater: %s | Status: %s",
                now()->format('H:i:s'),
                $ambient,
                $humidity,
                $minT,
                $maxT,
                $fan,
                $heater ? 'ON' : 'OFF',
                $statusStr
            ));

            if ($k < $count - 1) {
                sleep($delay);
            }
        }

        return 0;
    }
}
