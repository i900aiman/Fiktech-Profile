/**
 * Fiktech Enterprise - Ajax Contact Form Validation and Submission Handler
 */

document.addEventListener('DOMContentLoaded', () => {
    initContactForm();
});

function initContactForm() {
    const form = document.getElementById('contact-form');
    const statusMsg = document.getElementById('form-status-msg');
    
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Hide previous messages
        if (statusMsg) {
            statusMsg.style.display = 'none';
            statusMsg.className = 'form-status';
            statusMsg.textContent = '';
        }

        // Get Form Data
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // 1. Client-Side Validation
        const errors = validateClientSide(data);
        if (errors.length > 0) {
            showStatusMessage(errors.join('<br>'), 'error');
            return;
        }

        // Get Submit Button elements to handle disabling/loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        // Disable button & show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        // Retrieve CSRF token from the form
        const csrfToken = data['csrf_token'];

        try {
            const response = await fetch('api/contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showStatusMessage(result.message, 'success');
                form.reset(); // Clear all form inputs
            } else {
                // Handle validation errors from backend
                let errorMsg = result.message || 'An error occurred. Please try again.';
                if (result.errors) {
                    const errorDetails = Object.values(result.errors).join('<br>');
                    errorMsg += `<br><span style="font-size:0.85em;">${errorDetails}</span>`;
                }
                showStatusMessage(errorMsg, 'error');
            }
        } catch (err) {
            console.error('Submission error:', err);
            showStatusMessage('Unable to connect to the server. Please check your internet connection and try again.', 'error');
        } finally {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });

    /**
     * Basic client-side validation rules
     */
    function validateClientSide(data) {
        const errors = [];
        
        if (!data.full_name || data.full_name.trim().length < 3) {
            errors.push('Full Name must be at least 3 characters long.');
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!data.email || !emailRegex.test(data.email.trim())) {
            errors.push('Please enter a valid Email Address.');
        }
        
        const phoneRegex = /^\+?[0-9\s\-()]{7,20}$/;
        if (!data.phone || !phoneRegex.test(data.phone.trim())) {
            errors.push('Please enter a valid Phone Number.');
        }
        
        if (!data.subject || data.subject.trim().length < 3) {
            errors.push('Subject must be at least 3 characters long.');
        }
        
        if (!data.service) {
            errors.push('Please select a Service of interest.');
        }
        
        if (!data.message || data.message.trim().length < 10) {
            errors.push('Message must be at least 10 characters long.');
        }
        
        if (!data.consent) {
            errors.push('You must consent to being contacted by our team.');
        }
        
        return errors;
    }

    /**
     * Helper to show warning/success alerts
     */
    function showStatusMessage(message, type) {
        if (!statusMsg) return;
        
        statusMsg.innerHTML = message;
        statusMsg.classList.add(type);
        statusMsg.style.display = 'block';
        
        // Auto scroll to message
        statusMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}
