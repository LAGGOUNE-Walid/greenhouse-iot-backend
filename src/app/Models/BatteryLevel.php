<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BatteryLevel extends Model
{
    use HasFactory;
    
    protected $fillable = ['node_id', 'value'];

    protected function value(): Attribute

    {
        return Attribute::make(
            get: fn (int $value) =>  max(0, min(100, $value)),
        );

    }
}
