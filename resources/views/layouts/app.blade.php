<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoGestion - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="min-h-screen">
        {{-- <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-black uppercase tracking-tighter text-indigo-600">Immo<span
                                class="text-slate-900">Gestion </span></span>
                    </div>

                    <div class="flex items-center space-x-8 ml-10">
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-chart-line mr-1"></i> Dashboard
                        </a>

                        <a href="{{ route('biens.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('biens.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-building mr-1"></i> Mes Biens
                        </a>

                        <a href="{{ route('locataires.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('locataires.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-users mr-1"></i> Locataires
                        </a>

                        <a href="{{ route('contrats.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('contrats.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-file-contract mr-1"></i> Contrats
                        </a>


                        <a href="{{ route('paiements.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('paiements.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-hand-holding-usd mr-1"></i> Loyers
                        </a>

                        <a href="{{ route('settings.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-cog mr-1"></i> Paramètres
                        </a>

                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">

                            <p class="text-sm font-bold text-gray-800">{{ Auth::user()->prenoms }}
                                {{ Auth::user()->nom }} </p>

                            <p class="text-xs text-indigo-500 font-medium capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        <div
                            class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                            {{ substr(Auth::user()->prenoms, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav> --}}
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">

                    {{-- Logo --}}
                    <span class="text-xl font-black uppercase tracking-tighter text-indigo-600">
                        Immo<span class="text-slate-900">Gestion</span>
                    </span>

                    {{-- Menu desktop --}}
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-chart-line mr-1"></i> Dashboard
                        </a>
                        <a href="{{ route('biens.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('biens.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-building mr-1"></i> Mes Biens
                        </a>
                        <a href="{{ route('locataires.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('locataires.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-users mr-1"></i> Locataires
                        </a>
                        <a href="{{ route('contrats.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('contrats.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-file-contract mr-1"></i> Contrats
                        </a>
                        <a href="{{ route('paiements.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('paiements.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-hand-holding-usd mr-1"></i> Loyers
                        </a>
                        <a href="{{ route('settings.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-cog mr-1"></i> Paramètres
                        </a>
                    </div>

                    {{-- Droite : avatar + hamburger --}}
                    <div class="flex items-center space-x-3">
                        {{-- Avatar (toujours visible) --}}
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-gray-800">{{ Auth::user()->prenoms }}
                                {{ Auth::user()->nom }}</p>
                            <p class="text-xs text-indigo-500 font-medium capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <div
                            class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200 text-sm">
                            {{ substr(Auth::user()->prenoms, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}
                        </div>

                        {{-- Bouton logout desktop --}}
                        <form method="POST" action="{{ route('logout') }}" class="hidden lg:block">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>

                        {{-- Bouton hamburger mobile --}}
                        <button id="hamburger"
                            class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 transition"
                            onclick="toggleMenu()">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Menu mobile --}}
            <div id="mobile-menu" class="lg:hidden hidden border-t border-gray-200 bg-white">
                <div class="px-4 py-2 space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-chart-line w-4"></i> Dashboard
                    </a>
                    <a href="{{ route('biens.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('biens.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-building w-4"></i> Mes Biens
                    </a>
                    <a href="{{ route('locataires.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('locataires.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-users w-4"></i> Locataires
                    </a>
                    <a href="{{ route('contrats.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('contrats.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-file-contract w-4"></i> Contrats
                    </a>
                    <a href="{{ route('paiements.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('paiements.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-hand-holding-usd w-4"></i> Loyers
                    </a>
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('settings.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-cog w-4"></i> Paramètres
                    </a>
                    {{-- Logout mobile --}}
                    <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-gray-100">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-4"></i> Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Alertes de Succès --}}
                {{-- @if (session('success'))
                    <div x-data="{ show: true }" x-show="show"
                        class="mb-6 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
                        <div class="ml-4">
                            <p class="text-[11px] font-bold text-emerald-700 uppercase">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="ml-auto text-emerald-300 hover:text-emerald-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif --}}

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show"
                        class="relative mb-8 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
                        <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xs font-black text-emerald-900 uppercase tracking-widest">Opération réussie
                            </h3>
                            <p class="text-[11px] font-bold text-emerald-700 mt-0.5">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="ml-auto p-2 group"><svg
                                class="h-5 w-5 text-emerald-300 group-hover:text-emerald-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="max-w-7xl mx-auto mt-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <p class="font-bold">Oups ! Il y a des erreurs :</p>
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>

            <script>
                function toggleMenu() {
                    const menu = document.getElementById('mobile-menu');
                    menu.classList.toggle('hidden');
                }
            </script>
            
        </main>
    </div>
</body>

</html>
