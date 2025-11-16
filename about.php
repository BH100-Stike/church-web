<?php
// Include necessary files
include 'includes/connect.php';
include 'includes/fetch.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - CBCTY</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/about.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header>
  <nav class="site-nav">
    <p>CBCTY</p>

    <!-- Hamburger toggle -->
    <input type="checkbox" id="nav-toggle" class="nav-toggle" />
    <label for="nav-toggle" class="nav-toggle-label">
      <span></span>
      <span></span>
      <span></span>
    </label>

    <!-- Navigation links -->
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="#history">Our History</a>
      <a href="#leaders">Our Leaders</a>
      <a href="#values">Core Values</a>
      <a href="#testimonials">Testimonials</a>
      <a href="index.php#ministries">Ministries</a>
      <a href="index.php#organizations">Organizations</a>
      <a href="index.php#events">Events</a>
      <a href="index.php#contact">Contact</a>
    </div>

    <!-- Social media links -->
    <div class="nav-social">
      <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      <a href="#" aria-label="X"><i class="fab fa-x"></i></a>
    </div>
  </nav>
</header>

<!-- Hero Section -->
<section class="about-hero" id="home">
  <div class="hero-overlay">
    <h1>Our Story</h1>
    <p>Discover the journey of faith that brought us here</p>
  </div>
</section>

<!-- History Section -->
<section class="about-section" id="history">
  <div class="about-container">
    <div class="about-image">
      <img src="assets/images/vision.jpg" alt="Church History">
    </div>
    <div class="about-content">
      <h2>Our History</h2>
      <p>Founded in 1985, CBCTY began as a small Bible study group meeting in a living room. Through faith and perseverance, we've grown into a vibrant community serving hundreds of families.</p>
      <p>Our current sanctuary was completed in 2005 after years of prayer and faithful giving from our members. Each brick tells a story of God's faithfulness.</p>
    </div>
  </div>
</section>

<!-- Core Values Section -->
<section class="core-values-section" id="values">
  <div class="container">
    <h2 class="section-title">Our Core Values</h2>
    <div class="values-grid">
      <div class="value-card">
        <i class="fas fa-cross"></i>
        <h3>Faith</h3>
        <p>We believe in the power of prayer and God's unchanging word as our foundation for life.</p>
      </div>
      <div class="value-card">
        <i class="fas fa-hands-helping"></i>
        <h3>Community</h3>
        <p>Building authentic relationships that support and encourage spiritual growth.</p>
      </div>
      <div class="value-card">
        <i class="fas fa-heart"></i>
        <h3>Love</h3>
        <p>Demonstrating Christ's love through service to our church, city, and world.</p>
      </div>
    </div>
  </div>
</section>

<!-- Leadership Section -->
<section class="leaders-section" id="leaders">
  <div class="container">
    <h2 class="section-title">Meet Our Leaders</h2>
    <div class="leaders-grid">
      <?php foreach ($leaders as $leader): ?>
        <div class="leader-card">
          <img src="<?= $leader['image_url'] ?>" alt="<?= $leader['name'] ?>" class="leader-img">
          <div class="leader-info">
            <h3><?= $leader['name'] ?></h3>
            <p class="position"><?= $leader['position'] ?></p>
            <p class="leader-bio">
              <?php 
                if (strpos($leader['position'], 'Pastor') !== false) {
                  echo "Provides spiritual leadership and guidance to our congregation.";
                } elseif (strpos($leader['position'], 'Director') !== false) {
                  echo "Leads ministry teams with excellence and dedication.";
                } else {
                  echo "Faithfully serves the church in various leadership capacities.";
                }
              ?>
            </p>
            <div class="leader-meta">
              
              <span class="contact">
                <i class="fas fa-envelope"></i> 
                <?= strtolower(str_replace(' ', '.', $leader['name'])) ?>@cbcty.org
              </span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section" id="testimonials">
  <div class="container">
    <h2 class="section-title">What People Say</h2>
    <div class="testimonials-slider">
      <div class="testimonial active">
        <blockquote>
          "This church has been a spiritual home for our family. The teaching is biblical and the community is genuine."
        </blockquote>
        <div class="author">- The Nfor's Family</div>
      </div>
      <div class="testimonial">
        <blockquote>
          "I found hope and purpose through CBCTY's outreach programs. They truly live out their mission."
        </blockquote>
        <div class="author">-Mr. Tom</div>
      </div>
    </div>
    <div class="testimonial-controls">
      <button class="prev-testimonial"><i class="fas fa-chevron-left"></i></button>
      <button class="next-testimonial"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</section>
 <!-- BACK TO TOP BUTTON -->
  <a href="#home" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
  </a>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<script src="assets/js/script.js"></script>
<script src="assets/js/about.js"></script>
 
</body>
</html>