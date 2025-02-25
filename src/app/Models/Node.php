<?php

namespace App\Models;

use App\Enums\NodeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function batteryLevels(): HasMany
    {
        return $this->hasMany(BatteryLevel::class);
    }

}
