@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1">Gestion</p>
            <h1 class="h4 fw-bold">Séjours en famille d'accueil</h1>
        </div>
        <a href="{{ route('cat-stays.create') }}" class="btn btn-primary">Nouveau séjour</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Chat</th>
                            <th>Famille d'accueil</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stays as $stay)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('cats.show', $stay->cat) }}" class="text-decoration-none text-dark">
                                        {{ $stay->cat->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('foster-families.show', $stay->fosterFamily) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $stay->fosterFamily->name }}
                                    </a>
                                </td>
                                <td>
                                    Du {{ $stay->started_at->format('d/m/Y') }}
                                    @if($stay->ended_at)
                                        au {{ $stay->ended_at->format('d/m/Y') }}
                                    @else
                                        (en cours)
                                    @endif
                                </td>
                                <td>
                                    @if(!$stay->ended_at)
                                        <span class="badge bg-soft-success text-success">En cours</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-muted">Terminé</span>
                                        @if($stay->outcome)
                                            <small class="d-block text-muted">{{ $stay->outcome }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('cat-stays.show', $stay) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('cat-stays.edit', $stay) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('cat-stays.destroy', $stay) }}" method="POST"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce séjour ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun séjour enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $stays->links() }}
    </div>
@endsection