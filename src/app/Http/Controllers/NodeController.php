<?php

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NodeController extends Controller
{
    public function index(Request $request): StreamedResponse|AnonymousResourceCollection
    {
        if ($request->has('static')) {
            return NodeResource::collection(Node::with(['batteryLevels', 'measurements'])->get());
        }

        return $this->sseResponse(function () {
            return NodeResource::collection(Node::with(['batteryLevels', 'measurements'])->get());
        });
    }
}
