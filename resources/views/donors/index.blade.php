@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <p class="text-muted mb-1">Financement</p>
            <h1 class="h4 fw-bold">Donateurs</h1>
        </div>
        <a href="{{ route('donors.create') }}" class="btn btn-primary">Ajouter</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Ville</th>
                            <th>Dons</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donors as $donor)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('donors.show', $donor) }}" class="text-decoration-none text-dark">
                                        {{ $donor->name }}
                                    </a>
                                </td>
                                <td>{{ $donor->email ?? '—' }}</td>
                                <td>{{ $donor->city ?? '—' }}</td>
                                <td><span class="badge bg-soft-primary text-primary">{{ $donor->donations_count }}</span></td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('donors.edit', $donor) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('donors.destroy', $donor) }}" method="POST"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce donateur ?');">
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
                                <td colspan="5" class="text-center text-muted py-4">Aucun donateur enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $donors->links() }}
    </div>
@endsection