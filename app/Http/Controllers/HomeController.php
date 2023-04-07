<?php

namespace App\Http\Controllers;

use App\Services\ImportService;
use Illuminate\Http\Request;

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
            'limit' => 10
        ];
        if ($oRequest->has('page') && $oRequest->has('limit')) {
            $aPageDetails['page'] = $oRequest->input('page');
            $aPageDetails['limit'] = $oRequest->input('limit');
        }

        $aImports = $this->oImportService->getImports($aPageDetails);
        return view('home', [
            'imports' => $aImports
        ]);
    }
}
