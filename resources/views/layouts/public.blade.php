<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ChatGuardian') }} - L'École du Chat</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .cat-card {
            transition: transform 0.3s ease;
        }

        .cat-card:hover {
            transform: translateY(-5px);
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 3rem 0;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('public.home') }}">
                <i class="bi bi-heart-fill"></i> ChatGuardian
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.home') ? 'active fw-bold' : '' }}"
                            href="{{ route('public.home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.cats*') ? 'active fw-bold' : '' }}"
                            href="{{ route('public.cats') }}">Nos Chats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary rounded-pill px-4" href="{{ route('public.cats') }}">Adopter</a>
                    </li>
                    @auth
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-secondary rounded-pill" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light text-dark" href="{{ route('login') }}">Connexion</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="footer mt-auto" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">ChatGuardian</h5>
                    <p class="text-muted small">L'École du Chat de la Val d'Orge est une association de protection
                        animale dédiée au sauvetage et à l'adoption de chats.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Contact</h5>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Val d'Orge, France</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> contact@chatguardian.fr</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> 01 23 45 67 89</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Liens utiles</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('public.cats') }}"
                                class="text-muted text-decoration-none">Nos chats à l'adoption</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Devenir bénévole</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Faire un don</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center small text-muted">
                &copy; {{ date('Y') }} ChatGuardian. Tous droits réservés.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>