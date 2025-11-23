@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>

            <div class="d-flex gap-2">
                @if($message->recipient_id === auth()->id())
                    <a href="{{ route('messages.reply', $message) }}" class="btn btn-primary">
                        <i class="bi bi-reply"></i> Répondre
                    </a>
                @endif
                <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Supprimer ce message ?')">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h4 class="mb-3">{{ $message->subject }}</h4>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle me-3"
                        style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.1rem;">
                        {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div><strong>{{ $message->sender->name }}</strong></div>
                        <small class="text-muted">{{ $message->sender->email }}</small>
                    </div>
                    <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
            <div class="card-body">
                <div style="white-space: pre-wrap;">{{ $message->body }}</div>
            </div>
        </div>
    </div>
@endsection