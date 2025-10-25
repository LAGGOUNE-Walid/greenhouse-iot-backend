<?php

namespace App\Http\Controllers;

use App\Models\SaraValue;
use Illuminate\Http\Request;

class SaraController extends Controller
{
    public function getSaraValues()
    {
        return SaraValue::orderByDesc("id")->get();
    }
}
