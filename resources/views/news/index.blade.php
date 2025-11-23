@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3"><i class="bi bi-newspaper"></i> Actualités</h1>
            <a href="{{ route('news.create') }}" class="btn btn-primary">+ Nouvelle actualité</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Titre</th>
                                <th>Date de publication</th>
                                <th>Auteur</th>
                                <th>Statut</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($news as $item)
                                <tr>
                                    <td><strong>{{ $item->title }}</strong></td>
                                    <td>{{ $item->publish_date->format('d/m/Y') }}</td>
                                    <td>{{ $item->author->name }}</td>
                                    <td>
                                        @if($item->is_published)
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('news.edit', $item) }}"
                                            class="btn btn-sm btn-outline-secondary">Modifier</a>
                                        <form action="{{ route('news.destroy', $item) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Aucune actualité</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $news->links() }}
        </div>
    </div>
@endsection