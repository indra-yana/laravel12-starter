<?php

namespace App\Utils;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{

    public static function getExtFromMimeType(string $mimeType)
    {
        preg_match('/^image\/(\w+)/', $mimeType, $type);
        $type = $type[1] ?? 'png';
        $extension = strtolower($type);

        return $extension;
    }

    public static function extractBase64File(string $base64File)
    {
        $base64File = explode(',', $base64File);
        $blob = base64_decode(Arr::last($base64File));

        preg_match('/^data:(image\/(\w+));base64/', Arr::first($base64File), $type);
        $mimeType = strtolower($type[1] ?? "");
        $extension = strtolower($type[2] ?? "");

        return [
            'blob' => $blob,
            'extension' => $extension,
            'mimeType' => $mimeType,
        ];
    }

    public static function fromBase64(?string $base64File): ?UploadedFile
    {
        if (!$base64File) {
            return null;
        }

        // Extract the base 64 file
        $extractedBase64File = self::extractBase64File($base64File);

        // Get file data base64 string
        $fileData = $extractedBase64File['blob'];

        // Create temp file and get its absolute path
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $fileData);
        $tempFileObject = new File($tempFilePath);
        $file = new UploadedFile(
            $tempFileObject->getPathname(),
            $tempFileObject->getFilename(),
            $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        // Close this file after response is sent.
        // Closing the file will cause to remove it from temp director!
        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });

        // return UploadedFile object
        return $file;
    }

    public static function createFileName($prefix = "", UploadedFile $file, $isBase64Image = false, $useUniqueName = false)
    {
        if ($isBase64Image) {
            $extension = $file->getExtension();
            if (in_array($extension, ["tmp", ".tmp", ""])) {
                $extension = self::getExtFromMimeType($file->getMimeType());
            }
        } else {
            $extension = $file->getClientOriginalExtension();
        }

        if (!Str::contains(".", $extension)) {
            $extension = ".$extension";
        }

        $filename = $file->getClientOriginalName();
        if ($useUniqueName) {
            $filename = Str::random(8);
        } else {
            $pathInfo = pathinfo($filename);
            $name = $pathInfo['filename'];
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', subject: $name);
            $filename = preg_replace('/_+/', '_', $cleanName);
        }

        return strtolower(preg_replace('/[\W_]+/', '', $prefix) . '-' . date('Ymd-His') . '-' . $filename . $extension);
    }

    public static function createPdfFileName($filename, $useRandomStr = true)
    {
        $filename = "{$filename}_" . ($useRandomStr ? Str::random(8) : '');
        return preg_replace("/[\W]+/", "_", $filename) . ".pdf";
    }

    public static function getCleanName($filename)
    {
        return preg_replace("/[\W]+/", "_", $filename);
    }

    public static function upload(array $data, $isBase64Image = false, $useUniqueName = false)
    {
        $file = @$data['file'];
        $prefix = @$data['prefix'];
        $path = @$data['path'];
        $oldFile = @$data['old_file'];

        if (!empty($file)) {
            $filename = self::createFileName($prefix, $file, $isBase64Image, $useUniqueName);
            Storage::putFileAs($path, $file, $filename);

            if (isset($oldFile)) {
                Storage::delete($oldFile);
            }

            $storagePath = Storage::url("$path/$filename");
            return [
                'name' => $filename,
                'path' => $storagePath,
                'url' => url($storagePath),
                'base_url' => url('/'),
                'driver' => Storage::getDefaultDriver(),
            ];
        }

        return null;
    }

    public static function delete($oldFile)
    {
        return Storage::delete($oldFile);
    }

    public static function preview($path, $defaultImage = null)
    {
        if (Storage::exists($path)) {
            return Storage::response($path);
        }

        return response()->file($defaultImage ?? public_path("images/user.png"));
    }

    public static function contractPath(string $key)
    {
        return "public/uploads/evidence/$key";
    }

}
