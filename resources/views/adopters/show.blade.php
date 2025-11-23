@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('adopters.index') }}"
                    class="text-decoration-none text-muted">Adoptants</a> / Détails</p>
            <h1 class="h3 fw-bold">{{ $adopter->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('adopters.edit', $adopter) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('adopters.destroy', $adopter) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet adoptant ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Informations</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <small class="text-muted d-block">Email</small>
                        <a href="mailto:{{ $adopter->email }}">{{ $adopter->email }}</a>
                    </li>
                    @if($adopter->phone)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Téléphone</small>
                            <a href="tel:{{ $adopter->phone }}">{{ $adopter->phone }}</a>
                        </li>
                    @endif
                    @if($adopter->address)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Adresse</small>
                            {{ $adopter->address }}
                        </li>
                    @endif
                    @if($adopter->city)
                        <li class="list-group-item">
                            <small class="text-muted d-block">Ville</small>
                            {{ $adopter->city }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            @if($adopter->notes)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Notes</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $adopter->notes }}</p>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Chats adoptés</span>
                    <span class="badge bg-primary">{{ $adopter->cats->count() }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Sexe</th>
                                <th>Date d'adoption</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adopter->cats as $cat)
                                <tr>
                                    <td>
                                        <a href="{{ route('cats.show', $cat) }}" class="text-decoration-none text-dark fw-semibold">
                                            {{ $cat->name }}
                                        </a>
                                    </td>
                                    <td>{{ $cat->sex === 'male' ? 'Mâle' : ($cat->sex === 'female' ? 'Femelle' : 'Inconnu') }}</td>
                                    <td>{{ $cat->adopted_at ? $cat->adopted_at->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('cats.show', $cat) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Aucun chat adopté.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
