@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Nouveau message</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="recipient_id" class="form-label">Destinataire *</label>
                        <select class="form-select @error('recipient_id') is-invalid @enderror" id="recipient_id"
                            name="recipient_id" required {{ isset($message) ? 'readonly' : '' }}>
                            <option value="">Sélectionner un utilisateur</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (isset($message) && $message->sender_id == $user->id) || old('recipient_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('recipient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Sujet *</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                            name="subject" value="{{ old('subject', isset($message) ? 'Re: ' . $message->subject : '') }}"
                            required>
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Message *</label>
                        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="10"
                            required>{{ old('body') }}</textarea>
                        @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Envoyer
                        </button>
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection