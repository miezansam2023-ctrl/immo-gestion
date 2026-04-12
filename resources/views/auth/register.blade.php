@section('title', 'INSCRIPTION')
<x-guest-layout>

    <div class="mb-10 text-center">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Créer un compte</h2>
        <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mt-2">Rejoignez la plateforme de
            gestion</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="nom"
                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Nom</label>
                <input id="nom" type="text" name="nom" :value="old('nom')"
                    class="block w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700 shadow-sm"
                    required autofocus />
                <x-input-error :messages="$errors->get('nom')" class="mt-2 text-xs" />
            </div>
            <div>
                <label for="prenoms"
                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Prénoms</label>
                <input id="prenoms" type="text" name="prenoms" :value="old('prenoms')"
                    class="block w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700 shadow-sm"
                    required />
                <x-input-error :messages="$errors->get('prenoms')" class="mt-2 text-xs" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label for="telephone"
                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Téléphone</label>
                <input id="telephone" type="tel" name="telephone" :value="old('telephone')"
                    class="block w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700 shadow-sm"
                    required />
                <x-input-error :messages="$errors->get('telephone')" class="mt-2 text-xs" />
            </div>
            <div>
                <label for="email"
                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Adresse
                    Email</label>
                <input id="email" type="email" name="email" :value="old('email')"
                    class="block w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700 shadow-sm"
                    required />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="password"
                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Mot de
                    passe</label>
                <input id="password" type="password" name="password"
                    class="block w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700 shadow-sm"
                    required autocomplete="new-password" />
            </div>
            <div>
                <label for="password_confirmation"
                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Confirmer le
                    mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="block w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700 shadow-sm"
                    required />
            </div>
            <x-input-error :messages="$errors->get('password')" class="col-span-2 mt-1 text-xs" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="col-span-2 mt-1 text-xs" />
        </div>

        <div class="pt-6 space-y-4">

            <button type="submit"
                class="w-full bg-gray-900 hover:bg-black text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl transition-all active:scale-95">
                Créer mon compte
            </button>

            <div class="text-center">

                <div class="text-center">
                    <a class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-colors"
                        href="{{ route('login') }}">
                        Déjà inscrit ? Se connecter
                    </a>
                </div>
            

                <a class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-colors"
                    href="{{ route('home') }}">
                    retour à l'accueil
                </a>
            </div>

        </div>
    </form>


</x-guest-layout>
