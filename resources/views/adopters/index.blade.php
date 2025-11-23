@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold">Adoptants</h1>
            <p class="text-muted mb-0">Liste des personnes ayant adopté un chat.</p>
        </div>
        <a href="{{ route('adopters.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouvel adoptant
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Ville</th>
                        <th class="text-center">Chats adoptés</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adopters as $adopter)
                        <tr>
                            <td>
                                <a href="{{ route('adopters.show', $adopter) }}"
                                    class="text-decoration-none text-dark fw-semibold">
                                    {{ $adopter->name }}
                                </a>
                            </td>
                            <td>{{ $adopter->email }}</td>
                            <td>{{ $adopter->city ?: '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $adopter->cats_count }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('adopters.show', $adopter) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('adopters.edit', $adopter) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('adopters.destroy', $adopter) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet adoptant ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucun adoptant enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $adopters->links() }}
    </div>
@endsection