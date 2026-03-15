<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configuração Inicial - Tech Solutions</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="flex min-h-screen">
        <!-- Left Side: Visual -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-600 overflow-hidden isolate">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-violet-700 opacity-90"></div>
            
            <div class="relative z-10 flex flex-col justify-center px-16 lg:px-24 w-full h-full text-white">
                <div class="mb-12">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-lg">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold tracking-tight">Tech Solutions</span>
                    </div>
                </div>
                
                <h1 class="text-4xl lg:text-5xl font-black tracking-tight mb-6">Configuração do Administrador</h1>
                <p class="text-indigo-100 text-lg max-w-lg mb-8">Nenhum usuário foi encontrado no sistema. Vamos criar a sua conta de administrador principal para começar.</p>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-4 text-indigo-100">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-medium">Segurança total para sua rede</span>
                    </div>
                    <div class="flex items-center gap-4 text-indigo-100">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-medium">Gestão centralizada de sites UniFi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-8 lg:p-12 bg-white">
            <div class="w-full max-w-md mx-auto space-y-6">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 mb-2">Primeiros Passos</h2>
                    <p class="text-slate-500">Crie suas credenciais de administrador.</p>
                </div>

                <form method="POST" action="{{ route('setup.store') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-slate-700">Nome Completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            placeholder="Seu nome" required autofocus>
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-slate-700">E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            placeholder="seu@email.com" required>
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Senha</label>
                        <input type="password" id="password" name="password"
                            class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            placeholder="••••••••" required>
                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirmar Senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            placeholder="••••••••" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full flex justify-center py-4 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all shadow-lg shadow-indigo-200">
                            Finalizar Configuração
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
