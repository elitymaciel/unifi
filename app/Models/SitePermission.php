<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePermission extends Model
{
    protected $fillable = ['user_id', 'site_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
