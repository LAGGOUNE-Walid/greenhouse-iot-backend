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

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    protected function casts(): array
    {
        return [
            'type' => NodeType::class,
        ];
    }
}
