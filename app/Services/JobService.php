<?php

namespace App\Services;

use App\Models\JobModel;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * JobService
 */
class JobService
{    
    /**
     * fetch the batch details by batch_id 
     *
     * @param  array $aJobDetails
     * @return int | bool
     */
    public function getImportProgressById(array $aJobDetails): int | bool
    {
        try {
            $oBatchDetails = JobModel::where('id', $aJobDetails['batch_id']);
            if ($oBatchDetails->count()) {
                $oBatch = $oBatchDetails->first();
                $iTotalJobsDone = $oBatch->total_jobs - $oBatch->pending_jobs;
                $iProgress = (int)round(($iTotalJobsDone / $oBatch->total_jobs)* 100, 0, PHP_ROUND_HALF_DOWN);
                return $iProgress;
            }
            return false;
        } catch (Exception $oException) {
            Log::error('Error occurred while getting import progress: ' . $oException);
            return false;
        }
    }
}