/**
 * Fiktech Enterprise - Admin Dashboard Interactive Script
 * Handles: Asynchronous status toggling and table filters
 */

document.addEventListener('DOMContentLoaded', () => {
    initStatusToggles();
});

/**
 * Handle marking submissions as New/Read asynchronously
 */
function initStatusToggles() {
    const toggleButtons = document.querySelectorAll('.toggle-status-btn');
    
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            const contactId = btn.getAttribute('data-id');
            const currentStatus = btn.getAttribute('data-current-status');
            const nextStatus = currentStatus === 'new' ? 'read' : 'new';
            const csrfToken = btn.getAttribute('data-csrf');
            
            // Disable button during call
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                const response = await fetch('api_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': csrfToken
                    },
                    body: new URLSearchParams({
                        'id': contactId,
                        'status': nextStatus,
                        'csrf_token': csrfToken
                    })
                });
                
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    // Update badge in the parent row or container if it exists
                    const row = btn.closest('tr');
                    const badge = row ? row.querySelector('.badge') : document.querySelector('.badge');
                    
                    if (badge) {
                        if (nextStatus === 'read') {
                            badge.className = 'badge badge-read';
                            badge.textContent = 'Read';
                        } else {
                            badge.className = 'badge badge-new';
                            badge.textContent = 'New';
                        }
                    }
                    
                    // Update button content and state attributes
                    btn.setAttribute('data-current-status', nextStatus);
                    if (nextStatus === 'read') {
                        btn.className = 'action-btn toggle-status-btn';
                        btn.innerHTML = '<i class="fas fa-envelope"></i> Mark New';
                    } else {
                        btn.className = 'action-btn action-btn-status toggle-status-btn';
                        btn.innerHTML = '<i class="fas fa-envelope-open"></i> Mark Read';
                    }
                } else {
                    alert(result.message || 'Failed to update status.');
                    btn.innerHTML = originalHtml;
                }
            } catch (err) {
                console.error('Error toggling status:', err);
                alert('Connection error. Failed to update status.');
                btn.innerHTML = originalHtml;
            } finally {
                btn.disabled = false;
            }
        });
    });
}
