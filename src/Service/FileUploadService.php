<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{

    public function generateUniqueFilename(UploadedFile $file, string $path): string
    {
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);

        return $filename;
    }
}