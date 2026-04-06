<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterPermission extends Model
{
    protected $fillable = ['user_id', 'router_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }
}
