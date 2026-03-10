<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Inscription - EventsAccess</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #1f2937 inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">

    <div class="fixed inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2574&auto=format&fit=crop" class="w-full h-full object-cover opacity-40" alt="Event Background">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-gray-900/40"></div>
    </div>

    <div class="fixed top-[-10%] left-[-10%] w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-50 animate-pulse z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-50 animate-pulse z-0"></div>

    <div class="relative z-10 w-full max-w-md">
        
        <div class="bg-gray-800/40 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8">
            
            <div class="text-center mb-8">
              
                <p class="mt-2 text-gray-400 text-sm font-light">Créez votre compte en quelques secondes</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 backdrop-blur-sm">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-red-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <ul class="text-sm text-red-200 list-disc pl-4 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-300 uppercase tracking-wider ml-1">Nom complet</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all duration-200 sm:text-sm"
                            placeholder="John Doe">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-300 uppercase tracking-wider ml-1">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all duration-200 sm:text-sm"
                            placeholder="exemple@email.com">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-300 uppercase tracking-wider ml-1">Mot de passe</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" required
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all duration-200 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-300 uppercase tracking-wider ml-1">Confirmer</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="password" name="password_confirmation" required
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-900/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all duration-200 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-purple-500/20 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-purple-500 transition-all duration-200 transform hover:scale-[1.02] mt-4">
                    S'INSCRIRE
                </button>

            </form>

            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <p class="text-gray-400 text-sm">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" class="font-semibold text-white hover:text-purple-400 transition-colors ml-1">
                        Se connecter
                    </a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>