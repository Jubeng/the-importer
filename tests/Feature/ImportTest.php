<?php

namespace Tests\Feature;

use App\Models\ImportModel;
use App\Models\JobModel;
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
     * Test the validity of the uploaded file
     * 
     * @test
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
    
    /**
     * test the redirection if user wants to edit a data
     *
     * @test
     */
    public function test_view_edit_data(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $aImportData = ImportModel::factory()->create();
        $oResponse = $this->get('/edit?import_id=' . $aImportData->id);
        $oResponse->assertStatus(302);
    }

    /**
     * test edit functionality
     *
     * @test
     */
    public function test_edit_data(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $aImportData = ImportModel::factory()->create()->toArray();
        $aImportData['email'] = 'testing@exampl.com';
        $oResponse = $this->put('/edit-data', $aImportData);
        $oResponse->assertStatus(302);
    }

    /**
     * test delete a single row of the user
     *
     * @test
     */
    public function test_delete_single_data(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $aImportData = ImportModel::factory()->create()->toArray();

        $oResponse = $this->post('/delete', [
            'type' => 'single',
            'import_id' => $aImportData['id'],
            'email' => $aImportData['email']
        ]);
        $oResponse->assertStatus(302);
    }

    /**
     * test delete all data of the user
     *
     * @test
     */
    public function test_delete_all_data(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $aImportData = ImportModel::factory()->create()->toArray();

        $oResponse = $this->post('/delete', [
            'type' => 'all',
            'import_id' => $aImportData['id']
        ]);
        $oResponse->assertStatus(302);
    }

    /**
     * test to get the current progress of import
     *
     * @test
     */
    public function test_get_progress_of_import(): void
    {
        $this->withoutExceptionHandling();

        //can't create a factory for job_batches so a fixed array is created
        $aJobData = [
            'id' => '13215-554dfs56',
            'name' => '',
            'total_jobs' => 4,
            'pending_jobs' => 4,
            'failed_jobs' => 2,
            'failed_job_ids' => null,
            'options' => null,
            'cancelled_at' => null,
            'created_at' => now()->unix(),
            'finished_at' => null,
        ];

        $oResponse = $this->get('/check-job-progress', $aJobData);
        $oResponse->assertStatus(200);
    }
}
