<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    protected $table = 'routers';

    protected $fillable = [
        'name',
        'host',
        'username',
        'password',
        'port',
    ];
}
