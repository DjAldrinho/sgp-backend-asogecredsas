<?php

namespace App\Helpers;

use Exception;

class FileManager
{

    /**
     * Upload Public Files on /storage/$path
     * @param $file
     * @param $path
     * @return bool|string
     */
    public static function uploadPublicFiles($file, $path)
    {
        $i = 1;

        try {
            if (isset($file)) {
                $extension = $file->extension();
                $fileName = (time() + $i) . '.' . $extension;
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

