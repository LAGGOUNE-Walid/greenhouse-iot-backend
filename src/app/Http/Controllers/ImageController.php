<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Actions\UploadStreamImageAction;
use Illuminate\Database\Eloquent\Collection;

class ImageController extends Controller
{
    public function __construct(public UploadStreamImageAction $uploadStreamImageAction) {}



    public function get(): Collection
    {
        return Image::orderByDesc('created_at')->get();
    }

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
