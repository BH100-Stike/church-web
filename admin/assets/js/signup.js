document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const form = document.querySelector('form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    const passwordRequirements = document.getElementById('password-requirements');

    // Validation Patterns
    const patterns = {
        username: /^[a-zA-Z0-9_-]{3,20}$/,
        name: /^[a-zA-Z\s]{2,50}$/,
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    };

    // Show password requirements on focus or when error exists
    passwordInput?.addEventListener('focus', function() {
        passwordRequirements.style.display = 'block';
    });

    // Hide requirements when leaving field if valid
    passwordInput?.addEventListener('blur', function() {
        if (!this.classList.contains('is-invalid') && this.value.length === 0) {
            passwordRequirements.style.display = 'none';
        }
    });

    // Real-time password validation
    passwordInput?.addEventListener('input', function() {
        const password = this.value;
        
        // Validate length
        const lengthValid = password.length >= 8;
        document.getElementById('req-length').className = lengthValid ? 'text-success' : 'text-danger';
        document.getElementById('req-length').innerHTML = lengthValid ? 
            '<i class="bi bi-check-circle-fill"></i> 8+ characters' : 
            '<i class="bi bi-x-circle-fill"></i> 8+ characters';
        
        // Validate uppercase
        const upperValid = /[A-Z]/.test(password);
        document.getElementById('req-upper').className = upperValid ? 'text-success' : 'text-danger';
        document.getElementById('req-upper').innerHTML = upperValid ? 
            '<i class="bi bi-check-circle-fill"></i> 1 uppercase letter' : 
            '<i class="bi bi-x-circle-fill"></i> 1 uppercase letter';
        
        // Validate lowercase
        const lowerValid = /[a-z]/.test(password);
        document.getElementById('req-lower').className = lowerValid ? 'text-success' : 'text-danger';
        document.getElementById('req-lower').innerHTML = lowerValid ? 
            '<i class="bi bi-check-circle-fill"></i> 1 lowercase letter' : 
            '<i class="bi bi-x-circle-fill"></i> 1 lowercase letter';
        
        // Validate number
        const numberValid = /\d/.test(password);
        document.getElementById('req-number').className = numberValid ? 'text-success' : 'text-danger';
        document.getElementById('req-number').innerHTML = numberValid ? 
            '<i class="bi bi-check-circle-fill"></i> 1 number' : 
            '<i class="bi bi-x-circle-fill"></i> 1 number';
        
        // Validate password match if confirm field has value
        if (confirmPasswordInput.value) {
            validatePasswordMatch();
        }
    });

    // Confirm password validation
    confirmPasswordInput?.addEventListener('input', validatePasswordMatch);

    function validatePasswordMatch() {
        const passwordsMatch = passwordInput.value === confirmPasswordInput.value;
        confirmPasswordInput.classList.toggle('is-invalid', !passwordsMatch && confirmPasswordInput.value !== '');
        confirmPasswordInput.classList.toggle('is-valid', passwordsMatch && confirmPasswordInput.value !== '');
    }

    // Toggle Password Visibility
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    });

    // Form Submission Validation
    form?.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate all fields
        isValid &= validateField(document.getElementById('name'), patterns.name);
        isValid &= validateField(document.getElementById('username'), patterns.username);
        isValid &= validateField(document.getElementById('email'), patterns.email);
        
        // Special password validation
        const password = passwordInput.value;
        const passwordValid = password.length >= 8 && 
                            /[A-Z]/.test(password) && 
                            /[a-z]/.test(password) && 
                            /\d/.test(password);
        
        if (!passwordValid) {
            passwordInput.classList.add('is-invalid');
            passwordRequirements.style.display = 'block';
            isValid = false;
        }
        
        // Validate password match
        isValid &= passwordInput.value === confirmPasswordInput.value;
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first invalid field
            document.querySelector('.is-invalid')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });

    // Generic field validation
    function validateField(field, pattern) {
        if (!field) return true;
        
        const isValid = pattern.test(field.value);
        field.classList.toggle('is-invalid', !isValid);
        field.classList.toggle('is-valid', isValid);
        return isValid;
    }
});