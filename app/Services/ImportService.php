<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * ImportService
 */
class ImportService
{    
    /**
     * importFile
     *
     * @param  array $aFile
     * @return array
     */
    public function importFile(array $aFile): array
    {
        try {
            $oSpreadSheet = IOFactory::load($aFile['import_file']);
            // Get the active sheet
            $aWorkSheet = $oSpreadSheet->getActiveSheet();

            // Get the highest row number and column letter
            $highestRow = $aWorkSheet->getHighestRow();
            $highestColumn = $aWorkSheet->getHighestColumn();
            
            // Loop through each row and column to get the cell values
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($column = 'A'; $column <= $highestColumn; $column++) {
                    $cellValue = $aWorkSheet->getCell($column . $row)->getValue();
                    $rowData[] = $cellValue;
                }
                // Save the row data to the database
                DB::table('import')->insert($rowData);
            }
            return [
                'message' => 'Successfully add data',
                'status' => 201
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

    public function getImports(array $aPageDetails): array
    {
        try {
            $iOffset = ((int)$aPageDetails['page'] - 1) * (int)$aPageDetails['limit']; // Calculate offset

            $aImports = DB::table('import')
                ->skip($iOffset)
                ->take($aPageDetails['limit'])
                ->get()
                ->toArray();
            return $aImports;
        } catch (\Throwable $oException) {
            // handle any exceptions that may occur in importFile
            return [
                'message' => 'Failed to get data.',
                'error' => $oException->getMessage(),
                'status' => 500
            ];
        }
    }
}