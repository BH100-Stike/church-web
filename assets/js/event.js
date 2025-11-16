// assets/js/events.js

document.addEventListener('DOMContentLoaded', function() {
    // Event Modal Functionality
    const eventModal = document.querySelector('.event-modal');
    const modalContent = document.querySelector('.modal-content');
    const closeModal = document.querySelector('.close-modal');
    
    // Open modal when clicking event cards
    document.querySelectorAll('.event-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't open modal if clicking links inside card
            if (e.target.tagName === 'A' || e.target.parentElement.tagName === 'A') {
                return;
            }
            
            const eventId = this.dataset.eventId;
            loadEventModal(eventId);
        });
    });
    
    // Close modal
    closeModal.addEventListener('click', closeEventModal);
    eventModal.addEventListener('click', function(e) {
        if (e.target === eventModal) {
            closeEventModal();
        }
    });
    
    // Filter between upcoming and past events
    const eventFilterTabs = document.querySelectorAll('.event-filter-tab');
    if (eventFilterTabs.length > 0) {
        eventFilterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const filterType = this.dataset.filter;
                filterEvents(filterType);
            });
        });
    }
    
    // Back to top button
    const backToTopBtn = document.querySelector('.back-to-top');
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        
        backToTopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

// Load event details into modal
function loadEventModal(eventId) {
    // In a real implementation, you would fetch this from your server
    // For now, we'll use the data from the clicked card
    
    const eventCard = document.querySelector(`.event-card[data-event-id="${eventId}"]`);
    if (!eventCard) return;
    
    const eventTitle = eventCard.querySelector('h3').textContent;
    const eventDate = eventCard.querySelector('.event-date').textContent;
    const eventTime = eventCard.querySelector('.event-meta p:nth-child(1)').textContent;
    const eventLocation = eventCard.querySelector('.event-meta p:nth-child(2)').textContent;
    const eventDescription = eventCard.querySelector('.event-excerpt').textContent;
    const eventImage = eventCard.querySelector('.event-image img').src;
    
    const modalBody = document.querySelector('.modal-body');
    modalBody.innerHTML = `
        <div class="modal-event-image">
            <img src="${eventImage}" alt="${eventTitle}">
        </div>
        <div class="modal-event-info">
            <h2>${eventTitle}</h2>
            <div class="modal-event-meta">
                <p><i class="fas fa-calendar-alt"></i> ${eventDate}</p>
                <p><i class="fas fa-clock"></i> ${eventTime}</p>
                <p><i class="fas fa-map-marker-alt"></i> ${eventLocation}</p>
            </div>
            <div class="modal-event-description">
                <p>${eventDescription}</p>
            </div>
            <div class="modal-event-actions">
                <a href="#" class="modal-event-btn"><i class="fas fa-calendar-plus"></i> Add to Calendar</a>
                <a href="#" class="modal-event-btn"><i class="fas fa-share-alt"></i> Share Event</a>
            </div>
        </div>
    `;
    
    document.querySelector('.event-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeEventModal() {
    document.querySelector('.event-modal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Filter events between upcoming and past
function filterEvents(filterType) {
    const allEvents = document.querySelectorAll('.event-card');
    
    allEvents.forEach(event => {
        const eventDate = new Date(event.dataset.eventDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (filterType === 'all') {
            event.style.display = 'block';
        } else if (filterType === 'upcoming') {
            event.style.display = eventDate >= today ? 'block' : 'none';
        } else if (filterType === 'past') {
            event.style.display = eventDate < today ? 'block' : 'none';
        }
    });
    
    // Update active tab styling
    document.querySelectorAll('.event-filter-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.filter === filterType) {
            tab.classList.add('active');
        }
    });
}