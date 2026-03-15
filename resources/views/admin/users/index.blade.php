<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Administração de Usuários e Permissões') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{ 
        showCreateModal: false, 
        showEditModal: false,
        editingUser: { id: '', name: '', email: '', role: '' },
        openEditModal(user) {
            this.editingUser = { ...user };
            this.showEditModal = true;
        }
    }">
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
                <h3 class="text-xl font-bold text-gray-800">Gestão de Usuários</h3>
                <button @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Novo Usuário
                </button>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-emerald-500">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-red-500">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                    <ul class="list-disc list-inside text-sm text-red-700 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Users List and Permissions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="text-lg font-bold text-gray-800">Acessos e Permissões</h3>
                </div>
                <!-- ... remains same ... -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acesso</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Permissões de Site</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form action="{{ route('admin.users.role') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <select name="role" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border-gray-200 {{ $user->isAdmin() ? 'text-red-600 bg-red-50 border-red-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100' }}">
                                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>USER</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>ADMIN</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->isAdmin())
                                            <div class="text-center text-xs font-bold text-gray-400 italic">Administradores possuem acesso total a todos os sites</div>
                                        @else
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                                @foreach($allSites as $site)
                                                    @php
                                                        $hasAccess = $user->sitePermissions->contains('site_name', $site->name);
                                                    @endphp
                                                    <form action="{{ route('admin.users.sites') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                        <input type="hidden" name="site_name" value="{{ $site->name }}">
                                                        <button type="submit" class="w-full text-left px-3 py-1.5 rounded-lg text-[11px] font-bold border transition-all {{ $hasAccess ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-400 hover:border-indigo-300 hover:text-indigo-600' }}">
                                                            <div class="flex items-center justify-between">
                                                                <span>{{ $site->desc }}</span>
                                                                @if($hasAccess)
                                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                                @endif
                                                            </div>
                                                        </button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button @click="openEditModal({ id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', role: '{{ $user->role }}' })" 
                                                    class="text-indigo-500 hover:text-indigo-700 transition-colors p-2 rounded-lg hover:bg-indigo-50" title="Editar Usuário">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>

                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-lg hover:bg-red-50" title="Excluir Usuário">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Sua conta</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create User Modal (Premium Redesign) -->
            <div x-show="showCreateModal" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    
                    <!-- Backdrop with Blur -->
                    <div x-show="showCreateModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 transition-opacity backdrop-blur-sm bg-slate-900/40" 
                         @click="showCreateModal = false">
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

                    <!-- Modal Canvas -->
                    <div x-show="showCreateModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                        
                        <!-- Header with Icon -->
                        <div class="bg-indigo-600 px-6 py-8 text-center relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="relative z-10">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-md mb-4 shadow-inner">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-white tracking-tight">Novo Usuário</h3>
                                <p class="text-indigo-100 text-sm font-medium mt-1">Cadastre um novo administrador ou usuário comum</p>
                            </div>
                        </div>

                        <div class="px-8 py-8">
                            <form id="create-user-form" action="{{ route('admin.users.create') }}" method="POST" class="space-y-6">
                                @csrf
                                
                                <div class="group">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Nome Completo</label>
                                    <input type="text" name="name" required placeholder="Ex: João Silva"
                                           class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-semibold">
                                </div>

                                <div class="group">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Email Corporativo</label>
                                    <input type="email" name="email" required placeholder="joao@unifi.com"
                                           class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-semibold">
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Senha</label>
                                        <input type="password" name="password" required placeholder="••••••••"
                                               class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-semibold">
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Função</label>
                                        <select name="role" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 font-bold appearance-none">
                                            <option value="user">Usuário Comum</option>
                                            <option value="admin">Administrador</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Actions Area -->
                        <div class="px-8 py-6 bg-slate-50/50 border-t border-gray-100 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" form="create-user-form" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-transparent px-8 py-3 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 transition-all outline-none">
                                Criar Conta
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            <button type="button" @click="showCreateModal = false" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-gray-200 px-8 py-3 bg-white text-sm font-bold text-slate-600 hover:bg-gray-50 active:scale-95 transition-all outline-none">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit User Modal -->
            <div x-show="showEditModal" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    
                    <div x-show="showEditModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 transition-opacity backdrop-blur-sm bg-slate-900/40" 
                         @click="showEditModal = false">
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

                    <div x-show="showEditModal" 
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-white tracking-tight">Editar Usuário</h3>
                                <p class="text-indigo-100 text-sm font-medium mt-1">Atualize os dados ou altere a senha do usuário</p>
                            </div>
                        </div>

                        <div class="px-8 py-8">
                            <form :action="'{{ route('admin.users.update', ['user' => 'USER_ID']) }}'.replace('USER_ID', editingUser.id)" method="POST" id="edit-user-form" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <div class="group">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Nome Completo</label>
                                    <input type="text" name="name" x-model="editingUser.name" required placeholder="Ex: João Silva"
                                           class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-semibold">
                                </div>

                                <div class="group">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Email Corporativo</label>
                                    <input type="email" name="email" x-model="editingUser.email" required placeholder="joao@unifi.com"
                                           class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-semibold">
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Nova Senha</label>
                                        <input type="password" name="password" placeholder="Mantenha em branco para não alterar"
                                               class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-semibold">
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Função</label>
                                        <select name="role" x-model="editingUser.role" class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 font-bold appearance-none">
                                            <option value="user">Usuário Comum</option>
                                            <option value="admin">Administrador</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="px-8 py-6 bg-slate-50/50 border-t border-gray-100 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" form="edit-user-form" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-transparent px-8 py-3 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 transition-all outline-none">
                                Salvar Alterações
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                            <button type="button" @click="showEditModal = false" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-gray-200 px-8 py-3 bg-white text-sm font-bold text-slate-600 hover:bg-gray-50 active:scale-95 transition-all outline-none">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
        </div>
    </div>
</x-app-layout>
