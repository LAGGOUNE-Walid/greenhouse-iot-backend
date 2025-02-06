<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = [
        'mac_address',
        'ip_address',
        'type'
    ];

}
