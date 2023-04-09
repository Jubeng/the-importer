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
        $user = User::factory()->create();

        $this->actingAs($user);
        Storage::fake('local');
        $file = UploadedFile::fake()->create('file10rows.xlsx', 5120); // create a fake file with 5120 bytes size
        
        $response = $this->post('/import', [
            'import_file' => $file
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function FunctionName(Type $var = null)
    {
        # code...
    }
}
