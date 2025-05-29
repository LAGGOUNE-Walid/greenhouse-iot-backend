<?php

namespace App\Models;

use App\Enums\NodeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Node extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'type' => NodeType::class,
        ];
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function lastMeasurement(): HasOne
    {
        return $this->hasOne(Measurement::class)->latest();
    }

    public function batteryLevels(): HasMany
    {
        return $this->hasMany(BatteryLevel::class);
    }
}
