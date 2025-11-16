document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const form = document.querySelector('form');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.querySelector('.toggle-password');
    const errorAlert = document.querySelector('.alert-danger');
    let requirementsContainer = null;
    let passwordErrorShown = false;

    // Validation Patterns
    const patterns = {
        username: /^[a-zA-Z0-9_-]{3,20}$/,
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        password: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/
    };

    // If there's an error (from server), show password requirements if password is invalid
    if (errorAlert && passwordInput.value.length > 0 && !patterns.password.test(passwordInput.value)) {
        showPasswordRequirements();
        passwordErrorShown = true;
        updatePasswordRequirements();
    }

    // Validate Field Function
    function validateField(field, pattern) {
        const isValid = pattern.test(field.value);
        field.classList.toggle('is-invalid', !isValid);
        field.classList.toggle('is-valid', isValid);
        
        // Special handling for password field
        if (field === passwordInput) {
            if (!isValid && field.value.length > 0) {
                showPasswordRequirements();
                passwordErrorShown = true;
            } else if (isValid && passwordErrorShown) {
                hidePasswordRequirements();
                passwordErrorShown = false;
            }
            updatePasswordRequirements();
        }
        
        return isValid;
    }

    // Create password requirements element
    function createRequirementsContainer() {
        if (requirementsContainer) return requirementsContainer;
        
        requirementsContainer = document.createElement('div');
        requirementsContainer.className = 'password-requirements';
        requirementsContainer.innerHTML = `
            <div class="requirements-card">
                <p class="requirements-title">Password must contain:</p>
                <ul class="requirements-list">
                    <li class="requirement-item length-requirement">
                        <span class="requirement-icon">•</span>
                        <span class="requirement-text">8+ characters</span>
                    </li>
                    <li class="requirement-item uppercase-requirement">
                        <span class="requirement-icon">•</span>
                        <span class="requirement-text">1 uppercase letter</span>
                    </li>
                    <li class="requirement-item lowercase-requirement">
                        <span class="requirement-icon">•</span>
                        <span class="requirement-text">1 lowercase letter</span>
                    </li>
                    <li class="requirement-item number-requirement">
                        <span class="requirement-icon">•</span>
                        <span class="requirement-text">1 number</span>
                    </li>
                </ul>
            </div>
        `;
        
        // Insert after the password field's parent
        passwordInput.closest('.mb-3').appendChild(requirementsContainer);
        return requirementsContainer;
    }

    function showPasswordRequirements() {
        const container = createRequirementsContainer();
        container.style.display = 'block';
    }

    function hidePasswordRequirements() {
        if (requirementsContainer) {
            requirementsContainer.style.display = 'none';
        }
    }

    // Update password requirements visual state
    function updatePasswordRequirements() {
        if (!passwordInput || !requirementsContainer) return;
        
        const value = passwordInput.value;
        const requirements = {
            length: value.length >= 8,
            uppercase: /[A-Z]/.test(value),
            lowercase: /[a-z]/.test(value),
            number: /\d/.test(value)
        };
        
        // Update each requirement visually
        Object.keys(requirements).forEach(type => {
            const item = requirementsContainer.querySelector(`.${type}-requirement`);
            if (item) {
                const isValid = requirements[type];
                item.classList.toggle('valid', isValid);
                const icon = item.querySelector('.requirement-icon');
                if (icon) {
                    icon.textContent = isValid ? '✓' : '•';
                    icon.style.color = isValid ? '#28a745' : '';
                }
            }
        });
    }

    // Real-time Validation
    usernameInput?.addEventListener('input', () => validateField(usernameInput, patterns.username));
    emailInput?.addEventListener('input', () => validateField(emailInput, patterns.email));
    passwordInput?.addEventListener('input', () => {
        validateField(passwordInput, patterns.password);
    });

    // Toggle Password Visibility
    togglePassword?.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });

    // Form Submission Validation
    form?.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate all fields
        isValid &= validateField(usernameInput, patterns.username);
        isValid &= validateField(emailInput, patterns.email);
        isValid &= validateField(passwordInput, patterns.password);
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first invalid field
            document.querySelector('.is-invalid')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            
            // Ensure password requirements are shown if password is invalid
            if (passwordInput.classList.contains('is-invalid')) {
                showPasswordRequirements();
                passwordErrorShown = true;
            }
        }
    });
});