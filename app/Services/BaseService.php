<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use PDO;

/**
 * BaseService
 */
class BaseService
{     
    /**
     * Returns a set of 10 rows from the imported data of the current user
     *
     * @param  array $aPageDetails
     * @return array
     */
    protected function getDataByUserDetails(array $aPageDetails): array
    {
        try {
            $iLimit = 10; //limit of rows per page
            $sAdditionalQuery = ' LIMIT :limit OFFSET :offset';
            $aColumns = [
                'import_id',
                'last_name',
                'first_name',
                'middle_name',
                'address_street',
                'address_brgy',
                'address_city',
                'address_province',
                'contact_phone',
                'contact_mobile',
                'email'
            ];
            // dd($aPageDetails);
            if (isset($aPageDetails['export_type']) === true) {
                if ($aPageDetails['export_type'] === 'all') {
                    $sAdditionalQuery = '';
                }
                unset($aColumns[0]);
            }
            $iOffset = ((int)$aPageDetails['page'] - 1) * $iLimit; // Calculate offset,
            
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare('SELECT ' . implode(',', $aColumns) . ' FROM import WHERE user_id = :user_id' . $sAdditionalQuery);
            $stmt->bindParam(':user_id', $aPageDetails['user_id'], PDO::PARAM_INT);
            if (isset($aPageDetails['export_type']) === false || $aPageDetails['export_type'] === 'page') {
                $stmt->bindParam(':limit', $iLimit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $iOffset, PDO::PARAM_INT);
            }
            $stmt->execute();
            $aImports = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $aImports;
        } catch (Exception $oException) {
            return [
                'message' => 'Failed to get data.',
                'error' => $oException->getMessage(),
                'status' => 500
            ];
        }
    }
}