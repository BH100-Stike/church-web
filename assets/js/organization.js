document.addEventListener('DOMContentLoaded', function() {
  // DOM Elements
  const categoryTabs = document.querySelectorAll('.category-tab');
  const partnerCards = document.querySelectorAll('.partner-card');
  const searchInput = document.getElementById('partner-search');
  const modal = document.querySelector('.partner-modal');
  const closeModal = document.querySelector('.close-modal');
  const modalBody = document.querySelector('.modal-body');
  const backToTop = document.querySelector('.back-to-top');

  // Initialize partners data from data attributes
  const partnersData = {};
  document.querySelectorAll('.partner-card').forEach(card => {
    const id = card.dataset.id;
    partnersData[id] = {
      name: card.querySelector('h3').textContent,
      image: card.querySelector('img').src,
      category: card.querySelector('.partner-category').textContent,
      description: card.dataset.description || '',
      website: card.dataset.website || '',
      contact: card.dataset.contact || '',
      since: card.dataset.since || ''
    };
  });

  // Filter organizations by category
  function filterByCategory(category) {
    partnerCards.forEach(card => {
      const cardCategory = card.dataset.category;
      const isVisible = category === 'all' || cardCategory === category;
      card.style.display = isVisible ? 'block' : 'none';
    });
  }

  // Search functionality
  function searchOrganizations(term) {
    term = term.toLowerCase();
    partnerCards.forEach(card => {
      const cardName = card.querySelector('h3').textContent.toLowerCase();
      const isMatch = cardName.includes(term);
      if (card.style.display !== 'none') {
        card.style.display = isMatch ? 'block' : 'none';
      }
    });
  }

  // Modal functionality
  function openModal(partnerId) {
    const partner = partnersData[partnerId];
    if (!partner) return;

    modalBody.innerHTML = `
      <div class="modal-header">
        <div class="modal-logo">
          <img src="${partner.image}" alt="${partner.name}">
        </div>
        <div class="modal-title">
          <h2>${partner.name}</h2>
          <span class="modal-category">${partner.category}</span>
        </div>
      </div>
      <div class="modal-description">
        <p>${partner.description}</p>
      </div>
      <div class="modal-details">
        ${partner.since ? `<p><strong>Partner Since:</strong> ${partner.since}</p>` : ''}
        ${partner.contact ? `<p><strong>Contact:</strong> ${partner.contact}</p>` : ''}
      </div>
      <div class="modal-actions">
        ${partner.website ? `<a href="${partner.website}" target="_blank" class="modal-button">
          <i class="fas fa-external-link-alt"></i> Visit Website
        </a>` : ''}
        <button class="modal-button close-modal-btn">
          <i class="fas fa-times"></i> Close
        </button>
      </div>
    `;

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    document.querySelector('.close-modal-btn')?.addEventListener('click', closeModalHandler);
  }

  function closeModalHandler() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Event Listeners
  categoryTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      categoryTabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      filterByCategory(this.dataset.category);
    });
  });

  searchInput.addEventListener('input', function() {
    searchOrganizations(this.value);
  });

  document.querySelectorAll('.partner-more').forEach(button => {
    button.addEventListener('click', function() {
      openModal(this.dataset.partner);
    });
  });

  closeModal.addEventListener('click', closeModalHandler);
  window.addEventListener('click', function(event) {
    if (event.target === modal) closeModalHandler();
  });
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') closeModalHandler();
  });

  // Back to top button
  window.addEventListener('scroll', function() {
    backToTop.style.display = window.pageYOffset > 300 ? 'block' : 'none';
  });

  backToTop.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // Initialize
  filterByCategory('all');
});