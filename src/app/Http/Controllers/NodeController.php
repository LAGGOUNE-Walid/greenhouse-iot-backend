<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;
use App\Http\Resources\NodeResource;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NodeController extends Controller
{
    /**
     * @group Nodes
     *
     * Get nodes streamed response using server sent event.
     * Or get nodes in simple http json response
     *
     * @queryParam static when passing this query parameter with any value the response will be json response otherwise streamed response will return 
     * @response 200 data: {} scenario="Streamed response"
     * 
     */
    #[ResponseFromApiResource(NodeResource::class, Node::class)]
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
