<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'setpoint',
        'critical_temp',
        'heater_state',
        'fan_pwm',
        'mode',
        'batch_name',
        'batch_day',
        'duck_breed',
        'egg_count',
        'api_key',
    ];

    protected $casts = [
        'setpoint' => 'float',
        'critical_temp' => 'float',
        'heater_state' => 'boolean',
        'fan_pwm' => 'integer',
        'batch_day' => 'integer',
        'egg_count' => 'integer',
    ];

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class)->latest();
    }

    public function vitalityLogs()
    {
        return $this->hasMany(VitalityLog::class)->latest();
    }

    public function latestReading()
    {
        return $this->hasOne(SensorReading::class)->latestOfMany();
    }
}
