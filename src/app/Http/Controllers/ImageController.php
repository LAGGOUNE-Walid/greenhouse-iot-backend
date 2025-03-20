<?php

namespace App\Http\Controllers;

use App\Actions\UploadStreamImageAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(public UploadStreamImageAction $uploadStreamImageAction) {}

    /**
     * @group Image
     *
     * Send and store raw binary image
     *
     * @response 200 {"message": "Image uploaded successfully!","path": "/storage/uploads/IMG_NAME.jpg"} scenario="Image uploaded"
     * @response 422 {"message": "Invalid image file!","errors": {"file": ["The file field must be an image.","The file field must be a file of type: jpeg, png, jpg, gif."]}} scenario="Validation failed"
     * 
     */
    public function create(Request $request): JsonResponse
    {
        return $this->uploadStreamImageAction->execute(file_get_contents('php://input'));
    }
}
