<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatteryLevel extends Model
{
    protected $fillable = ['node_id', 'value'];
}
