<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Services\RouterService;
use App\Http\Requests\Hotspot\StoreHotspotUserRequest;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    protected $router;

    public function __construct(RouterService $router)
    {
        $this->router = $router;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $routers = Router::all();
        } else {
            $allowedIds = $user->allowedRouters();
            $routers = Router::whereIn('id', $allowedIds)->get();
        }
        
        if ($routers->isEmpty()) {
            return view('hotspot.index', ['users' => collect([]), 'routers' => $routers])
                ->with('error', 'Nenhum roteador encontrado ou você não possui permissão para acessar nenhum equipamento.');
        }

        $selectedId = $request->get('router_id', session('router_id', $routers->first()->id));
        
        // Ensure the selected router is allowed
        if (!$routers->contains('id', $selectedId)) {
            $selectedId = $routers->first()->id;
        }
        
        session(['router_id' => $selectedId]);
        
        $activeRouter = $routers->firstWhere('id', $selectedId);

        try {
            $service = new RouterService($activeRouter);
            $usersRaw = $service->listHotspotUsers();
            
            $users = collect($usersRaw)->map(function($item) {
                $userData = [];
                foreach ($item as $pair) {
                    if (is_array($pair)) {
                        foreach ($pair as $key => $value) {
                            $userData[$key] = $value;
                        }
                    }
                }
                return (object) $userData;
            });

            return view('hotspot.index', compact('users', 'routers', 'activeRouter'));
        } catch (\Exception $e) {
            return view('hotspot.index', compact('routers', 'activeRouter'))
                ->with('users', collect([]))
                ->with('error', "Erro ao conectar ao roteador ({$activeRouter->name}): " . $e->getMessage());
        }
    }

    public function store(StoreHotspotUserRequest $request)
    {
        try {
            $router = Router::findOrFail($request->router_id);
            $service = new RouterService($router);
            
            $service->createHotspotUser(
                $request->name,
                $request->password,
                $request->profile,
                $request->comment ?? ''
            );

            return back()->with('success', 'Usuário de Hotspot criado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }
}
