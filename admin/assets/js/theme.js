// Initialize theme on load
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('globalTheme') || 'dark';
    setGlobalTheme(savedTheme);
});

// Set theme globally
function setGlobalTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('globalTheme', theme);
    
    // Update all toggles on page
    document.querySelectorAll('.global-theme-toggle input').forEach(toggle => {
        toggle.checked = theme === 'light';
    });
}

// Toggle between themes
function toggleGlobalTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setGlobalTheme(newTheme);
}

// Sync across tabs
window.addEventListener('storage', function(e) {
    if (e.key === 'globalTheme') {
        setGlobalTheme(e.newValue);
    }
});