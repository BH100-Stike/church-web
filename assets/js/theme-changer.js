document.addEventListener('DOMContentLoaded', function() {
    // Theme switcher functionality
    const colorOptions = document.querySelectorAll('.color-option');
    const htmlElement = document.documentElement;
    const accentColorInput = document.getElementById('accent-color');
    const resetThemeBtn = document.getElementById('reset-theme');
    const themeForm = document.getElementById('theme-form');
    
    // Apply saved theme on page load
    applyTheme(htmlElement.getAttribute('data-theme'), accentColorInput.value);
    
    // Color option click handler
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            const theme = this.getAttribute('data-theme');
            htmlElement.setAttribute('data-theme', theme);
            applyTheme(theme, accentColorInput.value);
        });
    });
    
    // Accent color change handler
    accentColorInput.addEventListener('input', function() {
        const currentTheme = htmlElement.getAttribute('data-theme');
        applyTheme(currentTheme, this.value);
    });
    
    // Reset theme handler
    resetThemeBtn.addEventListener('click', function() {
        htmlElement.setAttribute('data-theme', 'light');
        accentColorInput.value = '#4e73df';
        applyTheme('light', '#4e73df');
        
        // Reset radio buttons
        document.querySelector('input[name="theme"][value="light"]').checked = true;
    });
    
    // Theme form submission
    themeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const theme = formData.get('theme');
        const accentColor = formData.get('accent_color');
        
        // In a real app, you would send this to the server to save preferences
        console.log('Theme saved:', { theme, accentColor });
        
        // Show success message
        showAlert('Theme preferences saved successfully!', 'success');
    });
    
    // Function to apply theme changes
    function applyTheme(theme, accentColor) {
        // Update CSS variables based on theme
        const root = document.documentElement;
        
        // Set accent color
        root.style.setProperty('--accent-color', accentColor);
        
        // Set theme colors
        switch(theme) {
            case 'dark':
                root.style.setProperty('--bg-color', '#1a1a1a');
                root.style.setProperty('--text-color', '#f8f9fa');
                root.style.setProperty('--heading-color', '#ffffff');
                root.style.setProperty('--card-bg', '#2d2d2d');
                root.style.setProperty('--sidebar-bg', '#252525');
                root.style.setProperty('--content-bg', '#1a1a1a');
                root.style.setProperty('--border-color', '#3a3a3a');
                root.style.setProperty('--hover-bg', '#3a3a3a');
                break;
            case 'blue':
                root.style.setProperty('--bg-color', '#f0f5ff');
                root.style.setProperty('--text-color', '#333333');
                root.style.setProperty('--heading-color', '#1e3a8a');
                root.style.setProperty('--card-bg', '#ffffff');
                root.style.setProperty('--sidebar-bg', '#ebf5ff');
                root.style.setProperty('--content-bg', '#f0f5ff');
                root.style.setProperty('--border-color', '#dbeafe');
                root.style.setProperty('--hover-bg', '#dbeafe');
                break;
            case 'green':
                root.style.setProperty('--bg-color', '#f0fdf4');
                root.style.setProperty('--text-color', '#333333');
                root.style.setProperty('--heading-color', '#166534');
                root.style.setProperty('--card-bg', '#ffffff');
                root.style.setProperty('--sidebar-bg', '#dcfce7');
                root.style.setProperty('--content-bg', '#f0fdf4');
                root.style.setProperty('--border-color', '#bbf7d0');
                root.style.setProperty('--hover-bg', '#bbf7d0');
                break;
            default: // light
                root.style.setProperty('--bg-color', '#f8f9fa');
                root.style.setProperty('--text-color', '#333333');
                root.style.setProperty('--heading-color', '#495057');
                root.style.setProperty('--card-bg', '#ffffff');
                root.style.setProperty('--sidebar-bg', '#f8f9fa');
                root.style.setProperty('--content-bg', '#ffffff');
                root.style.setProperty('--border-color', '#e9ecef');
                root.style.setProperty('--hover-bg', '#e9ecef');
        }
    }
    
    // Tab switching functionality
    const tabLinks = document.querySelectorAll('.settings-menu a');
    const tabs = document.querySelectorAll('.settings-tab');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links and tabs
            tabLinks.forEach(l => l.classList.remove('active'));
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Show corresponding tab
            const tabId = this.getAttribute('href').substring(1);
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Profile picture upload preview
    const profileUpload = document.getElementById('profile-upload');
    const profilePreview = document.getElementById('profile-preview');
    const profilePicInput = document.getElementById('profile-pic-input');
    
    profileUpload.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
                profilePicInput.value = 'assets/uploads/' + profileUpload.files[0].name;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Password strength meter
    const passwordInput = document.querySelector('input[name="new_password"]');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text span');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            strengthBar.style.width = strength.percentage + '%';
            strengthBar.style.backgroundColor = strength.color;
            strengthText.textContent = strength.text;
            strengthText.style.color = strength.color;
        });
    }
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        const hasSpecialChars = /[^A-Za-z0-9]/.test(password);
        
        if (password.length > 0) strength += 10;
        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 20;
        if (hasUpperCase) strength += 15;
        if (hasLowerCase) strength += 15;
        if (hasNumbers) strength += 10;
        if (hasSpecialChars) strength += 10;
        
        if (strength < 30) {
            return { percentage: strength, color: '#dc3545', text: 'Weak' };
        } else if (strength < 70) {
            return { percentage: strength, color: '#fd7e14', text: 'Moderate' };
        } else {
            return { percentage: strength, color: '#28a745', text: 'Strong' };
        }
    }
    
    // 2FA toggle
    const twoFactorToggle = document.getElementById('2fa-toggle');
    const twoFactorSetup = document.getElementById('2fa-setup');
    
    if (twoFactorToggle) {
        twoFactorToggle.addEventListener('change', function() {
            twoFactorSetup.style.display = this.checked ? 'block' : 'none';
            
            if (this.checked) {
                // Generate QR code for 2FA setup
                const secret = generateRandomSecret(); // In a real app, get this from server
                const otpUrl = `otpauth://totp/ChurchAdmin:${encodeURIComponent('admin@church.com')}?secret=${secret}&issuer=ChurchAdmin`;
                
                // Clear previous QR code
                document.getElementById('qrcode').innerHTML = '';
                
                // Generate new QR code
                QRCode.toCanvas(document.getElementById('qrcode'), otpUrl, { width: 180 }, function(error) {
                    if (error) console.error(error);
                });
            }
        });
    }
    
    function generateRandomSecret() {
        // In a real app, this should come from your server
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        let secret = '';
        for (let i = 0; i < 32; i++) {
            secret += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return secret;
    }
    
    // Show alert function
    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.role = 'alert';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const alertsContainer = document.querySelector('.alerts-container');
        if (!alertsContainer) {
            const container = document.createElement('div');
            container.className = 'alerts-container';
            document.body.appendChild(container);
        }
        
        document.querySelector('.alerts-container').appendChild(alert);
        
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    }
});