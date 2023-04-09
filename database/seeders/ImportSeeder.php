<?php

namespace Database\Seeders;

use App\Models\ImportModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ImportModel::factory(80000)->create();
    }
}
