@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <div class="mb-4">
        <h1 class="display-1 fw-bold text-danger">403</h1>
        <h2 class="h4 mb-3">Accès refusé</h2>
        <p class="text-muted mb-4">
            Vous n'avez pas les permissions nécessaires pour accéder à cette page.
        </p>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">Retour au dashboard</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Retour en arrière</a>
        </div>
    </div>
</div>
@endsection

