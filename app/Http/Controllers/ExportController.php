<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $aParam = $oRequest->all();
        $aParam['user_id'] = Auth::user()->id;
        $aFileDetails = $this->oExportService->exportData($aParam);

        return response()->download($aFileDetails['file_name'], $aFileDetails['file_name'], [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}