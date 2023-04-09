<?php

namespace App\Services;

use App\Jobs\ProcessImport;
use App\Services\BaseService;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * ImportService
 */
class ImportService extends BaseService
{    
    /**
     * Fetch the 10 rows of the current page for table of the home page
     *
     * @param  mixed $aPageDetails
     * @return array
     */
    public function getImports(array $aPageDetails): array
    {
        return [
            'imports' => $this->getDataByUserDetails($aPageDetails),
            'count'   => $this->getAllDataCountByUserId($aPageDetails['user_id'])
        ];
    }

    /**
     * Imports the file to the database
     *
     * @param  array $aFile
     * @return RedirectResponse | array
     */
    public function importFile(array $aFile): RedirectResponse | array
    {
        try {
            $aFile['user_id'] = Auth::user()->id;
            $oSpreadSheet = IOFactory::load($aFile['import_file']);
            // Get the active sheet
            $aWorkSheet = $oSpreadSheet->getActiveSheet();

            // Get the highest row number and column letter
            $iHighestRow = $aWorkSheet->getHighestRow();
            $sHighestColumn = $aWorkSheet->getHighestColumn();
            $aSpreadSheetData = [
                'work_sheet' => $aWorkSheet,
                'highest_row' => $iHighestRow,
                'highest_column' => $sHighestColumn
            ];
            $aImportedData = $this->getAllRowsFromExcel($aSpreadSheetData,  $aFile['user_id']);
            $mValidatorResponse = $this->validateRowData($aImportedData);

            if ($mValidatorResponse !== true) {
                return $mValidatorResponse;
            }
            $oBatch = $this->dispatchImports($aImportedData);
            return [
                'message'  => 'Importing...',
                'batch_id' => $oBatch->id,
                'status'   => 200
            ];
        } catch (\Exception $oException) {
            // handle any exceptions that may occur in importFile
            return [
                'message' => 'Failed to add data.',
                'error'   => $oException->getMessage(),
                'status'  => 500
            ];
        }   
    }
    
    /**
     * Fetch the import data by Import Id and User Id
     *
     * @param  array $aImportDetails
     * @return object | null
     */
    public function getImportDataById(array $aImportDetails): object | null
    {
        $aImportDetails['user_id'] = Auth::user()->id;
        return DB::table('import')->where('user_id', '=', $aImportDetails['user_id'])
                                  ->where('import_id', '=', $aImportDetails['import_id'])
                                  ->first();
    }
    
    /**
     * Returns all the row in the Excel file in array form
     *
     * @param  array $aSpreadSheetData
     * @param  string $sUserId
     * @return array
     */
    private function getAllRowsFromExcel(array $aSpreadSheetData, string $sUserId): array
    {
        $aImportedData = [];
        $aHeader = [
            'A' => 'last_name',
            'B' => 'first_name',
            'C' => 'middle_name',
            'D' => 'address_street',
            'E' => 'address_brgy',
            'F' => 'address_city',
            'G' => 'address_province',
            'H' => 'contact_phone',
            'I' => 'contact_mobile',
            'J' => 'email'
        ];
        $aWorkSheet = $aSpreadSheetData['work_sheet'];
        $iHighestRow = $aSpreadSheetData['highest_row'];
        $sHighestColumn = $aSpreadSheetData['highest_column'];

        // Loop through each row and column to get the cell values
        for ($iRow = 2; $iRow <= $iHighestRow; $iRow++) {
            $aRowData = [];
            for ($aColumn = 'A'; $aColumn <= $sHighestColumn; $aColumn++) {
                $cellValue = $aWorkSheet->getCell($aColumn . $iRow)->getValue();
                $aRowData[$aHeader[$aColumn]] = $cellValue;
            }
            // Save the row data to a variable that will carry it all
            $aRowData['user_id'] = $sUserId;
            $aImportedData[] = $aRowData;
        }
        return $aImportedData;
    }
    
    /**
     * Validation for rows of the file
     *
     * @param  array $aRowData
     * @return  RedirectResponse | bool
     */
    private function validateRowData(array $aRowData): RedirectResponse | bool
    {
        Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\+?[\d ()\-\.]{7,15}$/', $value);
        });
        foreach ($aRowData as $aData) {
            $validator = Validator::make($aData, [
                'first_name' => 'required|string|min:2|max:50',
                'last_name' => 'required|string|min:2|max:50',
                'middle_name' => 'string|min:2|max:50',
                'address_street' => 'required|string|min:2|max:100',
                'address_brgy' => 'required|string|min:2|max:100',
                'address_city' => 'required|string|min:2|max:50',
                'address_province' => 'required|string|min:2|max:50',
                'contact_phone' => 'phone_number|max:15',
                'contact_mobile' => 'required|phone_number|min:9|max:15',
                'email' => 'required|email|max:255|unique:import',
            ],
            [
                'contact_phone'  => 'The phone number field is not valid.',
            ],
            [
                'first_name' => 'first name',
                'last_name'  => 'last name',
                'middle_name' => 'middle name',
                'address_street'  => 'street',
                'address_brgy' => 'barangay',
                'address_city'  => 'city',
                'address_province' => 'province',
                'contact_phone'  => 'phone number',
                'contact_mobile' => 'mobile number',
            ]
            );
            if ($validator->stopOnFirstFailure()->fails()) {
                $aErrors = $validator->errors();
                $sErrorKey = $aErrors->keys()[0];
                $sErrorMessage = 'From the data of: ' . $aData['email'];
                $oCustomMessage = $aErrors->messages();
                $oCustomMessage[$sErrorKey][0] = $sErrorMessage;
                $aErrors->merge($oCustomMessage);
                return redirect('home')
                            ->withErrors($validator)
                            ->withInput();
            }
        }
        return true;
    }
    
    /**
     * Chunk and dispatch the imported data
     *
     * @param  array $aImportedData
     * @return Batch
     */
    private function dispatchImports(array $aImportedData): Batch
    {
        $aChunkedImportedData = array_chunk($aImportedData, 300);// chunks the data to 300
        $oBatch = Bus::batch([])->dispatch();
        foreach ($aChunkedImportedData as $iIndex => $aImportRow) {
            $oBatch->add(new ProcessImport($aChunkedImportedData[$iIndex]));
        }
        return $oBatch;
    }
}