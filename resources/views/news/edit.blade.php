@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-4"><i class="bi bi-newspaper"></i> Modifier l'actualité</h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('news.update', $news) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                            value="{{ old('title', $news->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Contenu *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                            rows="6" required>{{ old('content', $news->content) }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publish_date" class="form-label">Date de publication *</label>
                            <input type="date" class="form-control @error('publish_date') is-invalid @enderror"
                                id="publish_date" name="publish_date"
                                value="{{ old('publish_date', $news->publish_date->format('Y-m-d')) }}" required>
                            @error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                    value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publier
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('news.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection