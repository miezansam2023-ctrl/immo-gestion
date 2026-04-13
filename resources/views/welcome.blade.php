<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoGestion - Simplifiez votre gestion locative</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal">

    <nav class="bg-white shadow-md fixed w-full z-10 top-0" style="Z-index:20">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <span class="text-xl font-black uppercase tracking-tighter text-indigo-600">Immo<span
                        class="text-slate-900">Gestion </span></span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-600 hover:text-indigo-600 font-medium">Fonctionnalités</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-indigo-700 transition shadow-lg">Mon
                            Tableau de Bord</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Connexion</a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-indigo-700 transition shadow-lg">S'inscrire</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <header class="relative bg-indigo-900 pt-32 pb-20 md:pt-48 md:pb-32 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80"
                alt="Background Immo" class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-12 md:mb-0">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                        Gérez votre patrimoine immobilier en un clic.
                    </h1>
                    <p class="text-xl text-indigo-100 mb-8 max-w-lg">
                        De Cocody au Plateau, suivez vos locataires, encaissez vos loyers et gérez les incidents sur une
                        plateforme unique et sécurisée.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}"
                            class="bg-white text-indigo-700 px-8 py-4 rounded-lg font-bold text-center hover:bg-indigo-50 transition shadow-xl">Commencer
                            maintenant</a>
                        <a href="#features"
                            class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-center hover:bg-white hover:text-indigo-700 transition">En
                            savoir plus</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Pourquoi choisir ImmoGestion ?</h2>
                <div class="h-1 w-24 bg-indigo-600 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition duration-300">
                    <div
                        class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-6 text-2xl">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Parc Immobilier</h3>
                    <p class="text-gray-600">Listez vos villas, appartements et bureaux. Suivez le statut (disponible,
                        occupé) en temps réel.</p>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition duration-300">
                    <div
                        class="w-14 h-14 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6 text-2xl">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Loyers & Quittances</h3>
                    <p class="text-gray-600">Encaissez les loyers et générez automatiquement des quittances
                        professionnelles au format PDF.</p>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition duration-300">
                    <div
                        class="w-14 h-14 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mb-6 text-2xl">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Maintenance</h3>
                    <p class="text-gray-600">Suivez les pannes et les interventions techniques. Ne laissez plus un
                        incident traîner.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-800 py-12">
        <div class="container mx-auto px-6 text-center text-white">
            <p class="mb-4">&copy; 2026 ImmoGestion. Tous droits réservés.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="hover:text-indigo-400"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-indigo-400"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-indigo-400"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </footer>

</body>

</html>
