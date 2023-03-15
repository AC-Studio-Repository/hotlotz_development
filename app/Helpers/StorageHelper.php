<?php

namespace App\Helpers;

use Str;
use Storage;

class StorageHelper
{
    public static function getName($file, $showExtension = true)
    {
        $path_parts = pathinfo($file);

        if ($showExtension) {
            return $path_parts['basename'];
        } else {
            return $path_parts['basename'] . $path_parts['extension'];
        }
    }

    public static function getExtension($file, $isUploadedFile = true, $isExternal)
    {
        if ($isUploadedFile) {
            return $file->extension();
        } else {
            if ($isExternal) {
                $path_parts = pathinfo($file);

                return $path_parts['extension'];
            } else {
                preg_match("/^data:image\/(.*);base64/i", $file, $match);
                return $match[1];
            }
        }
    }

    public static function getURL($file)
    {
        $visibility = Storage::getVisibility($file);

        if ($visibility == 'public') {
            return Storage::url($file);
        } else {
            return Storage::temporaryUrl($file, now()->addMinutes(15));
        }
    }

    public static function getBlob($file)
    {
        $content = Storage::get($file);
        $blob = base64_encode($content);
        $extension = self::getExtension($file, false, true);

        return "data:image/$extension;base64,". $blob;
    }

    public static function generateUniqueName()
    {
        return (string) Str::uuid();
    }

    public static function get($path, $filter = [], $loopSubFolder = false, $outputURL = true)
    {
        // loop all images
        if ($loopSubFolder) {
            $files = Storage::allFiles($path);
        } else {
            $files = Storage::files($path);
        }

        // get meta from image
        $images = [];
        foreach ($files as $file) {
            // prepare variables
            $name = self::getName($file);
            $extension = self::getExtension($file, false, true);

            // skip mac system files & do filter
            if ($extension == 'DS_Store' || ($filter && !in_array($name, $filter))) {
                continue;
            }

            // output
            $images[] = [
                'name' => $name,
                'data' => $outputURL ? self::getURL($file) : self::getBlob($file)
            ];
        }

        return $images;
    }

    public static function store($path, $files, $wipeExisting = false, $isExternal = false)
    {
        if ($wipeExisting) {
            Storage::deleteDirectory($path);
        }

        $paths = [];
        foreach ($files as $file) {
            $isUploadedFile = is_uploaded_file($file);

            if ($isUploadedFile) {
                $paths[] = Storage::putFile($path, $file);
            } else {
                $name = self::generateUniqueName();
                $extension = self::getExtension($file, $isUploadedFile, $isExternal);
                $path = $path .'/'. $name .'.'. $extension;

                if ($isExternal) {
                    $file = file_get_contents($file);
                } else {
                    $file = base64_decode(explode(',', $file)[1]);
                }

                Storage::put($path, $file);
                $paths[] = $path;
            }
        }

        return self::get($path);
    }

    public static function add($path, $files, $wipeExisting = false, $isExternal = false)
    {
        if ($wipeExisting) {
            Storage::deleteDirectory($path);
        }

        $paths = [];
        foreach ($files as $file) {
            $isUploadedFile = is_uploaded_file($file);

            if ($isUploadedFile) {
                $paths[] = Storage::putFile($path, $file);
            } else {
                $name = self::generateUniqueName();
                $extension = self::getExtension($file, $isUploadedFile, $isExternal);
                $path = $path .'/'. $name .'.'. $extension;

                if ($isExternal) {
                    $file = file_get_contents($file);
                } else {
                    $file = base64_decode(explode(',', $file)[1]);
                }

                Storage::put($path, $file);
                $paths[] = $path;
            }
        }

        return $paths;
    }

    public static function copyDirectory($oldPath, $newPath)
    {
        // get source files
        $files = Storage::files($oldPath);

        // add to new directory
        foreach ($files as $file) {
            $fileContent = Storage::get($file);
            $name = self::generateUniqueName();
            $extension = self::getExtension($file, false, true);
            $newPath = $newPath .'/'. $name .'.'. $extension;

            Storage::put($newPath, $fileContent);
        }
    }

    public static function delete($path, $names = [])
    {
        if ($names) {
            $files = Storage::files($path);

            foreach ($files as $file) {
                $name = self::getName($file);

                if (in_array($name, $names)) {
                    Storage::delete($file);
                }
            }
        } else {
            Storage::deleteDirectory($path);
        }
    }
}
