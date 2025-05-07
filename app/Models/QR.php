<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QR extends Model
{
    protected $table = 'qrs';

    protected $fillable = [
        'hash_id',
        'name',
        'code',
        'class',
        'enterprise',
        'phone',
    ];
}
