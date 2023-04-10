<?php

namespace Tests\Feature;

use App\Models\ImportModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_valid_file(): void
    {
        $this->withoutExceptionHandling();
        $oUser = User::factory()->create();

        $this->actingAs($oUser);
        Storage::fake('local');
        $oFile = UploadedFile::fake()->create('file10rows.xlsx', 5120); // create a fake file with 5120 bytes size
        
        $oResponse = $this->post('/import', [
            'import_file' => $oFile
        ]);
        $oResponse->assertStatus(302);
    }

    public function test_view_edit_data(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $this->actingAs($user);
        $aImportData = ImportModel::factory()->create();

        $oResponse = $this->post('/import', [
            'import_id' => $aImportData->import_id
        ]);
        dd($oResponse);
        $oResponse->assertStatus(302);
    }
}
