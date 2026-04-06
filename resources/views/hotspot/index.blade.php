<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Gestão de <span class="text-indigo-600 font-black">Visitantes WiFi</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{ showCreateModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Usuários Visitantes</h3>
                    @if(isset($activeRouter))
                        <p class="text-sm text-indigo-600 font-bold">Roteador Ativo: {{ $activeRouter->name }} ({{ $activeRouter->host }})</p>
                    @endif
                </div>

                <div class="flex space-x-4">
                    <!-- Router Selector -->
                    @if($routers->count() > 1)
                        <form action="{{ route('hotspot.index') }}" method="GET" class="flex items-center">
                            <select name="router_id" onchange="this.form.submit()" class="rounded-xl border-gray-200 text-sm font-bold bg-white focus:ring-indigo-500 pr-10">
                                @foreach($routers as $r)
                                    <option value="{{ $r->id }}" {{ $activeRouter->id == $r->id ? 'selected' : '' }}>
                                        {{ $r->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif

                    @if(isset($activeRouter))
                        <button @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Novo Usuário
                        </button>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm">
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error') || isset($error))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                    <p class="text-sm font-bold text-red-800">{{ session('error') ?? $error }}</p>
                </div>
            @endif

            <!-- Users List -->
            @if(isset($users))
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Usuário</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Perfil</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Comentário</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Dados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($users as $user)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm">
                                                {{ substr($user->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $user->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-black uppercase">
                                            {{ $user->profile ?? 'default' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->comment ?? '-' }}
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap text-xs font-mono text-gray-400">
                                        U: {{ $user->{'uptime'} ?? '0s' }} | D: {{ $user->{'bytes-out'} ?? '0' }} B
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-gray-500 font-bold">
                                        Nenhum visitante encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Create User Modal -->
            @if(isset($activeRouter))
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-white tracking-tight">Novo Visitante</h3>
                                <p class="text-indigo-100 text-sm font-medium mt-1">Crie credenciais para o roteador <span class="underline">{{ $activeRouter->name }}</span></p>
                            </div>
                        </div>

                        <div class="px-8 py-8">
                            <form id="create-hotspot-user-form" action="{{ route('hotspot.users.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="router_id" value="{{ $activeRouter->id }}">
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Usuário / Login</label>
                                    <input type="text" name="name" required class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Senha</label>
                                    <input type="password" name="password" required class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Perfil</label>
                                        <select name="profile" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                            <option value="default">Default</option>
                                            <option value="1h">1 Hora</option>
                                            <option value="unlimited">Ilimitado</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Comentário</label>
                                        <input type="text" name="comment" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="px-8 py-6 bg-slate-50/50 border-t border-gray-100 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" form="create-hotspot-user-form" class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-transparent px-8 py-3 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 hover:shadow-lg active:scale-95 transition-all outline-none">
                                Criar Visitante
                            </button>
                            <button type="button" @click="showCreateModal = false" class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-gray-200 px-8 py-3 bg-white text-sm font-bold text-slate-600 hover:bg-gray-50 active:scale-95 transition-all outline-none">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
