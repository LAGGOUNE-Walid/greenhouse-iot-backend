<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaraValue extends Model
{
    protected $fillable = ["value_soil", "value_temp", "value_humidity", "node_id"];
}
