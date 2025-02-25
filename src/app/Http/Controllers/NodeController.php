<?php

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NodeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return NodeResource::collection(Node::with(['batteryLevels', 'measurements'])->get());
    }
}
