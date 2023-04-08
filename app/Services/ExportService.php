<?php

namespace App\Services;

use App\Services\BaseService;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use PDO;

/**
 * ImportService
 */
class ExportService extends BaseService
{    
    /**
     * importFile
     *
     * @param  array $aFile
     * @return array
     */
    public function exportData(array $aExportDetails): array
    {
        try {
            $aUserImportedData = $this->getDataByUserDetails($aExportDetails);

            $aUserImportedData = array_map('array_values', $aUserImportedData);

            $oSpreadsheet = new Spreadsheet();

            // Get the active sheet and set the data
            $oSheet = $oSpreadsheet->getActiveSheet();
            $oSheet->fromArray($aUserImportedData, null, 'A2'); // Start at row 2

            // Set the headers and format the cells
            $aHeaders = [
                'Last Name',
                'First Name',
                'Middle Name',
                'Address Street',
                'Address Brgy',
                'Address City',
                'Address Province',
                'Contact Phone',
                'Contact Mobile',
                'Email'
            ];
            $oSheet->fromArray([$aHeaders], null, 'A1');
            $oSheet->getStyle('A1:J1')->getFont()->setBold(true);

            $oWriter = new Xlsx($oSpreadsheet);
            $iTimestamp = time();
            $sFileName = 'TheImporter' . date('YmdHis', $iTimestamp) . '.xlsx';
            $oWriter->save($sFileName);

            return [
                'file_name' => $sFileName,
                'status' => 200
            ];
        } catch (\Exception $oException) {
            // handle any exceptions that may occur in importFile
            return [
                'message' => 'Failed to add data.',
                'error' => $oException->getMessage(),
                'status' => 500
            ];
        }
    }
}