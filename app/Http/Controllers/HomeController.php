<?php

namespace App\Http\Controllers;

use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * HomeController
 */
class HomeController extends Controller
{
    /**
     * oImportService
     *
     * @var ImportService
     */
    protected $oImportService;

    /**
     * Create a new controller instance.
     *
     * @param  mixed $oImportService
     * @return void
     */
    public function __construct(ImportService $oImportService)
    {
        $this->oImportService = $oImportService;
        // $this->oJobService = $oJobService;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $oRequest)
    {
        $aPageDetails = [
            'page' => 1,
            'limit' => 10,
            'user_id' => Auth::user()->id
        ];
        if ($oRequest->has('page')) {
            $aPageDetails['page'] = $oRequest->input('page');
        }

        $aImports = $this->oImportService->getImports($aPageDetails);
        $aImports['page'] = $aPageDetails['page'];
        $aImports['jobs'] = false;
        return view('home', $aImports);
    }
}
