<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ChatGuardian - {{ $title ?? 'Gestion' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet (if needed) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-color: #3a7e8c;
            --primary-light: #5ca0ae;
            --primary-dark: #2a6e7c;
            --secondary-color: #f0803c;
            --secondary-light: #f5a06c;
            --bg-color: #f7f9fc;
            --sidebar-width: 260px;
            --header-height: 65px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-color);
            overflow-x: hidden;
        }

        /* Header */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color);
            text-decoration: none;
        }

        .logo i {
            font-size: 1.75rem;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-color);
            cursor: pointer;
            margin-right: 1rem;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            overflow-y: auto;
            z-index: 1020;
            transition: transform 0.3s ease;
        }

        .sidebar-user {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            list-style: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(58, 126, 140, 0.08);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .nav-link.active {
            background: rgba(58, 126, 140, 0.12);
            color: var(--primary-color);
            font-weight: 600;
            border-left-color: var(--primary-color);
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-section-title {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 0.5px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* User Menu */
        .user-menu {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .notification-btn:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 3px;
            right: 3px;
            background: var(--secondary-color);
            color: white;
            border-radius: 10px;
            padding: 0.15rem 0.4rem;
            font-size: 0.65rem;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: var(--header-height);
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1015;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <button class="mobile-menu-btn" id="mobile-menu-btn">
            <i class="bi bi-list"></i>
        </button>

        <a href="{{ route('dashboard') }}" class="logo">
            <i class="bi bi-heart-fill"></i>
            <span>ChatGuardian</span>
        </a>

        <div class="user-menu">
            <div class="dropdown">
                <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @if(isset($globalNotifications) && $globalNotifications['count'] > 0)
                        <span class="notification-badge">{{ $globalNotifications['count'] }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-0 shadow border-0"
                    style="width: 320px; max-height: 400px; overflow-y: auto;">
                    <li class="p-3 border-bottom bg-light">
                        <h6 class="mb-0 fw-bold">Notifications</h6>
                    </li>

                    @if(isset($globalNotifications) && $globalNotifications['count'] > 0)
                        {{-- Stock Faible --}}
                        @foreach($globalNotifications['lowStock'] as $item)
                            <li>
                                <a class="dropdown-item p-3 border-bottom d-flex align-items-start"
                                    href="{{ route('inventory-items.index') }}">
                                    <div class="bg-soft-danger rounded p-2 me-3">
                                        <i class="bi bi-box-seam text-danger"></i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-bold text-danger">Stock faible</p>
                                        <p class="mb-0 small text-muted">{{ $item->name }} ({{ $item->quantity }}
                                            {{ $item->unit }})
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @endforeach

                        {{-- Soins Médicaux --}}
                        @foreach($globalNotifications['medical'] as $care)
                            <li>
                                <a class="dropdown-item p-3 border-bottom d-flex align-items-start"
                                    href="{{ route('medical-cares.show', $care) }}">
                                    <div class="bg-soft-warning rounded p-2 me-3">
                                        <i class="bi bi-heart-pulse text-warning"></i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-bold text-warning">Soin à prévoir</p>
                                        <p class="mb-0 small text-muted">{{ $care->type_label }} pour {{ $care->cat->name }}</p>
                                        <small class="text-muted">{{ $care->care_date->format('d/m/Y') }}</small>
                                    </div>
                                </a>
                            </li>
                        @endforeach

                        {{-- Événements --}}
                        @foreach($globalNotifications['events'] as $event)
                            <li>
                                <a class="dropdown-item p-3 border-bottom d-flex align-items-start"
                                    href="{{ route('events.show', $event) }}">
                                    <div class="bg-soft-primary rounded p-2 me-3">
                                        <i class="bi bi-calendar-event text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-bold text-primary">Aujourd'hui</p>
                                        <p class="mb-0 small text-muted">{{ $event->title }}</p>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</small>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li class="p-4 text-center text-muted">
                            <i class="bi bi-bell-slash fs-1 d-block mb-2 opacity-50"></i>
                            <p class="mb-0">Aucune notification</p>
                        </li>
                    @endif
                </ul>
            </div>

            <a href="{{ route('messages.index') }}" class="notification-btn" title="Messages">
                <i class="bi bi-envelope-fill"></i>
                @php
                    $unreadMessages = \App\Models\Message::inbox(auth()->id())->unread()->count();
                @endphp
                @if($unreadMessages > 0)
                    <span class="notification-badge">{{ $unreadMessages }}</span>
                @endif
            </a>

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i>
                            Profil</a></li>
                    @if(Auth::user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi bi-person-gear"></i>
                                Utilisateurs</a></li>
                    @endif
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i>
                                Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="fw-600">{{ Auth::user()->name }}</div>
                <small class="text-muted">{{ Auth::user()->email }}</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <div class="nav-section-title">Gestion des chats</div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cats.*') ? 'active' : '' }}"
                        href="{{ route('cats.index') }}">
                        <i class="bi bi-heart"></i>
                        <span>Chats</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medical-cares.*') ? 'active' : '' }}"
                        href="{{ route('medical-cares.index') }}">
                        <i class="bi bi-clipboard2-pulse"></i>
                        <span>Soins médicaux</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('feeding-points.*') ? 'active' : '' }}"
                        href="{{ route('feeding-points.index') }}">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Points de nourrissage</span>
                    </a>
                </li>

                <div class="nav-section-title">Bénévoles & Familles</div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('volunteers.*') ? 'active' : '' }}"
                        href="{{ route('volunteers.index') }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Bénévoles</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('foster-families.*') ? 'active' : '' }}"
                        href="{{ route('foster-families.index') }}">
                        <i class="bi bi-house-heart-fill"></i>
                        <span>Familles d'accueil</span>
                    </a>
                </li>

                <div class="nav-section-title">Gestion</div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('members.*') || request()->routeIs('memberships.*') ? 'active' : '' }}"
                        href="{{ route('members.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Adhérents</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inventory-items.*') || request()->routeIs('inventory-movements.*') ? 'active' : '' }}"
                        href="{{ route('inventory-items.index') }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Inventaire</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('donations.*') ? 'active' : '' }}"
                        href="{{ route('donations.index') }}">
                        <i class="bi bi-gift-fill"></i>
                        <span>Dons</span>
                    </a>
                </li>

                <div class="nav-section-title">Communication</div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}"
                        href="{{ route('news.index') }}">
                        <i class="bi bi-newspaper"></i>
                        <span>Actualités</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}"
                        href="{{ route('events.index') }}">
                        <i class="bi bi-calendar-event"></i>
                        <span>Agenda</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}"
                        href="{{ route('messages.index') }}">
                        <i class="bi bi-envelope-fill"></i>
                        <span>Messagerie</span>
                        @php
                            $unreadMessages = \App\Models\Message::inbox(auth()->id())->unread()->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge bg-danger ms-auto">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                </li>

                @if(Auth::user()->isAdmin())
                    <div class="nav-section-title">Administration</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="bi bi-person-gear"></i>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                @endif

                <div class="nav-section-title">Support</div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}" href="{{ route('help') }}">
                        <i class="bi bi-question-circle"></i>
                        <span>Aide</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Close sidebar on mobile when clicking a link
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>