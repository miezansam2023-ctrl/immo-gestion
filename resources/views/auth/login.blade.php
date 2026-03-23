@section('title', 'CONNEXION')
<x-guest-layout>

    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Connexion</h2>
        <p class="text-gray-400 text-sm font-medium mt-2">Accédez à votre console de gestion immobilière</p>
    </div>

    @if (session('success'))
        <div id="success-alert"
            class="relative mb-8 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
            <div class="flex-shrink-0 bg-emerald-500 rounded-full p-1">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-xs font-black text-emerald-900 uppercase tracking-widest">Opération réussie</h3>
                <p class="text-[11px] font-bold text-emerald-700 mt-0.5">{{ session('success') }}</p>
            </div>
            <button onclick="document.getElementById('success-alert').remove()" class="ml-auto p-2 group">
                <svg class="h-5 w-5 text-emerald-300 hover:text-emerald-600 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
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

        <div class="pt-4">
            <button type="submit"
                class="w-full bg-gray-900 hover:bg-black text-white py-5 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-xl hover:shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95">
                Se connecter au Dashboard
            </button>
        </div>

        <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest pt-6">
            Système de Gestion immobiliere pour les professionnels de l'immobilier.
        </p>
    </form>

</x-guest-layout>
