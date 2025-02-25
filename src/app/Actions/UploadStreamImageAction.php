<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadStreamImageAction
{

    public function execute(string $fileContent): JsonResponse
    {
        $fileName = time() . ".jpg";
        $filePath = "uploads/" . $fileName;

        $tempFile = tmpfile();
        fwrite($tempFile, $fileContent);
        $metaData = stream_get_meta_data($tempFile);
        $tempPath = $metaData['uri'];

        $uploadedFile = new UploadedFile(
            $tempPath,
            $fileName,
            mime_content_type($tempPath),
            null,
            true
        );

        $validator = Validator::make(['file' => $uploadedFile], [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            fclose($tempFile);
            return response()->json([
                'message' => 'Invalid image file!',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (Storage::disk('public')->put($filePath, $fileContent)) {
            fclose($tempFile);
            return response()->json([
                'message' => 'Image uploaded successfully!',
                'path' => asset("storage/".$filePath)
            ]);
        }

        fclose($tempFile);
        return response()->json([
            'message' => 'Failed to upload!',
        ], 500);
    }

}
