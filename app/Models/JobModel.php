<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * JobModel
 */
class JobModel extends Model
{
    use HasFactory;

    protected $table = 'job_batches';
}
