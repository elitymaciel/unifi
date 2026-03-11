<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-indigo-900 leading-tight tracking-tight">
            {{ __('AGROPECUARIA CATARATAS') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Global Totals -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Sites -->
                <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total de Sites</p>
                            <p class="text-3xl font-black text-gray-900">{{ $stats['total_sites'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Equipment -->
                <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Equipamentos Gerenciados</p>
                            <p class="text-3xl font-black text-gray-900">{{ $stats['total_devices'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Clients -->
                <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Dispositivos Conectados</p>
                            <p class="text-3xl font-black text-gray-900">{{ $stats['total_clients'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Site Breakdown Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Detalhamento por Site</h3>
                    <span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-600 rounded-full">Atualizado agora</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Descrição do Site</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Dispositivos</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Clientes</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($stats['sites_breakdown'] as $site)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                                {{ substr($site['desc'], 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-bold text-gray-900">{{ $site['desc'] }}</p>
                                                <p class="text-xs text-gray-500">{{ $site['name'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            {{ $site['devices'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            {{ $site['clients'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form action="{{ route('unifi.select-site') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="site_id" value="{{ $site['name'] }}">
                                            <button type="submit" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-4 py-2 rounded-lg font-bold text-sm transition-colors">
                                                Gerenciar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
