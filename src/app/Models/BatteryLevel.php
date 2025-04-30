<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BatteryLevel extends Model
{
    protected $fillable = ['node_id', 'value'];

    protected function value(): Attribute

    {
        return Attribute::make(
            get: fn (int $value) =>  max(0, min(100, $value)),
        );

    }
}
