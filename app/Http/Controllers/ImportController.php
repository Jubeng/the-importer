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
        try {
            // Get the uploaded file
            $oFile = $oRequest->file('import_file');

            // Process the file with spatie/simple-excel
            
            $this->oImportService->importFile(['import_file' => $oFile]);

            // Redirect back with a success message
            return back()->with('success', 'File uploaded and data inserted successfully.');
        } catch (Exception $oError) {
            Log::error($oError);
        }
    }
        
    /**
     * fetch the imports with limit of 10
     *
     * @return \Illuminate\View\View
     */ 
    public function getImport(): View
    {
        $aImports = $this->oImportService->getImports();
        return view('home', [
            'imports' => $aImports
        ]);
    }
}
