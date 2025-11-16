// sections in view JS
const sections = document.querySelectorAll('section');
if (sections.length > 0) {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('in-view');
      }
    });
  }, { threshold: 0.2 });

  sections.forEach(s => obs.observe(s));
}

// HERO SLIDER - Modified for IMG tags structure
const heroImages = document.querySelectorAll('.hero-images img');
const prevBtn = document.querySelector('.fa-chevron-left');
const nextBtn = document.querySelector('.fa-chevron-right');

if (heroImages.length > 0) {
  let currentIndex = 0;
  
  // Initialize - show first image, hide others
  heroImages.forEach((img, index) => {
    img.style.opacity = index === 0 ? '1' : '0';
    img.style.display = 'block';
    img.style.transition = 'opacity 1s ease-in-out';
    img.style.position = 'absolute';
    img.style.top = '0';
    img.style.left = '0';
    img.style.width = '100%';
    img.style.height = '100%';
    img.style.objectFit = 'cover';
  });

  function showSlide(index) {
    // Fade out current slide
    heroImages[currentIndex].style.opacity = '0';
    
    // Calculate new index with wrapping
    currentIndex = (index + heroImages.length) % heroImages.length;
    
    // Fade in new slide
    setTimeout(() => {
      heroImages[currentIndex].style.opacity = '1';
    }, 50);
  }

  function nextSlide() {
    showSlide(currentIndex + 1);
  }

  function prevSlide() {
    showSlide(currentIndex - 1);
  }

  // Add event listeners for navigation buttons
  if (prevBtn) prevBtn.addEventListener('click', prevSlide);
  if (nextBtn) nextBtn.addEventListener('click', nextSlide);

  // Auto-rotate every 10 seconds
  setInterval(nextSlide, 10000);
}

// MINISTRIES SLIDER (unchanged)
const ministriesSlider = document.querySelector('.ministries-slider');
if (ministriesSlider) {
  const cards = document.querySelectorAll('.ministries-card');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');
  
  if (cards.length > 0) {
    const total = cards.length;
    let index = 0;
    let autoplayInterval;

    function cardsPerView() {
      const width = window.innerWidth;
      if (width <= 640) return 1;
      if (width <= 1024) return 2;
      return 3;
    }

    function updateSlider() {
      const perView = cardsPerView();
      const maxIndex = Math.ceil(total / perView) - 1;
      if (index < 0) index = maxIndex;
      if (index > maxIndex) index = 0;

      const shift = (100 / perView) * index;
      ministriesSlider.style.transform = `translateX(-${shift}%)`;
    }

    function startAutoplay() {
      stopAutoplay();
      autoplayInterval = setInterval(() => {
        index++;
        updateSlider();
      }, 7000);
    }

    function stopAutoplay() {
      clearInterval(autoplayInterval);
    }

    if (prevBtn) prevBtn.addEventListener('click', () => {
      index--;
      updateSlider();
    });

    if (nextBtn) nextBtn.addEventListener('click', () => {
      index++;
      updateSlider();
    });

    cards.forEach(card => {
      card.addEventListener('mouseenter', stopAutoplay);
      card.addEventListener('mouseleave', startAutoplay);
    });

    window.addEventListener('resize', updateSlider);
    updateSlider();
    startAutoplay();
  }
}

// CONTROLLER ARROW VISIBILITY AFTER SCROLL (unchanged)
const backToTop = document.querySelector('.back-to-top');
if (backToTop) {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
      backToTop.classList.add('visible');
    } else {
      backToTop.classList.remove('visible');
    }
  });
}

// LEADERS SLIDER 
const leaderslider = document.querySelector('.leaders-slider');
if (leaderslider) {
  const cards = document.querySelectorAll('.leader-card');
  if (cards.length > 0) {
    const cardCount = cards.length;
    
    // CARD MOVEMENT LOOP
    cards.forEach(card => {
      const clone = card.cloneNode(true);
      leaderslider.appendChild(clone);
    });

    let scrollPosition = 0;
    let animationFrame;

    function animateSlider() {
      const cardWidth = cards[0].offsetWidth + 24;
      scrollPosition += 1;

      if (scrollPosition >= cardWidth * cardCount) {
        scrollPosition = 0;
      }

      leaderslider.style.transform = `translateX(-${scrollPosition}px)`;
      animationFrame = requestAnimationFrame(animateSlider);
    }

    animateSlider();

    leaderslider.addEventListener('mouseenter', () => cancelAnimationFrame(animationFrame));
    leaderslider.addEventListener('mouseleave', () => animateSlider());
  }
}


// FLOATING SHAPES IN ORGANISATION SECTION 
document.addEventListener('DOMContentLoaded', function() {
  const container = document.createElement('div');
  container.className = 'shape-container';
  document.querySelector('#organizations').prepend(container);
  
  const shapes = ['circle', 'star', 'hexagon'];
  const colors = ['dark-green', 'light-green'];
  const animations = ['float-1', 'float-2', 'float-3'];
  
  // Generate 30 shapes
  for (let i = 0; i < 30; i++) {
    const shape = document.createElement('div');
    shape.className = `floating-shape ${shapes[Math.floor(Math.random() * shapes.length)]} ${colors[Math.floor(Math.random() * colors.length)]}`;
    
    // Random properties
    const size = Math.random() * 20 + 5;
    const duration = Math.random() * 20 + 10;
    const delay = Math.random() * 15;
    const opacity = Math.random() * 0.1 + 0.05;
    
    Object.assign(shape.style, {
      width: `${size}px`,
      height: `${size}px`,
      left: `${Math.random() * 100}%`,
      top: `${Math.random() * 100}%`,
      animationName: animations[Math.floor(Math.random() * animations.length)],
      animationDuration: `${duration}s`,
      animationDelay: `${delay}s`,
      opacity: opacity
    });
    
    container.appendChild(shape);
  }
});