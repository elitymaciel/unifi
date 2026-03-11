<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciar WiFi') }} - <span class="text-indigo-600">{{ $activeSite->desc ?? 'Site Padrão' }}</span>
            </h2>
            <a href="{{ route('unifi.networks') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">&larr; Voltar para Visão Geral</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 text-sm font-medium text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 text-sm font-medium text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-bold mb-4">Redes WiFi Disponíveis</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nome da Rede (SSID)</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Senha</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($wlans as $wlan)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $wlan->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <code class="px-2 py-1 bg-gray-100 rounded text-sm text-indigo-700 font-bold">
                                                {{ $wlan->x_passphrase ?? '********' }}
                                            </code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full {{ $wlan->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $wlan->enabled ? 'Ativo' : 'Desativado' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('unifi.wifi.mac-filters', $wlan->_id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-indigo-100 transition">
                                                Gerenciar Filtros MAC ({{ count($wlan->mac_filter_list ?? []) }} Permitidos)
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Nenhuma rede WiFi disponível para gerenciamento.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
