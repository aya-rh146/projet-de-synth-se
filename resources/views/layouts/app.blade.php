<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FindStay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <style>
        .navbar-custom {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 10px 20px;
        }
        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        .navbar-custom .nav-link:hover {
            color: #4CAF50 !important;
            transform: translateY(-2px);
        }
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Header -->
    <header class="relative bg-cover bg-center h-96 shadow-md" style="background-image: url('{{ asset('storage/photos/backgroundheader.png') }}');">
        <div class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('storage/photos/logo.png') }}" alt="logo" class="h-12">
            </div>
            <nav class="navbar navbar-expand-lg navbar-custom">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            @auth
                                @if (Auth::check())
                                    @if (Auth::user()->role_uti === 'proprietaire')
                                        <li class="nav-item"><a class="nav-link" href="{{ route('proprietaire.accueilproprietaire') }}">Accueil</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#">Nos logements</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#">Mes réservations</a></li>
                                        <li class="nav-item"><a class="nav-link" href="{{ route('proprietaire.annoncesproprietaire.index') }}">Mes annonces</a></li>
                                    @elseif (Auth::user()->role_uti === 'locataire' || Auth::user()->role_uti === 'colocataire')
                                        <li class="nav-item"><a class="nav-link" href="{{ route('locataire.accueillocataire') }}">Accueil</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#">Nos logements</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#">Mes réservations</a></li>
                                        <li class="nav-item"><a class="nav-link" href="{{ route('locataire.annonceslocataire.index') }}">Mes annonces</a></li>
                                    @endif
                                @else
                                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('signup') }}">Inscription</a></li>
                                @endif
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('signup') }}">Inscription</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
    </div>
    </header>

    <!-- Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 py-6 mt-10">
        <div class="container mx-auto text-center text-sm text-gray-600">
            <p>© 2025 FindStay. Tous droits réservés.</p>
        </div>
    </footer>
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-sr1I/6zFZrKqH8+q6rblW0zKZLwMeOWefqfRnRgyCFSOyPlzKnS9+vNGRBr17fV7" crossorigin="anonymous"></script>
</body>
</html>