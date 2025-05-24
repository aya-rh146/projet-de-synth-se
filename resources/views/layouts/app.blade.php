<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FindStay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
        .footer-custom {
            background: linear-gradient(135deg, #a8c8e0 0%, #7fb3d3 100%);
            color: #2c3e50;
            padding: 40px 0 20px 0;
        }
        
        .footer-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            text-decoration: none;
        }
        
        .footer-description {
            color: #34495e;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 8px;
        }
        
        .footer-links a {
            color: #2c3e50;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #3498db;
            text-decoration: underline;
        }
        
        .footer-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .footer-bottom {
            border-top: 1px solid #bdc3c7;
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            color: #2c3e50;
            font-size: 0.9rem;
        }
        
        .contact-info {
            color: #2c3e50;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Header -->
    <header class="relative bg-cover bg-center h-96 shadow-md" style="background-image: url('{{ asset('storage/photos/backgroundheader.png') }}');">
        <div class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('proprietaire.accueilproprietaire') }}" class="footer-logo d-flex align-items-center justify-content-center justify-content-lg-start">
                    <img src="{{ asset('storage/photos/logo.png') }}" alt="logo" class="h-12">
                </a>            
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
    <footer class="footer-custom">
    <div class="container">
        <!-- Email en haut -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <div class="contact-info">
                    <strong>findstay@gmail.com</strong>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Logo et description à gauche -->
            <div class="col-lg-4 col-md-12 mb-4 text-center text-lg-start">
                <a href="{{ auth()->check() ? (auth()->user()->role_uti === 'proprietaire' ? route('proprietaire.accueilproprietaire') : route('locataire.accueillocataire')) : route('#') }}" class="footer-logo d-flex align-items-center justify-content-center justify-content-lg-start">
                    <img src="{{ asset('storage/photos/logo.png') }}" alt="logo" class="h-12">
                </a>
                <p class="footer-description mt-2">
                    Trouvez votre chez-vous avec FindStay. Facile, rapide, fiable
                </p>
            </div>
            
            <!-- Services au centre -->
            <div class="col-lg-4 col-md-6 mb-4 text-center">
                <h6 class="footer-title">Nos Services</h6>
                <ul class="footer-links">
                    <li><a href="">FAQ</a></li>
                    <li><a href="">On Quelques chiffres</a></li>
                </ul>
            </div>
            
            <!-- Pages à droite -->
            <div class="col-lg-4 col-md-6 mb-4 text-center">
                <h6 class="footer-title">Pages</h6>
                <ul class="footer-links">
                    @if(auth()->check())
                        @if(auth()->user()->role_uti === 'proprietaire')
                            <li><a href="">Nos logements</a></li>
                            <li><a href="">Mes réservations</a></li>
                            <li><a href="{{ route('proprietaire.annoncesproprietaire.index') }}">Mes annonces</a></li>
                        @elseif(auth()->user()->role_uti === 'locataire')
                            <li><a href="">Nos logements</a></li>
                            <li><a href="">Mes réservations</a></li>
                            <li><a href="{{ route('locataire.annonceslocataire.index') }}">Mes annonces</a></li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="footer-bottom">
            <p class="mb-0">© 2025 FindStay. Tous droits réservés.</p>
        </div>
    </div>
</footer>
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-sr1I/6zFZrKqH8+q6rblW0zKZLwMeOWefqfRnRgyCFSOyPlzKnS9+vNGRBr17fV7" crossorigin="anonymous"></script>
</body>
</html>