<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditRequest;
use App\Http\Requests\ImportFileRequest;
use App\Http\Requests\ImportRequest;
use App\Services\JobService;
use App\Services\ImportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

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
     * oJobService
     *
     * @var JobService
     */
    protected $oJobService;
    
    /**
     * __construct
     *
     * @param  mixed $oImportService
     * @return void
     */
    public function __construct(ImportService $oImportService, JobService $oJobService)
    {
        $this->oImportService = $oImportService;
        $this->oJobService = $oJobService;
    }

    /**
     * receives and store the imported xls or xlsx in database
     *
     * @param  ImportFileRequest $oRequest
     * @return void
     */
    public function importFile(ImportFileRequest $oRequest)
    {
        // Get the uploaded file
        $oFile = $oRequest->file('import_file');
        
        $aImportResponse = $this->oImportService->importFile([
            'import_file' => $oFile
        ]);
        session()->put('sCurrentBatchId', $aImportResponse['batch_id']);

        return redirect('home');
    }
    
    /**
     * fetch the progress in percent form base on the batch table in the database
     *
     * @return JsonResponse
     */
    public function checkJobProgress(): JsonResponse
    {
        $sBatchId = session()->get('sCurrentBatchId');
        $mProgress = false;
        if ($sBatchId !== null) {
            $mProgress = $this->oJobService->getImportProgressById(['batch_id' => $sBatchId]);
            if ($mProgress === 100) {
                session()->forget('sCurrentBatchId');
            }
        }

        return response()->json(
            [
                'progress' => $mProgress
            ], 200
        );
    }
    
    /**
     * Fetch the import details and display the edit form
     *
     * @param  EditRequest $oRequest
     * @return View
     */
    public function viewEditImport(EditRequest $oRequest): View
    {
        $aImportData = $this->oImportService->getImportDataById($oRequest->all());
        return view('edit', ['import' => $aImportData]);
    }

    public function editImport(ImportRequest $oRequest)
    {
        # code...
        return redirect('home')->with('success', 'Edit Success.');
    }
}
