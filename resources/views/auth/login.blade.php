@section('title', 'CONNEXION')
<x-guest-layout>

    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Connexion</h2>
        <p class="text-gray-400 text-sm font-medium mt-2">Accédez à votre console de gestion immobilière</p>
    </div>

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            (function() {
                let message = @json(session('success'));
                Swal.fire({
                    title: 'Opération réussie',
                    html: message,
                    icon: 'success',
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                });
            })();
        </script>
    @endif

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="group">
            <label for="email"
                class="block text-[11px] font-black uppercase tracking-widest text-gray-400 group-focus-within:text-indigo-600 transition-colors mb-2 ml-1">
                Adresse Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-300"></i>
                </div>
                <input id="email"
                    class="block w-full pl-11 pr-4 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all text-sm font-semibold text-gray-700 shadow-sm"
                    type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold" />
        </div>

        <div class="group">
            <div class="flex justify-between items-center mb-2 ml-1">
                <label for="password"
                    class="text-[11px] font-black uppercase tracking-widest text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                    Mot de passe
                </label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-extrabold uppercase tracking-tighter text-indigo-500 hover:text-indigo-700 transition-colors"
                        href="{{ route('password.request') }}">
                        Oublié ?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-300"></i>
                </div>
                <input id="password"
                    class="block w-full pl-11 pr-4 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-0 transition-all text-sm font-semibold text-gray-700 shadow-sm"
                    type="password" name="password" required />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold" />
        </div>

        <div class="flex items-center justify-between py-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative">
                    <input id="remember_me" type="checkbox" name="remember" class="sr-only">
                    <div
                        class="w-10 h-5 bg-gray-200 rounded-full shadow-inner transition-colors group-has-[:checked]:bg-indigo-500">
                    </div>
                    <div
                        class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition-transform group-has-[:checked]:translate-x-5">
                    </div>
                </div>
                <span
                    class="ms-3 text-[11px] font-black uppercase tracking-widest text-gray-500 hover:text-gray-700 transition-colors italic">Maintenir
                    la session</span>
            </label>
        </div>

        <div class="pt-6 space-y-4">
            
            <button type="submit"
                class="w-full bg-gray-900 hover:bg-black text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl transition-all active:scale-95">
                Se connecter
            </button>

            <div class="text-center">

                <div class="text-center">
                    <a class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-colors"
                        href="{{ route('register') }}">
                        Pas encore inscrit ? S'inscrire
                    </a>
                </div>

                <a class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-colors"
                    href="{{ route('home') }}">
                    retour à l'accueil
                </a>

            </div>

            {{-- <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest pt-6">
            Système de Gestion immobiliere pour les professionnels de l'immobilier.
        </p> --}}

    </form>

</x-guest-layout>
