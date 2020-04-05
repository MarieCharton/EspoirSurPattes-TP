<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\File;

class ImageUploader
{

    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function moveAndRename(File $file)
    {

        $filename = null;

        if (!empty($file)) {

            $filename = uniqid() . '.' . $file->guessExtension();
            $file->move(
                $this->uploadDir,
                $filename
            );
        }

        return $filename;
    }
}
