<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
        $aFileDetails = $this->oExportService->exportData($oRequest->all());

        return response()->download($aFileDetails['file_name'], $aFileDetails['file_name'], [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}