<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function sitePermissions()
    {
        return $this->hasMany(SitePermission::class);
    }

    public function routerPermissions()
    {
        return $this->hasMany(RouterPermission::class);
    }

    public function wifiPermissions()
    {
        return $this->hasMany(WifiPermission::class);
    }

    public function allowedSites()
    {
        return $this->sitePermissions()->pluck('site_name')->toArray();
    }

    public function allowedRouters()
    {
        return $this->routerPermissions()->pluck('router_id')->toArray();
    }

    public function allowedWlans(string $siteName)
    {
        return $this->wifiPermissions()
            ->where('site_name', $siteName)
            ->pluck('wlan_id')
            ->toArray();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
