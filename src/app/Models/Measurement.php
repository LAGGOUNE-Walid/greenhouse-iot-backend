<?php

namespace App\Models;

use App\Models\Node;
use App\Enums\MeasurementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'measurement_type',
        'value',
    ];

    public function nodex(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }

    protected function casts(): array
    {
        return [
            'measurement_type' => MeasurementType::class,
        ];
    }
}
