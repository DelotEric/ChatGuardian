@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold">Adhérents</h1>
        <a href="{{ route('members.create') }}" class="btn btn-primary">Nouvel adhérent</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($members->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>N° Adhérent</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Adhésion</th>
                                <th>{{ date('Y') }}</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td><strong>{{ $member->member_number }}</strong></td>
                                    <td>{{ $member->full_name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->join_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($member->hasValidMembership())
                                            <span class="badge bg-success">✓ À jour</span>
                                        @else
                                            <span class="badge bg-warning">⚠ Non payé</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($member->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('members.show', $member) }}"
                                            class="btn btn-sm btn-outline-primary">Voir</a>
                                        <a href="{{ route('members.edit', $member) }}"
                                            class="btn btn-sm btn-outline-secondary">Modifier</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $members->links() }}
            @else
                <p class="text-muted text-center py-4">Aucun adhérent enregistré.</p>
            @endif
        </div>
    </div>
@endsection