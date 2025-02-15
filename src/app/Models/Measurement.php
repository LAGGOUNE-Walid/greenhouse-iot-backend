<?php

namespace App\Models;

use App\Enums\MeasurementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'measurement_type',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'measurement_type' => MeasurementType::class,
        ];
    }
}
