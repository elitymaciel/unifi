<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MikroTik;
use App\Http\Requests\Admin\StoreMikroTikRequest;
use Illuminate\Http\Request;

class MikroTikController extends Controller
{
    public function index()
    {
        $mikrotiks = MikroTik::all();
        return view('admin.mikrotiks.index', compact('mikrotiks'));
    }

    public function store(StoreMikroTikRequest $request)
    {
        MikroTik::create($request->validated());
        return back()->with('success', 'MikroTik adicionado com sucesso!');
    }

    public function destroy(MikroTik $mikrotik)
    {
        $mikrotik->delete();
        return back()->with('success', 'MikroTik removido com sucesso!');
    }
}
