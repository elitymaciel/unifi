<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SitePermission;
use App\Services\UniFiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $unifi;

    public function __construct(UniFiService $unifi)
    {
        $this->unifi = $unifi;
    }

    public function index()
    {
        $users = User::with('sitePermissions')->get();
        $allSites = $this->unifi->list_sites();
        
        return view('admin.users.index', compact('users', 'allSites'));
    }

    public function createUser(\App\Http\Requests\Admin\StoreUserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Usuário criado com sucesso.');
    }

    public function toggleSitePermission(\App\Http\Requests\Admin\SitePermissionRequest $request)
    {
        $permission = SitePermission::where('user_id', $request->user_id)
            ->where('site_name', $request->site_name)
            ->first();

        if ($permission) {
            $permission->delete();
            $status = 'removida';
        } else {
            SitePermission::create([
                'user_id' => $request->user_id,
                'site_name' => $request->site_name,
            ]);
            $status = 'concedida';
        }

        return back()->with('success', "Permissão para o site {$status} com sucesso.");
    }
    
    public function updateRole(\App\Http\Requests\Admin\UpdateRoleRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update(['role' => $request->role]);
        
        return back()->with('success', 'Nível de acesso atualizado.');
    }
}
