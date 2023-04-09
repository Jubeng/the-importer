<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportFileRequest;
use App\Services\ImportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * ImportController
 */
class ImportController extends Controller
{    
    /**
     * oImportService
     *
     * @var ImportService
     */
    protected $oImportService;
    
    /**
     * __construct
     *
     * @param  mixed $oImportService
     * @return void
     */
    public function __construct(ImportService $oImportService)
    {
        $this->oImportService = $oImportService;
    }

    /**
     * receives and store the imported xls or xlsx in database
     *
     * @param  mixed $oRequest
     * @return void
     */
    public function importFile(ImportFileRequest $oRequest)
    {
        // Get the uploaded file
        $oFile = $oRequest->file('import_file');
        
        $aImportResponse = $this->oImportService->importFile([
            'import_file' => $oFile,
            'user_id'     => $oRequest->get('user_id')
        ]);
        session()->put('sCurrentBatchId', $aImportResponse['batch_id']);

        return redirect('home');
    }
}
