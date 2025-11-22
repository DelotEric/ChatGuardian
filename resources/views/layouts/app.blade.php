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
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('volunteers.index') }}">Bénévoles</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cats.index') }}">Chats</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('foster-families.index') }}">Familles d'accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('donations.index') }}">Dons</a></li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-lg-3" href="/login">Connexion</a>
                    </li>
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
</body>
</html>
