<?php

namespace App\Http\Controllers;

use App\Services\MikroTikService;
use App\Http\Requests\Hotspot\StoreHotspotUserRequest;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    protected $mikrotik;

    public function __construct(MikroTikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    public function index(Request $request)
    {
        $mikrotiks = \App\Models\MikroTik::all();
        
        if ($mikrotiks->isEmpty()) {
            return view('hotspot.index', ['users' => collect([]), 'mikrotiks' => $mikrotiks])
                ->with('error', 'Nenhum MikroTik encontrado no sistema. Por favor, cadastre um roteador na área de Administração.');
        }

        $selectedId = $request->get('mikrotik_id', session('mikrotik_id', $mikrotiks->first()->id));
        session(['mikrotik_id' => $selectedId]);
        
        $activeMikroTik = $mikrotiks->firstWhere('id', $selectedId) ?? $mikrotiks->first();

        try {
            $service = new MikroTikService($activeMikroTik);
            $usersRaw = $service->listHotspotUsers();
            
            $users = collect($usersRaw)->map(function($item) {
                $userData = [];
                foreach ($item as $pair) {
                    foreach ($pair as $key => $value) {
                        $userData[$key] = $value;
                    }
                }
                return (object) $userData;
            });

            return view('hotspot.index', compact('users', 'mikrotiks', 'activeMikroTik'));
        } catch (\Exception $e) {
            return view('hotspot.index', compact('mikrotiks', 'activeMikroTik'))
                ->with('users', collect([]))
                ->with('error', "Erro ao conectar ao MikroTik ({$activeMikroTik->name}): " . $e->getMessage());
        }
    }

    public function store(StoreHotspotUserRequest $request)
    {
        try {
            $mikrotik = \App\Models\MikroTik::findOrFail($request->mikrotik_id);
            $service = new MikroTikService($mikrotik);
            
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
