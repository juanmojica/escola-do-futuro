<!-- Componente de Notificações (Sininho) -->
<li class="nav-item dropdown" 
    id="notifications-dropdown"
    data-unread-url="{{ route('notifications.unread') }}"
    data-mark-as-read-url="{{ route('notifications.markAsRead', '__ID__') }}"
    data-mark-all-as-read-url="{{ route('notifications.markAllAsRead') }}"
    data-csrf-token="{{ csrf_token() }}">
    <a id="notificationsDropdown" class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" id="notification-count" style="display: none;">
            0
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationsDropdown" style="min-width: 320px; max-width: 400px;">
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Notificações</span>
            <a href="#" id="mark-all-read" class="text-primary text-decoration-none small">Marcar todas como lida</a>
        </div>
        <div class="dropdown-divider"></div>
        
        <div id="notification-list" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center text-muted py-4" id="no-notifications">
                <i class="fas fa-bell-slash fs-2 mb-2 d-block"></i>
                <small>Nenhuma notificação</small>
            </div>
        </div>
        
        <div class="dropdown-divider"></div>
        <div class="dropdown-item-text text-center">                
            <a href="{{ route('notifications.index') }}" class="text-decoration-none small">Ver todas as notificações</a>
        </div>
    </div>
</li>

<script src="{{ asset('js/notifications-bell.js') }}" defer></script>
