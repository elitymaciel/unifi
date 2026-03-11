<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciamento de Dispositivos') }}
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

                    <h3 class="text-lg font-bold mb-4">Clientes Conectados</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nome do Dispositivo</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">MAC Address</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Endereço IP</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Conexão</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rede Conectada</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($clients as $client)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $client->name ?? $client->hostname ?? 'Desconhecido' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-500">{{ $client->mac }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $client->ip ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full {{ $client->is_wired ? 'bg-gray-100 text-gray-700' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $client->is_wired ? 'Cabeada' : 'WiFi' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-700">
                                                @if($client->is_wired)
                                                    <span class="text-gray-400 italic">Rede Local (LAN)</span>
                                                @else
                                                    <span class="text-indigo-600">{{ $client->essid ?? 'Desconhecido' }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum cliente conectado encontrado.</td>
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
