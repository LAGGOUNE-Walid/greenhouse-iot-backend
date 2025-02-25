<?php

namespace App\Http\Controllers;

use App\Actions\UploadStreamImageAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{

    public function __construct(public UploadStreamImageAction $uploadStreamImageAction) {}

    public function create(Request $request): JsonResponse
    {
        return $this->uploadStreamImageAction->execute(file_get_contents("php://input"));
    }
}
