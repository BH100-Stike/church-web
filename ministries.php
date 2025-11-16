<?php
// Database connection and data fetching
include 'includes/connect.php';
include 'includes/fetch.php';

// Function to create consistent section IDs
function createSectionID($title) {
    $id = strtolower($title);
    $id = preg_replace('/[^a-z0-9]+/', '-', $id); // Replace all non-alphanumeric with single hyphen
    $id = trim($id, '-'); // Remove leading/trailing hyphens
    return $id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ministries | CBCTY</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/ministries.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Ensure sections account for fixed header */
    section {
      scroll-margin-top: 100px;
    }
  </style>
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

    <!-- Updated Navigation Links -->
    <div class="nav-links">
      <a href="index.php">Home</a>
      <?php foreach ($ministries as $ministry): 
        $section_id = createSectionID($ministry['title']);
      ?>
        <a href="#<?= $section_id ?>">
          <?= htmlspecialchars($ministry['title']) ?>
        </a>
      <?php endforeach; ?>
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

<!-- MINISTRIES HERO SECTION -->
<section class="ministries-hero" id="home">
  <div class="hero-overlay">
    <div class="hero-text">
      <h1>Our Ministries</h1>
      <p>Discover how you can get involved and grow with us</p>
    </div>
  </div>
</section>

<!-- MAIN MINISTRIES CONTENT -->
<main class="ministries-main">
  <?php foreach ($ministries as $ministry): 
    $section_id = createSectionID($ministry['title']);
  ?>
    <section class="ministry-section" id="<?= $section_id ?>">
      <div class="ministry-container">
        <div class="ministry-image">
          <img src="<?= $ministry['image_url'] ?>" alt="<?= htmlspecialchars($ministry['title']) ?>">
        </div>
        <div class="ministry-content">
          <h2><?= htmlspecialchars($ministry['title']) ?></h2>
          <div class="ministry-meta">
            <?php if (!empty($ministry['meeting_day'])): ?>
              <p><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($ministry['meeting_day']) ?></p>
            <?php endif; ?>
            <?php if (!empty($ministry['meeting_time'])): ?>
              <p><i class="fas fa-clock"></i> <?= htmlspecialchars($ministry['meeting_time']) ?></p>
            <?php endif; ?>
            <?php if (!empty($ministry['location'])): ?>
              <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($ministry['location']) ?></p>
            <?php endif; ?>
          </div>
          <div class="ministry-description">
            <p><?= htmlspecialchars($ministry['description']) ?></p>
          </div>
          <?php if (!empty($ministry['leader'])): ?>
            <div class="ministry-leader">
              <p><strong>Leader:</strong> <?= htmlspecialchars($ministry['leader']) ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endforeach; ?>
</main>

<!-- BACK TO TOP BUTTON -->
<a href="#home" class="back-to-top">
  <i class="fas fa-arrow-up"></i>
</a>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<script src="assets/js/ministries.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>