<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorClass;

class UploadStreamImageAction
{
    public function execute(string $fileContent): JsonResponse
    {
        $fileName = time() . '.jpg';
        $filePath = 'uploads/' . $fileName;

        $tempFile = $this->createTempFile($fileContent);
        $tempPath = $this->getTempPath($tempFile);

        $uploadedFile = $this->uploadFileTemporary($tempPath, $fileName);

        $validator = $this->validate($uploadedFile);

        if ($validator->fails()) {
            fclose($tempFile);

            return response()->json([
                'message' => 'Invalid image file!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $savedFile = $this->saveToStorage($filePath, $fileContent);
        if (! $savedFile) {
            fclose($tempFile);

            return response()->json([
                'message' => 'Failed to upload!',
            ], 500);
        }
        fclose($tempFile);

        return response()->json([
            'message' => 'Image uploaded successfully!',
            'path' => asset('storage/' . $filePath),
        ]);
    }

    public function createTempFile(string $fileContent): mixed
    {
        $tempFile = tmpfile();
        fwrite($tempFile, $fileContent);

        return $tempFile;
    }

    public function getTempPath(mixed $tempFile): string
    {
        $metaData = stream_get_meta_data($tempFile);

        return $metaData['uri'];
    }

    public function uploadFileTemporary(string $tempPath, string $fileName): UploadedFile
    {
        return new UploadedFile(
            $tempPath,
            $fileName,
            mime_content_type($tempPath),
            null,
            true
        );
    }

    public function validate(UploadedFile $uploadedFile): ValidatorClass
    {
        return Validator::make(['file' => $uploadedFile], [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    public function saveToStorage(string $filePath, string $fileContent): bool
    {
        return Storage::disk('public')->put($filePath, $fileContent);
    }
}
