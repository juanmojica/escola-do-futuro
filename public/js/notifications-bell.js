/**
 * Sistema de Notificações - Sininho (Bell)
 * Atualiza automaticamente a cada 30 segundos
 */

// Configuração
const NOTIFICATION_UPDATE_INTERVAL = 30000; // 30 segundos

// URLs - serão definidas no HTML via data attributes
let notificationsUnreadUrl;
let notificationsMarkAsReadUrl;
let notificationsMarkAllAsReadUrl;
let csrfToken;

document.addEventListener('DOMContentLoaded', function() {
    // Obter configurações do elemento
    const bellElement = document.getElementById('notifications-dropdown');
    if (!bellElement) return;

    notificationsUnreadUrl = bellElement.dataset.unreadUrl;
    notificationsMarkAsReadUrl = bellElement.dataset.markAsReadUrl;
    notificationsMarkAllAsReadUrl = bellElement.dataset.markAllAsReadUrl;
    csrfToken = bellElement.dataset.csrfToken;

    // Carregar notificações imediatamente
    loadNotifications();
    
    // Atualizar notificações periodicamente
    setInterval(loadNotifications, NOTIFICATION_UPDATE_INTERVAL);
    
    // Evento: Marcar todas como lidas
    const markAllReadBtn = document.getElementById('mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }
});

/**
 * Carregar notificações não lidas
 */
function loadNotifications() {
    fetch(notificationsUnreadUrl)
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.count);
            renderNotifications(data.notifications);
        })
        .catch(error => console.error('Erro ao carregar notificações:', error));
}

/**
 * Atualizar badge do contador
 */
function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-count');
    if (!badge) return;

    if (count > 0) {
        badge.textContent = count > 9 ? '9+' : count;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

/**
 * Renderizar lista de notificações
 */
function renderNotifications(notifications) {
    const list = document.getElementById('notification-list');
    const noNotifications = document.getElementById('no-notifications');
    
    if (!list) return;

    if (notifications.length === 0) {
        list.innerHTML = '';
        if (noNotifications) {
            noNotifications.style.display = 'block';
            list.appendChild(noNotifications);
        }
        return;
    }
    
    if (noNotifications) {
        noNotifications.style.display = 'none';
    }
    
    list.innerHTML = '';
    
    notifications.forEach(notification => {
        const item = createNotificationElement(notification);
        list.appendChild(item);
    });
}

/**
 * Criar elemento HTML de notificação
 */
function createNotificationElement(notification) {
    const item = document.createElement('div');
    item.className = 'notification-item unread d-flex align-items-start';
    item.onclick = () => markAsReadAndRedirect(notification.id, notification.url);
    
    const iconClass = getNotificationIcon(notification.type);
    const iconBg = getNotificationColor(notification.type);
    
    item.innerHTML = `
        <div class="notification-icon ${iconBg} text-white me-3">
            <i class="${iconClass}"></i>
        </div>
        <div class="notification-content">
            <div class="fw-semibold small">${escapeHtml(notification.message)}</div>
            <div class="notification-time">${escapeHtml(notification.created_at)}</div>
        </div>
    `;
    
    return item;
}

/**
 * Obter ícone baseado no tipo de notificação
 */
function getNotificationIcon(type) {
    const icons = {
        'enrollment': 'fas fa-clipboard-check',
        'default': 'fas fa-info-circle'
    };
    return icons[type] || icons['default'];
}

/**
 * Obter cor baseada no tipo de notificação
 */
function getNotificationColor(type) {
    const colors = {
        'enrollment': 'bg-success',
        'default': 'bg-primary'
    };
    return colors[type] || colors['default'];
}

/**
 * Marcar notificação como lida e redirecionar
 */
function markAsReadAndRedirect(id, url) {
    const markUrl = notificationsMarkAsReadUrl.replace('__ID__', id);
    
    fetch(markUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && url && url !== '#') {
            window.location.href = url;
        } else {
            loadNotifications();
        }
    })
    .catch(error => console.error('Erro ao marcar como lida:', error));
}

/**
 * Marcar todas as notificações como lidas
 */
function markAllAsRead() {
    fetch(notificationsMarkAllAsReadUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    })
    .catch(error => console.error('Erro ao marcar todas como lidas:', error));
}

/**
 * Escape HTML para prevenir XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
