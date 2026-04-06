<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Http\Requests\Admin\StoreRouterRequest;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function index()
    {
        $routers = Router::all();
        return view('admin.routers.index', compact('routers'));
    }

    public function store(StoreRouterRequest $request)
    {
        Router::create($request->validated());
        return back()->with('success', 'Roteador adicionado com sucesso!');
    }

    public function destroy(Router $router)
    {
        $router->delete();
        return back()->with('success', 'Roteador removido com sucesso!');
    }
}
