<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * ExportController
 */
class ExportController
{
    /**
     * oExportService
     *
     * @var ExportService
     */
    protected $oExportService;
    
    /**
     * __construct
     *
     * @param  mixed $oImportService
     * @return void
     */
    public function __construct(ExportService $oExportService)
    {
        $this->oExportService = $oExportService;
    }
    
    /**
     * Exports the data of the user if all or specific page
     *
     * @param  mixed $oRequest
     * @return array
     */
    public function exportData(Request $oRequest)
    {
        try {
            $aParam = $oRequest->all();
            $aParam['user_id'] = Auth::user()->id;
            $aFileDetails = $this->oExportService->exportData($aParam);

            return response()->download($aFileDetails['file_name'], $aFileDetails['file_name'], [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (Exception $oException) {
            Log::error('Error occurred while exporting: ' . $oException->getMessage());
            return redirect()->back()->withErrors([
                'error' => 'Error occurred while exporting. Please try again.'
            ]);
        }
    }
}