<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * aChunkImportData
     *
     * @var array
     */
    protected $aChunkImportData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $aChunkImportData)
    {
        $this->aChunkImportData = $aChunkImportData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->aChunkImportData as $aImportData) 
        {
            DB::table('import')->insert($aImportData);
        }
    }
}
