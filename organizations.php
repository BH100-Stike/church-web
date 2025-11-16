<?php
// Database connection and data fetching
include 'includes/connect.php';
include 'includes/fetch.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Organizations | CBCTY</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/organizations.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>


<!-- HEADER SECTION -->
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
      <a href="index.php#history">Our History</a>
      <a href="index.php#leaders">Our Leaders</a>
      <a href="index.php#values">Core Values</a>
      <a href="index.php#testimonials">Testimonials</a>
      <a href="index.php#ministries">Ministries</a>
      <a href="organizations.php" class="active">Organizations</a>
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

<!-- ORGANIZATIONS HERO SECTION -->
<section class="page-hero">
  <div class="hero-overlay">
    <div class="hero-text">
      <h1>Our Valued Organizations</h1>
      <p>Collaborating for community transformation</p>
    </div>
  </div>
</section>

<!-- PARTNER INTRO SECTION -->
<section class="partners-intro">
  <div class="section-container">
    <h2 class="section-title">Strategic Alliances</h2>
    <div class="heading-line"></div>
    <p class="intro-text">We work with diverse organizations that share our vision for community development and spiritual growth. These partnerships amplify our impact and extend our reach.</p>
  </div>
</section>

<!-- PARTNER CATEGORIES -->
<section class="partner-categories">
  <div class="category-tabs">
    <div class="category-tab active" data-category="all">
      <i class="fas fa-handshake"></i>
      <span>All Organizations</span>
    </div>
    <div class="category-tab" data-category="local">
      <i class="fas fa-map-marker-alt"></i>
      <span>Local</span>
    </div>
    <div class="category-tab" data-category="international">
      <i class="fas fa-globe-americas"></i>
      <span>International</span>
    </div>
    <div class="category-tab" data-category="ministry">
      <i class="fas fa-pray"></i>
      <span>Ministries</span>
    </div>
  </div>
</section>

<!-- PARTNER GRID -->
<section class="partner-grid-section">
  <div class="search-container">
    <div class="search-box">
      <input type="text" id="partner-search" placeholder="Search Organization...">
      <button type="submit"><i class="fas fa-search"></i></button>
    </div>
  </div>

  <div class="partner-grid">
    <?php foreach ($organizations as $org): ?>
      <div class="partner-card" data-category="<?= strtolower($org['category'] ?? 'all') ?>">
        <div class="partner-logo">
          <img src="<?= $org['image_url'] ?>" alt="<?= $org['name'] ?>">
          <span class="partner-category"><?= $org['category'] ?? '' ?></span>
        </div>
        <div class="partner-info">
          <h3><?= $org['name'] ?></h3>
          <p class="partner-excerpt"><?= substr($org['description'], 0, 100) ?>...</p>
          <div class="partner-links">
            <?php if (!empty($org['website'])): ?>
              <a href="<?= $org['website'] ?>" target="_blank" class="partner-link">
                <i class="fas fa-external-link-alt"></i> Website
              </a>
            <?php endif; ?>
           
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- PARTNER MODAL (hidden by default) -->
<div class="partner-modal">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <div class="modal-body">
      <!-- Content will be loaded via JavaScript -->
    </div>
  </div>
</div>

<!-- PARTNER TESTIMONIALS -->
<section class="partner-testimonials">
  <div class="section-container">
    <h2 class="section-title">Partnership Stories</h2>
    <div class="heading-line"></div>
    <div class="testimonial-slider">
      <div class="testimonial">
        <blockquote>
          "Our collaboration with CBCTY has enabled us to reach communities we couldn't have impacted alone."
        </blockquote>
        <div class="testimonial-author">
          <strong>Rev. John Smith</strong>
          <span>Hope International</span>
        </div>
      </div>
      <!-- More testimonials can be added here -->
    </div>
  </div>
</section>

<!-- BACK TO TOP BUTTON -->
<a href="#" class="back-to-top">
  <i class="fas fa-arrow-up"></i>
</a>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<script src="assets/js/organization.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>