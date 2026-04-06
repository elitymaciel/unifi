<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WifiPermission extends Model
{
    protected $fillable = ['user_id', 'site_name', 'wlan_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
