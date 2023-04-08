<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function downloadFile()
    {
        // Get the file path from the session
        $sFilePath = session('file_path');

        // Delete the file from the server
        unlink($sFilePath);

        // Download the file
        return response()->download($sFilePath)->deleteFileAfterSend(true);
    }
}
