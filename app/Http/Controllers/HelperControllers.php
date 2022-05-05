<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class HelperControllers extends Controller
{
    
    public function deleteFile($path)
    {

        if (File::exists($path)) {
            File::delete($path);
            // unlink($path);
            return true;

        } else {
            return false;
        }

    }
}
