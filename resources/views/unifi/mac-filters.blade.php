<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Filtros MAC') }} - <span class="text-indigo-600 font-black">{{ $wlan->name }}</span>
            </h2>
            <a href="{{ route('unifi.wifi') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 text-sm font-bold transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{ showAddModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Context & Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Política de Acesso</p>
                    <p class="text-xl font-black text-gray-800 uppercase">{{ $wlan->mac_filter_policy ?? 'N/A' }}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Status do Filtro</p>
                    <div class="flex items-center">
                        <span class="h-3 w-3 rounded-full {{ ($wlan->mac_filter_enabled ?? false) ? 'bg-emerald-500' : 'bg-gray-300' }} mr-2 shadow-sm shadow-emerald-200"></span>
                        <p class="text-xl font-black text-gray-800 uppercase">{{ ($wlan->mac_filter_enabled ?? false) ? 'Ativado' : 'Desativado' }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Permitidos</p>
                        <p class="text-xl font-black text-indigo-600">{{ count($wlan->mac_filter_list ?? []) }} Dispositivos</p>
                    </div>
                    <button @click="showAddModal = true" class="bg-indigo-600 text-white p-3 rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-2xl shadow-sm">
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- MAC List -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-800">Endereços Autorizados</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Dispositivo</th>
                                <th class="px-8 py-5 text-left text-xs font-black text-gray-400 uppercase tracking-wider">Endereço MAC</th>
                                <th class="px-8 py-5 text-right text-xs font-black text-gray-400 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($wlan->mac_filter_list ?? [] as $mac)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-inner">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $macNames[strtolower($mac)] ?? 'Equipamento Desconhecido' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <code class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-600 tracking-tighter">{{ $mac }}</code>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap text-right">
                                        <form action="{{ route('unifi.wifi.mac-filters.remove') }}" method="POST" class="inline" onsubmit="return confirm('Desautorizar este dispositivo?')">
                                            @csrf
                                            <input type="hidden" name="wlan_id" value="{{ $wlan->_id }}">
                                            <input type="hidden" name="mac" value="{{ $mac }}">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-black rounded-xl text-red-600 bg-red-50/50 hover:bg-red-100/80 uppercase tracking-widest transition-all active:scale-95 outline-none">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Remover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                            </div>
                                            <p class="text-gray-400 font-bold">Nenhum filtro configurado para esta rede.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add MAC Modal (Aligned with User Creation Design) -->
            <div x-show="showAddModal" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    
                    <!-- Backdrop with Blur -->
                    <div x-show="showAddModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 transition-opacity backdrop-blur-sm bg-slate-900/40" 
                         @click="showAddModal = false">
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

                    <!-- Modal Canvas (Matches admin.users.index) -->
                    <div x-show="showAddModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                        
                        <!-- Header with Indigo Background and Icons -->
                        <div class="bg-indigo-600 px-6 py-8 text-center relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="relative z-10">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-md mb-4 shadow-inner">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-white tracking-tight">Autorizar MAC</h3>
                                <p class="text-indigo-100 text-sm font-medium mt-1">Liberar acesso WiFi para um novo equipamento</p>
                            </div>
                        </div>

                        <div class="px-8 py-8">
                            <form id="add-mac-form" action="{{ route('unifi.devices.mac-filter') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="wlan_id" value="{{ $wlan->_id }}">
                                
                                <div class="group">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Endereço Físico (MAC)</label>
                                    <input type="text" name="mac" required placeholder="00:11:22:33:44:55"
                                           class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-mono font-bold uppercase">
                                </div>

                                <div class="group">
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 group-focus-within:text-indigo-600 transition-colors">Identificação do Nome</label>
                                    <input type="text" name="name" placeholder="Ex: Celular do João"
                                           class="w-full bg-slate-50 border-gray-200 rounded-2xl py-3 px-4 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-gray-800 placeholder-gray-400 font-semibold">
                                </div>
                            </form>
                        </div>

                        <!-- Actions Area -->
                        <div class="px-8 py-6 bg-slate-50/50 border-t border-gray-100 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" form="add-mac-form" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-transparent px-8 py-3 bg-indigo-600 text-sm font-black text-white hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 transition-all outline-none">
                                Autorizar
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            <button type="button" @click="showAddModal = false" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-2xl border border-gray-200 px-8 py-3 bg-white text-sm font-bold text-slate-600 hover:bg-gray-50 active:scale-95 transition-all outline-none">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
