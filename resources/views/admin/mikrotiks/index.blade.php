<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Gestão de <span class="text-indigo-600 font-black">MikroTiks</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{ showCreateModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Admin Navigation Tabs -->
            <div class="flex space-x-4 mb-8">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('admin.users.index') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    Usuários
                </a>
                <a href="{{ route('admin.mikrotiks.index') }}" class="px-6 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('admin.mikrotiks.index') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    MikroTiks
                </a>
            </div>
            
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-xl font-bold text-gray-800">Roteadores Cadastrados</h3>
                <button @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Adicionar MikroTik
                </button>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm">
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- MikroTiks List -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Nome / Identificação</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Host (IP)</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Porta</th>
                                <th class="px-8 py-5 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($mikrotiks as $m)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-black text-sm">
                                                MT
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $m->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $m->username }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <code class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-600 tracking-tighter">{{ $m->host }}</code>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-500 font-bold">
                                        {{ $m->port }}
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap text-right">
                                        <form action="{{ route('admin.mikrotiks.destroy', $m) }}" method="POST" onsubmit="return confirm('Remover este MikroTik?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-black rounded-xl text-red-600 bg-red-50 hover:bg-red-100 uppercase tracking-widest transition-all active:scale-95">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Remover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-gray-500 font-bold">
                                        Nenhum MikroTik cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create Modal -->
            <div x-show="showCreateModal" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity backdrop-blur-sm bg-slate-900/40" @click="showCreateModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

                    <div x-show="showCreateModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                        
                        <div class="bg-indigo-600 px-6 py-8 text-center relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="relative z-10">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-md mb-4 shadow-inner">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-white tracking-tight">Novo MikroTik</h3>
                                <p class="text-indigo-100 text-sm font-medium mt-1">Configure um novo roteador para gestão de Hotspot</p>
                            </div>
                        </div>

                        <div class="px-8 py-8">
                            <form id="create-mikrotik-form" action="{{ route('admin.mikrotiks.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nome da Unidade / Descrição</label>
                                    <input type="text" name="name" required placeholder="Ex: Matriz - Recepcão" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Endereço IP / Host</label>
                                        <input type="text" name="host" required placeholder="192.168.88.1" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Porta API</label>
                                        <input type="number" name="port" required value="8728" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Usuário API</label>
                                        <input type="text" name="username" required placeholder="admin" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Senha API</label>
                                        <input type="password" name="password" required class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="px-8 py-6 bg-slate-50/50 border-t border-gray-100 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" form="create-mikrotik-form" class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-transparent px-8 py-3 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 hover:shadow-lg active:scale-95 transition-all outline-none">
                                Salvar MikroTik
                            </button>
                            <button type="button" @click="showCreateModal = false" class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-gray-200 px-8 py-3 bg-white text-sm font-bold text-slate-600 hover:bg-gray-50 active:scale-95 transition-all outline-none">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
