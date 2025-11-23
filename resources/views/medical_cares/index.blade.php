@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold">Soins médicaux</h1>
            <p class="text-muted mb-0">Suivi des soins vétérinaires et traitements</p>
        </div>
        <a href="{{ route('medical-cares.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouveau soin
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="cat_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les chats</option>
                        @foreach($cats as $cat)
                            <option value="{{ $cat->id }}" {{ request('cat_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Prévu</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Effectué</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les types</option>
                        <option value="vaccination" {{ request('type') == 'vaccination' ? 'selected' : '' }}>Vaccination
                        </option>
                        <option value="deworming" {{ request('type') == 'deworming' ? 'selected' : '' }}>Vermifuge</option>
                        <option value="vet_visit" {{ request('type') == 'vet_visit' ? 'selected' : '' }}>Visite vétérinaire
                        </option>
                        <option value="sterilization" {{ request('type') == 'sterilization' ? 'selected' : '' }}>Stérilisation
                        </option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Chat</th>
                        <th>Type</th>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Prochaine échéance</th>
                        <th>Statut</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicalCares as $care)
                        @php
                            $isOverdue = $care->status === 'scheduled' && $care->care_date->isPast();
                            $rowClass = $isOverdue ? 'table-danger' : '';
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>
                                <a href="{{ route('cats.show', $care->cat) }}" class="text-decoration-none">
                                    {{ $care->cat->name }}
                                </a>
                            </td>
                            <td>
                                @php
                                    $typeLabels = [
                                        'vaccination' => 'Vaccination',
                                        'deworming' => 'Vermifuge',
                                        'vet_visit' => 'Visite vétérinaire',
                                        'sterilization' => 'Stérilisation',
                                        'other' => 'Autre',
                                    ];
                                @endphp
                                {{ $typeLabels[$care->type] }}
                            </td>
                            <td>
                                <a href="{{ route('medical-cares.show', $care) }}"
                                    class="text-decoration-none text-dark fw-semibold">
                                    {{ $care->title }}
                                </a>
                            </td>
                            <td>{{ $care->care_date->format('d/m/Y') }}</td>
                            <td>{{ $care->next_due_date?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @if($care->status === 'completed')
                                    <span class="badge bg-success">Effectué</span>
                                @elseif($care->status === 'cancelled')
                                    <span class="badge bg-secondary">Annulé</span>
                                @elseif($isOverdue)
                                    <span class="badge bg-danger">En retard</span>
                                @else
                                    <span class="badge bg-primary">Prévu</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('medical-cares.show', $care) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('medical-cares.edit', $care) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('medical-cares.destroy', $care) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce soin ?');">
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
                            <td colspan="7" class="text-center text-muted py-4">Aucun soin enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $medicalCares->links() }}
    </div>
@endsection