<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGuardian - Prototype</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <span class="brand-badge me-2">CG</span>
                <strong>ChatGuardian</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @auth
                        @php $role = auth()->user()->role; @endphp
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Accueil</a></li>
                        @if($role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('volunteers.index') }}">Bénévoles</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('cats.index') }}">Chats</a></li>
                        @if(in_array($role, ['admin', 'benevole']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('foster-families.index') }}">Familles d'accueil</a></li>
                        @endif
                        @if(in_array($role, ['admin', 'benevole']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('feeding-points.index') }}">Points de nourrissage</a></li>
                        @endif
                        @if(in_array($role, ['admin', 'benevole']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('stocks.index') }}">Stocks</a></li>
                        @endif
                        @if($role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('donors.index') }}">Donateurs</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('donations.index') }}">Dons</a></li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="avatar-placeholder me-2">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                <span class="small">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2 text-muted small">Rôle : {{ auth()->user()->role }}</li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-outline-primary ms-lg-3" href="{{ route('login') }}">Connexion</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </header>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="border-top mt-5 py-4 text-center text-muted small">
        <p class="mb-1">ChatGuardian — prototype associatif</p>
        <p class="mb-0">Pensé pour faciliter la gestion des chats libres et des bénévoles.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>
</html>
