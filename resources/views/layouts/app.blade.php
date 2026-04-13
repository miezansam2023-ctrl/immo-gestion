<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoGestion - @yield('title')</title>


    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── FONCTIONS SWEETALERT2 GLOBALES ─────────────────────────────────

        /**
         * Confirmation standard avec SweetAlert2
         */
        async function confirmDelete(message = 'Êtes-vous sûr ?') {
            const result = await Swal.fire({
                title: 'Confirmation',
                html: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, continuer',
                cancelButtonText: 'Annuler',
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
            return result.isConfirmed;
        }

        /**
         * Confirmation de déconnexion
         */
        async function confirmLogout() {
            const result = await Swal.fire({
                title: 'Déconnexion',
                html: 'Êtes-vous sûr de vouloir vous déconnecter ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, me déconnecter',
                cancelButtonText: 'Annuler',
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
            return result.isConfirmed;
        }

        /**
         * Confirmation de (dés)activation d'un compte
         */
        async function confirmToggle(action, userName, form) {
            const result = await Swal.fire({
                title: action === 'desactiver' ? 'Désactivation de compte' : 'Activation de compte',
                html: `Êtes-vous sûr de vouloir ${action === 'desactiver' ? 'désactiver' : 'activer'} le compte de <strong>${userName}</strong> ?`,
                icon: action === 'desactiver' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'desactiver' ? '#dc2626' : '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: action === 'desactiver' ? 'Oui, désactiver' : 'Oui, activer',
                cancelButtonText: 'Annuler',
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
            if (result.isConfirmed && form) {
                form.submit();
            }
            return result.isConfirmed;
        }

        /**
         * Alerte simple
         */
        function alertWithSweetAlert(message, type = 'info') {
            Swal.fire({
                title: type.charAt(0).toUpperCase() + type.slice(1),
                html: message,
                icon: type,
                confirmButtonColor: '#1e293b',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        }

        /**
         * Toast (notification)
         */
        function toastWithSweetAlert(message, type = 'success', timer = 3000) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: timer,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: type,
                title: message
            });
        }
    </script>

    {{--  --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="bg-gray-100 font-sans">
    <div class="min-h-screen">
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">

                    {{-- Logo --}}
                    <a href="{{ route('dashboard') }}" class="text-xl font-black uppercase tracking-tighter text-indigo-600">
                        Immo<span class="text-slate-900">Gestion</span>
                    </a>

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
                        @auth
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
                            <form method="POST" action="{{ route('logout') }}" class="hidden lg:block"
                                onsubmit="event.preventDefault(); confirmLogout().then(confirmed => { if(confirmed) this.submit(); })">
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
                        @endauth
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
                    <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-gray-100"
                        onsubmit="event.preventDefault(); confirmLogout().then(confirmed => { if(confirmed) this.submit(); })">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-4"></i> Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        
        {{-- DATE & HEURE en bas de la navbar --}}
        <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-2 py-2">
            <div class="flex justify-center">
                <div
                    class="bg-gradient-to-r from-gray-50 to-gray-100 shadow-inner rounded-full border border-gray-200">
                    <div class="flex items-center space-x-3 text-sm">
                        <span class="font-medium text-gray-700 tracking-wide">
                            {{ Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                        </span>
                        <span class="text-gray-400">•</span>
                        <span class="font-mono text-indigo-600 font-semibold">
                            {{ Carbon\Carbon::now()->format('H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Alertes de Succès --}}
                @if (session('success'))
                    <script>
                        (function() {
                            let message = @json(session('success'));
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Opération réussie',
                                    html: message,
                                    icon: 'success',
                                    confirmButtonColor: '#10b981',
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                });
                            } else if (typeof alert !== 'undefined') {
                                alert(message);
                            }
                        })();
                    </script>
                @endif

                {{-- Alertes d'Erreur --}}
                @if ($errors->any())
                    <script>
                        (function() {
                            let errors = @json($errors->all());
                            let errorHtml = '<ul class="list-disc ml-5 mt-1">';
                            errors.forEach(function(error) {
                                errorHtml += '<li>' + error + '</li>';
                            });
                            errorHtml += '</ul>';
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Oups ! Des erreurs ont été détectées',
                                    html: errorHtml,
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444',
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                });
                            } else if (typeof alert !== 'undefined') {
                                alert('Erreurs: ' + errors.join('\n'));
                            }
                        })();
                    </script>
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

    <footer>
        <div class="bg-gray-800 py-12">
            <div class="container mx-auto px-6 text-center text-white">
                <p class="text-sm mb-4">© 2026 ImmoGestion. Tous droits réservés.</p>
                <div class="space-x-4">
                    <a href="#" class="text-gray-400 hover:text-gray-200 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-200 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-200 transition">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
    </footer>

</body>

</html>
