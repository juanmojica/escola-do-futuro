let confirmModal;
let formToSubmit = null;

document.addEventListener('DOMContentLoaded', function() {

    const modalElement = document.getElementById('confirmDeleteModal');

    if (modalElement) {
        confirmModal = new bootstrap.Modal(modalElement);
        
        const confirmBtn = document.getElementById('confirmDeleteButton');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
                confirmModal.hide();
            });
        }

        modalElement.addEventListener('hidden.bs.modal', function() {
            formToSubmit = null;
        });
    }

    initAutoDismissAlerts();
});

function initAutoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert.auto-dismiss');
    
    alerts.forEach(function(alert) {
        
        const progressBar = document.createElement('div');
        progressBar.className = 'alert-progress';
        alert.appendChild(progressBar);
        
        let isPaused = false;
        let timeoutId;
        
        const closeAlert = function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        };
        
        const startTimer = function() {
            progressBar.style.animationPlayState = 'running';
            timeoutId = setTimeout(closeAlert, 3000);
        };
        
        alert.addEventListener('mouseenter', function() {
            if (!isPaused) {
                isPaused = true;
                clearTimeout(timeoutId);
                progressBar.style.animationPlayState = 'paused';
            }
        });
        
        alert.addEventListener('mouseleave', function() {
            if (isPaused) {

                isPaused = false;

                progressBar.style.animation = 'none';

                setTimeout(function() {
                    progressBar.style.animation = 'alert-progress 3s linear forwards';
                    startTimer();
                }, 10);
            }
        });
        
        startTimer();
    });
}

function confirmDelete(formId, message) {

    message = message || 'Esta ação não pode ser desfeita.';

    formToSubmit = document.getElementById(formId);

    const messageElement = document.getElementById('confirmDeleteMessage');

    if (messageElement) {
        messageElement.textContent = message;
    }

    if (confirmModal) {
        confirmModal.show();
    }
}
