<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Redes - Visão Geral') }}
            </h2>
            
            <div class="flex items-center space-x-4">
                <form action="{{ route('unifi.select-site') }}" method="POST" id="site-selector-form">
                    @csrf
                    <select name="site_id" onchange="document.getElementById('site-selector-form').submit()" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        @foreach($sites ?? [] as $site)
                            <option value="{{ $site->name }}" {{ session('unifi_site_id', config('unifi.site_id')) == $site->name ? 'selected' : '' }}>
                                Site: {{ $site->desc }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- WiFi Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Redes WiFi</div>
                    <div class="text-3xl font-bold text-indigo-600 mt-2">{{ count($wlans ?? []) }}</div>
                    <div class="mt-4">
                        <a href="{{ route('unifi.wifi') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">Gerenciar WiFi &rarr;</a>
                    </div>
                </div>

                <!-- Device Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Dispositivos UniFi</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ count($devices ?? []) }}</div>
                    <div class="mt-4 text-sm text-gray-500">APs, Switches, etc.</div>
                </div>

                <!-- Client Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Clientes Conectados</div>
                    <div class="text-3xl font-bold text-blue-600 mt-2">{{ count($clients ?? []) }}</div>
                    <div class="mt-4">
                        <a href="{{ route('unifi.devices') }}" class="text-blue-600 hover:text-blue-900 text-sm font-semibold">Gerenciar Dispositivos &rarr;</a>
                    </div>
                </div>
            </div>

            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Erro!</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('unifi.wifi') }}" class="text-indigo-600 hover:underline">Alterar Nome do WiFi (SSID)</a></li>
                        <li><a href="{{ route('unifi.devices') }}" class="text-indigo-600 hover:underline">Gerenciar Dispositivos e Filtro MAC</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
