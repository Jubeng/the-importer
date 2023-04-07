<?php

namespace Tests\Feature;

use App\Models\ImportModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_valid_row(): void
    {
        $this->withoutExceptionHandling();

        Storage::fake('local');

        $aImportedFile = UploadedFile::fake()->create('test.xlsx', 5000);
        // $aImportData = ImportModel::factory()->make()->toArray();

        $oResponse = $this->post('/import', [
            'import_file' => $aImportedFile
        ]);

        // $oImport = ImportModel::first(); 

        // $this->assertEquals(1, $oImport->employee_id);

        $oResponse->assertStatus(302);
    }
}
