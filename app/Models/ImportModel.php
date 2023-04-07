<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for import table
 */
class ImportModel extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'import';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'address_street',
        'address_brgy',
        'address_city',
        'address_province',
        'contact_phone',
        'contact_mobile',
        'email',
    ];
}
