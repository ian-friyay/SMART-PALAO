<?php

namespace App\Events;

use App\Models\SensorReading;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSensorReadingEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reading;

    /**
     * Create a new event instance.
     */
    public function __construct(SensorReading $reading)
    {
        $this->reading = $reading->load('chamber');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('incubator.' . $this->reading->chamber_id),
            new Channel('incubator.all'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'sensor.reading';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->reading->id,
            'chamber_id' => $this->reading->chamber_id,
            'ambient_temp' => (float) $this->reading->ambient_temp,
            'humidity' => (float) $this->reading->humidity,
            'thermal_grid' => $this->reading->thermal_grid_json,
            'heater_active' => (bool) $this->reading->heater_active,
            'fan_pwm' => (int) $this->reading->fan_pwm,
            'is_thermal_runaway' => (bool) $this->reading->is_thermal_runaway,
            'created_at' => $this->reading->created_at->toIso8601String(),
            'chamber' => [
                'name' => $this->reading->chamber->name ?? 'Incubator Alpha',
                'setpoint' => (float) ($this->reading->chamber->setpoint ?? 37.5),
                'critical_temp' => (float) ($this->reading->chamber->critical_temp ?? 38.5),
                'mode' => $this->reading->chamber->mode ?? 'AUTO',
            ]
        ];
    }
}
