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
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                    <span class="text-xl font-black uppercase tracking-tighter text-indigo-600">Immo<span class="text-slate-900">Gestion </span></span>
                    </div>

                    <div class="flex items-center space-x-8 ml-10">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-chart-line mr-1"></i> Dashboard
                        </a>

                        <a href="{{ route('biens.index') }}" class="text-sm font-medium {{ request()->routeIs('biens.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-building mr-1"></i> Mes Biens
                        </a>

                        <a href="{{ route('locataires.index') }}" class="text-sm font-medium {{ request()->routeIs('locataires.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-users mr-1"></i> Locataires
                        </a>

                        <a href="{{ route('contrats.index') }}" class="text-sm font-medium {{ request()->routeIs('contrats.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-file-contract mr-1"></i> Contrats
                        </a>

                        
                        <a href="{{ route('paiements.index') }}" class="text-sm font-medium {{ request()->routeIs('paiements.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-hand-holding-usd mr-1"></i> Loyers
                        </a>

                        <a href="{{ route('settings.index') }}" class="text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                            <i class="fas fa-cog mr-1"></i> Paramètres
                        </a>

                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            
                            <p class="text-sm font-bold text-gray-800">{{ Auth::user()->prenoms }} {{ Auth::user()->nom }} </p> 
                          
                            <p class="text-xs text-indigo-500 font-medium capitalize">{{ Auth::user()->role }}</p> 
                        </div>
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
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
        </nav>

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>