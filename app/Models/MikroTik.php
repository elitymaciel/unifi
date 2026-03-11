<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MikroTik extends Model
{
    protected $table = 'mikrotiks';

    protected $fillable = [
        'name',
        'host',
        'username',
        'password',
        'port',
    ];
}
