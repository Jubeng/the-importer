<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditRequest;
use App\Http\Requests\ImportFileRequest;
use App\Http\Requests\ImportRequest;
use App\Services\JobService;
use App\Services\ImportService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

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
     */
    public function __construct(ImportService $oImportService, JobService $oJobService)
    {
        $this->oImportService = $oImportService;
        $this->oJobService = $oJobService;
    }

    /**
     * receives and store the imported xls or xlsx in database
     *
     * @param  object $oRequest
     * @return RedirectResponse
     */
    public function importFile(ImportFileRequest $oRequest): RedirectResponse
    {
        try {
            $oFile = $oRequest->file('import_file');

            $aImportResponse = $this->oImportService->importFile([
                'import_file' => $oFile
            ]);

            if (gettype($aImportResponse) === 'array') {
                session()->put('sCurrentBatchId', $aImportResponse['batch_id']);
            }
            
            return redirect('home');
        } catch (Exception $oException) {
            Log::error('Error occurred while importing: ' . $oException->getMessage());
            return redirect()->back()->withErrors([
                'error' => 'Error occurred while importing. Please try again.'
            ]);
        }
    }
    
    /**
     * fetch the progress in percent form base on the batch table in the database
     *
     * @return JsonResponse
     */
    public function checkJobProgress(): JsonResponse | RedirectResponse
    {
        try {
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
        } catch (Exception $oException) {
            Log::error('Error occurred while checking the job progress: ' . $oException->getMessage());
            return redirect()->back()->withErrors([
                'error' => 'Error occurred while checking the job progress. Please try again.'
            ]);
        }
    }
    
    /**
     * Fetch the import details and display the edit form
     *
     * @param  object $oRequest
     * @return View
     */
    public function viewEditImport(EditRequest $oRequest): View | RedirectResponse
    {
        try {
            $aParam = $oRequest->all();
            $aImportData = $this->oImportService->getImportDataById($aParam);
            return view('edit', [
                'import' => $aImportData,
                'page'   => $aParam['page']
            ]);
        } catch (Exception $oException) {
            Log::error('Error occurred while going to edit page: ' . $oException->getMessage());
            return redirect()->back()->withErrors([
                'error' => 'Error occurred while going to edit page. Please try again.'
            ]);
        }
    }
    
    /**
     * Edit the import data
     *
     * @param  object $oRequest
     * @return RedirectResponse
     */
    public function editImport(ImportRequest $oRequest): RedirectResponse
    {
        try {
            $aParam = $oRequest->all();
            $aEditImportResponse = $this->oImportService->editImport($aParam);
            return redirect()->route('home')->with([
                $aEditImportResponse['result'] => $aEditImportResponse['message'],
                'page'                         => $aParam['page']
            ]);
        } catch (Exception $oException) {
            Log::error('Error occurred while editing: ' . $oException->getMessage());
            return redirect()->back()->withErrors([
                'error' => 'Error occurred while editing. Please try again.'
            ]);
        }
    }
    
    /**
     * deletes the import data by user id under the users data
     *
     * @param  mixed $oRequest
     * @return RedirectResponse
     */
    public function deleteImport(Request $oRequest): RedirectResponse
    {
        try {
            $aParam = $oRequest->all();
            $aDeleteImportResponse = $this->oImportService->deleteImport($aParam);
            return redirect()->route('home')->with([
                $aDeleteImportResponse['result'] => $aDeleteImportResponse['message'],
                'page'                           => $aParam['page']
            ]);
        } catch (Exception $oException) {
            Log::error('Error occurred while deleting: ' . $oException->getMessage());
            return redirect()->back()->withErrors([
                'error' => 'Error occurred while deleting. Please try again.'
            ]);
        }
    }
}
