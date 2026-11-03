<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'chamber_id',
        'batch_id',
        'grid_position',
        'result',
        'candling_day',
        'notes',
    ];

    protected $casts = [
        'candling_day' => 'integer',
    ];

    public function chamber()
    {
        return $this->belongsTo(Chamber::class);
    }
}
