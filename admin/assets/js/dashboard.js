// ==============================================
// GLOBAL THEME MANAGEMENT SYSTEM (WORKS ON ALL PAGES)
// ==============================================

// Initialize theme from localStorage or default to dark
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    setTheme(savedTheme);
}

// Function to set theme that can be called from anywhere
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    
    // Update toggle state if it exists on this page
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.checked = theme === 'light';
    }
    
    // Dispatch custom event for any theme-dependent components
    document.dispatchEvent(new CustomEvent('themeChanged', { detail: theme }));
}

// Function to toggle theme that can be called from anywhere
function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
}

// Listen for theme changes from other tabs/windows
window.addEventListener('storage', function(e) {
    if (e.key === 'theme') {
        setTheme(e.newValue);
    }
});

// ==============================================
// NOTIFICATION SYSTEM (WORKS ON ALL PAGES)
// ==============================================

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'}"></i>
        <span>${message}</span>
        <button class="close-btn"><i class="bi bi-x"></i></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    notification.querySelector('.close-btn').addEventListener('click', function() {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    });
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Check for URL parameters to show notifications (works on all pages)
function checkForNotifications() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showNotification(urlParams.get('success'), 'success');
    } else if (urlParams.has('error')) {
        showNotification(urlParams.get('error'), 'error');
    }
}

// ==============================================
// DASHBOARD-SPECIFIC FUNCTIONALITY
// ==============================================

function setupDashboardFeatures() {
    // Only run if on dashboard page (check for dashboard-specific elements)
    const topBar = document.querySelector('.top-bar');
    const sidebar = document.querySelector('.sidebar');
    
    if (!topBar || !sidebar) return;

    // Toggle sidebar on mobile
    const sidebarToggle = document.createElement('button');
    sidebarToggle.innerHTML = '<i class="bi bi-list"></i>';
    sidebarToggle.className = 'sidebar-toggle d-lg-none';
    topBar.prepend(sidebarToggle);
    
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992 && 
            !e.target.closest('.sidebar') && 
            !e.target.closest('.sidebar-toggle')) {
            sidebar.classList.remove('active');
        }
    });
    
    // Active menu item highlighting
    const currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';
    const menuItems = document.querySelectorAll('.sidebar-menu li a');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPage.includes(href.replace('.php', ''))) {
            item.parentElement.classList.add('active');
        }
    });
    
    // Search functionality (only if search elements exist)
    const searchBox = document.querySelector('.search-box input');
    const searchButton = document.querySelector('.search-box button');
    
    if (searchButton && searchBox) {
        searchButton.addEventListener('click', function() {
            performSearch(searchBox.value);
        });
        
        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch(searchBox.value);
            }
        });
    }
    
    function performSearch(query) {
        if (query.trim() !== '') {
            alert('Searching for: ' + query);
        }
    }
    
    // Sample activities (only if activity list exists)
    const activityList = document.querySelector('.activity-list');
    if (activityList) {
        const activities = [
            {
                icon: 'bi-check-circle-fill text-success',
                text: 'Updated hero section images',
                time: '2 hours ago'
            },
            {
                icon: 'bi-plus-circle-fill text-primary',
                text: 'Added new event: Sunday Service',
                time: '1 day ago'
            },
            {
                icon: 'bi-pencil-fill text-warning',
                text: 'Edited about us content',
                time: '2 days ago'
            }
        ];
        
        activityList.innerHTML = activities.map(activity => `
            <li>
                <i class="${activity.icon}"></i>
                <span>${activity.text}</span>
                <small>${activity.time}</small>
            </li>
        `).join('');
    }
    
    // Responsive mobile menu (only if elements exist)
    const sidebarMenu = document.querySelector('.sidebar-menu');
    if (sidebarMenu) {
        const mobileToggle = document.createElement('div');
        mobileToggle.className = 'mobile-menu-toggle';
        mobileToggle.innerHTML = '<i class="bi bi-list"></i>';
        document.body.appendChild(mobileToggle);
        
        mobileToggle.addEventListener('click', function() {
            sidebarMenu.classList.toggle('active');
            mobileToggle.innerHTML = sidebarMenu.classList.contains('active') ? 
                '<i class="bi bi-x"></i>' : '<i class="bi bi-list"></i>';
        });
        
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 576) {
                    sidebarMenu.classList.remove('active');
                    mobileToggle.innerHTML = '<i class="bi bi-list"></i>';
                }
            });
        });
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 576) {
                sidebarMenu.classList.remove('active');
                mobileToggle.innerHTML = '<i class="bi bi-list"></i>';
            }
        });
    }
}

// ==============================================
// SETTINGS MODAL FUNCTIONALITY (WORKS ON ALL PAGES)
// ==============================================

function setupSettingsModal() {
    // Settings Modal Toggle
    const settingsButton = document.getElementById('settingsButton');
    const settingsModal = document.getElementById('settingsModal');
    const closeSettings = document.getElementById('closeSettings');
    
    if (settingsButton && settingsModal && closeSettings) {
        settingsButton.addEventListener('click', function(e) {
            e.preventDefault();
            settingsModal.style.display = 'flex';
        });
        
        closeSettings.addEventListener('click', function() {
            settingsModal.style.display = 'none';
        });
        
        settingsModal.addEventListener('click', function(e) {
            if (e.target === settingsModal) {
                settingsModal.style.display = 'none';
            }
        });
    }

    // Profile Edit Modal
    const editProfileBtn = document.getElementById('editProfileBtn');
    const editProfileModal = document.getElementById('editProfileModal');
    const cancelProfileEdit = document.getElementById('cancelProfileEdit');
    
    if (editProfileBtn && editProfileModal && cancelProfileEdit) {
        editProfileBtn.addEventListener('click', function() {
            if (settingsModal) settingsModal.style.display = 'none';
            editProfileModal.style.display = 'flex';
        });
        
        cancelProfileEdit.addEventListener('click', function() {
            editProfileModal.style.display = 'none';
            if (settingsModal) settingsModal.style.display = 'flex';
        });
        
        editProfileModal.addEventListener('click', function(e) {
            if (e.target === editProfileModal) {
                editProfileModal.style.display = 'none';
                if (settingsModal) settingsModal.style.display = 'flex';
            }
        });
    }

    // Profile Image Upload
    const profileImageUpload = document.getElementById('profileImageUpload');
    if (profileImageUpload) {
        profileImageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('profileImagePreview');
                    if (preview) {
                        preview.src = event.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Password Change Modal
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const passwordModal = document.getElementById('passwordModal');
    const cancelPasswordChange = document.getElementById('cancelPasswordChange');
    
    if (changePasswordBtn && passwordModal && cancelPasswordChange) {
        changePasswordBtn.addEventListener('click', function() {
            if (settingsModal) settingsModal.style.display = 'none';
            passwordModal.style.display = 'flex';
        });
        
        cancelPasswordChange.addEventListener('click', function() {
            passwordModal.style.display = 'none';
            if (settingsModal) settingsModal.style.display = 'flex';
        });
        
        passwordModal.addEventListener('click', function(e) {
            if (e.target === passwordModal) {
                passwordModal.style.display = 'none';
                if (settingsModal) settingsModal.style.display = 'flex';
            }
        });
    }

    // Password visibility toggle
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    if (togglePasswordBtns.length > 0) {
        togglePasswordBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input) {
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                }
            });
        });
    }

    // Password strength checker
    const newPasswordInput = document.getElementById('newPassword');
    if (newPasswordInput) {
        const strengthMeter = document.querySelector('.strength-meter');
        const strengthText = document.querySelector('.strength-text');
        
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            
            if (strengthMeter && strengthText) {
                strengthMeter.parentElement.setAttribute('data-strength', strength);
                const strengthLabels = ['Very Weak', 'Weak', 'Medium', 'Strong', 'Very Strong'];
                strengthText.textContent = strengthLabels[strength - 1];
            }
        });
    }

    // Password confirmation check
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordMatchFeedback = document.getElementById('passwordMatch');
    if (confirmPasswordInput && newPasswordInput && passwordMatchFeedback) {
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== newPasswordInput.value) {
                passwordMatchFeedback.textContent = "Passwords don't match!";
                passwordMatchFeedback.style.color = 'var(--danger)';
            } else {
                passwordMatchFeedback.textContent = "Passwords match!";
                passwordMatchFeedback.style.color = 'var(--success)';
            }
        });
    }

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return Math.min(strength, 5);
    }
}

// ==============================================
// INITIALIZE EVERYTHING WHEN DOM LOADS
// ==============================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme first
    initTheme();
    
    // Check for notifications
    checkForNotifications();
    
    // Setup dashboard features if on dashboard
    setupDashboardFeatures();
    
    // Setup settings modal (works on all pages)
    setupSettingsModal();
});