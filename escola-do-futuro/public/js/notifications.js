/**
 * Sistema de Notificações - Página de Listagem
 */

document.addEventListener('DOMContentLoaded', function() {
    // Marcar notificação individual como lida
    document.querySelectorAll('.mark-read-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erro ao marcar notificação como lida:', error));
        });
    });

    // Marcar todas as notificações como lidas
    const markAllForm = document.getElementById('mark-all-form');
    if (markAllForm) {
        markAllForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erro ao marcar todas como lidas:', error));
        });
    }
});
