@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 border-end" style="background: white;">
                <div class="p-3">
                    <a href="{{ route('messages.create') }}" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-pencil-square"></i> Nouveau message
                    </a>

                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('messages.index') }}">
                            <i class="bi bi-inbox-fill"></i> Boîte de réception
                        </a>
                        <a class="nav-link active" href="{{ route('messages.sent') }}">
                            <i class="bi bi-send-fill"></i> Messages envoyés
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Messages List -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <h3 class="mb-4"><i class="bi bi-send-fill"></i> Messages envoyés</h3>

                    <div class="list-group">
                        @forelse($messages as $message)
                            <a href="{{ route('messages.show', $message) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="text-muted me-2">À:</span>
                                            <span>{{ $message->recipient->name }}</span>
                                        </div>
                                        <h6 class="mb-1">{{ $message->subject }}</h6>
                                        <p class="mb-1 text-muted small">{{ Str::limit($message->body, 100) }}</p>
                                    </div>
                                    <small class="text-muted ms-3">{{ $message->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-send fs-1 d-block mb-2 opacity-50"></i>
                                <p>Aucun message envoyé</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection