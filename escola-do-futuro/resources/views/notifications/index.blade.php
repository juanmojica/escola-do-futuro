@extends('layouts.student')

@section('title', 'Notificações')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-bell me-2"></i>Minhas Notificações</h2>
        <p class="text-muted mb-0">Acompanhe todas as suas notificações</p>
    </div>
    @if($notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" id="mark-all-form">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-check-double me-1"></i>Marcar todas como lidas
            </button>
        </form>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($notifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifications as $notification)
                    <div class="list-group-item {{ $notification->isRead() ? '' : 'bg-light border-start border-primary border-3' }}">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="notification-icon-circle {{ $notification->type == 'enrollment' ? 'bg-success bg-opacity-10' : 'bg-primary bg-opacity-10' }}">
                                @if($notification->type == 'enrollment')
                                    <i class="fas fa-clipboard-check text-success"></i>
                                @else
                                    <i class="fas fa-bell text-primary"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 {{ $notification->isRead() ? '' : 'fw-bold' }}">
                                            {{ $notification->message }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if(!$notification->isRead())
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="mark-read-form ms-3">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary ms-3">Lida</span>
                                    @endif
                                </div>
                                
                                @if($notification->data)
                                    <div class="mt-2 p-2 bg-white rounded border">
                                        <small class="text-muted">
                                            @if(isset($notification->data['course_title']))
                                                <strong>Curso:</strong> {{ $notification->data['course_title'] }}<br>
                                            @endif
                                            @if(isset($notification->data['enrollment_date']))
                                                <strong>Data:</strong> {{ $notification->data['enrollment_date'] }}<br>
                                            @endif
                                            @if(isset($notification->data['status']))
                                                <strong>Status:</strong> 
                                                <span class="badge bg-{{ $notification->data['status'] == 'ativa' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($notification->data['status']) }}
                                                </span>
                                            @endif
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card-footer bg-white border-0">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted mb-0">Você não tem notificações</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/notifications.js') }}"></script>
@endpush
