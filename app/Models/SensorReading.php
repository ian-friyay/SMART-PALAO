<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'chamber_id',
        'thermal_grid_json',
        'ambient_temp',
        'humidity',
        'heater_active',
        'fan_pwm',
        'is_thermal_runaway',
    ];

    protected $casts = [
        'thermal_grid_json' => 'array',
        'ambient_temp' => 'float',
        'humidity' => 'float',
        'heater_active' => 'boolean',
        'fan_pwm' => 'integer',
        'is_thermal_runaway' => 'boolean',
    ];

    public function chamber()
    {
        return $this->belongsTo(Chamber::class);
    }
}
