        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            
            // Add slight delay for smooth transition
            setTimeout(() => {
                loader.classList.add('fade-out');
                
                // Remove loader from DOM after animation completes
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }, 300);
        });