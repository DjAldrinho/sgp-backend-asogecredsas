<?php

namespace App\Helpers;

use Exception;

class FileManager
{

    /**
     * Upload Public Files on /storage/$path
     * @param $file
     * @param $path
     * @param null $key
     * @return bool|string
     */
    public static function uploadPublicFiles($file, $path, $key = null)
    {
        try {
            if (isset($file)) {
                $extension = $file->extension();
                $fileName = (time() + $key) . '.' . $extension;
                $file->move(public_path('storage/' . $path), $fileName);
                return $path . '/' . $fileName;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete Public Files
     * @param $path
     * @return bool
     */
    public static function deletePublicFile($path)
    {
        $allIsDeleted = true;
        try {
            unlink(public_path($path));
        } catch (Exception $e) {
            $allIsDeleted = false;
        }

        return $allIsDeleted;
    }

}

