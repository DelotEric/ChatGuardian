<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <span class="brand-badge me-2">CG</span>
            <strong>ChatGuardian</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('volunteers.*') ? 'active' : '' }}"
                        href="{{ route('volunteers.index') }}">Bénévoles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cats.*') ? 'active' : '' }}"
                        href="{{ route('cats.index') }}">Chats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('foster-families.*') ? 'active' : '' }}"
                        href="{{ route('foster-families.index') }}">Familles d'accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cat-stays.*') ? 'active' : '' }}"
                        href="{{ route('cat-stays.index') }}">Séjours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('donations.*') ? 'active' : '' }}"
                        href="{{ route('donations.index') }}">Dons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('donors.*') ? 'active' : '' }}"
                        href="{{ route('donors.index') }}">Donateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('adopters.*') ? 'active' : '' }}"
                        href="{{ route('adopters.index') }}">Adoptants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('partners.*') ? 'active' : '' }}"
                        href="{{ route('partners.index') }}">Partenaires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medical-cares.*') ? 'active' : '' }}"
                        href="{{ route('medical-cares.index') }}">Soins médicaux</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inventory-items.*', 'inventory-movements.*') ? 'active' : '' }}"
                        href="{{ route('inventory-items.index') }}">Inventaire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('members.*', 'memberships.*') ? 'active' : '' }}"
                        href="{{ route('members.index') }}">Adhérents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('feeding-points.*') ? 'active' : '' }}"
                        href="{{ route('feeding-points.index') }}">Points de nourrissage</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}" href="{{ route('help') }}"><i
                            class="bi bi-question-circle"></i> Aide</a>
                </li>
                @if(Auth::check() && Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">Utilisateurs</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li>
                            <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">
                                {{ __('Adhérents') }}
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')">
                                {{ __('Actualités') }}
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                                {{ __('Agenda') }}
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('help')" :active="request()->routeIs('help')">
                                {{ __('Aide') }}
                            </x-nav-link>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>