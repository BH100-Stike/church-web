document.addEventListener('DOMContentLoaded', () => {
  new Swiper('.swiper-container', {
    effect: 'fade',
    loop: true,
    speed: 800,
    autoplay: {
      delay: 6000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    fadeEffect: {
      crossFade: true
    }
  });
});
