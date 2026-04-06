<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SitePermission;
use App\Models\Router;
use App\Models\RouterPermission;
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
        $users = User::with(['sitePermissions', 'routerPermissions', 'wifiPermissions'])->get();
        $allSites = $this->unifi->list_sites();
        $allRouters = Router::all();
        $allWlansPerSite = $this->unifi->listAllWlansPerSite();
        
        return view('admin.users.index', compact('users', 'allSites', 'allRouters', 'allWlansPerSite'));
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
            // Also delete any WiFi permissions for this site if the site is removed
            \App\Models\WifiPermission::where('user_id', $request->user_id)
                ->where('site_name', $request->site_name)
                ->delete();

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

    public function toggleRouterPermission(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'router_id' => 'required|exists:routers,id',
        ]);

        $permission = RouterPermission::where('user_id', $request->user_id)
            ->where('router_id', $request->router_id)
            ->first();

        if ($permission) {
            $permission->delete();
            $status = 'removida';
        } else {
            RouterPermission::create([
                'user_id' => $request->user_id,
                'router_id' => $request->router_id,
            ]);
            $status = 'concedida';
        }

        return back()->with('success', "Permissão para o roteador {$status} com sucesso.");
    }

    public function toggleWifiPermission(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'site_name' => 'required|string',
            'wlan_id' => 'required|string',
        ]);

        $permission = \App\Models\WifiPermission::where('user_id', $request->user_id)
            ->where('site_name', $request->site_name)
            ->where('wlan_id', $request->wlan_id)
            ->first();

        if ($permission) {
            $permission->delete();
            $status = 'removida';
        } else {
            \App\Models\WifiPermission::create([
                'user_id' => $request->user_id,
                'site_name' => $request->site_name,
                'wlan_id' => $request->wlan_id,
            ]);
            $status = 'concedida';
        }

        return back()->with('success', "Permissão para a rede WiFi {$status} com sucesso.");
    }
    
    public function updateRole(\App\Http\Requests\Admin\UpdateRoleRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update(['role' => $request->role]);
        
        return back()->with('success', 'Nível de acesso atualizado.');
    }

    public function updateUser(\App\Http\Requests\Admin\UpdateUserRequest $request, User $user)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Você não pode excluir a sua própria conta.');
        }

        $user->delete();

        return back()->with('success', 'Usuário excluído com sucesso.');
    }
}
