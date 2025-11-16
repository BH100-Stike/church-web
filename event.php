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
  <title>Events | CBCTY</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/event.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>

<!-- HEADER SECTION -->
<header>
  <nav class="site-nav">
    <p>CBCTY</p>
    <input type="checkbox" id="nav-toggle" class="nav-toggle" />
    <label for="nav-toggle" class="nav-toggle-label">
      <span></span>
      <span></span>
      <span></span>
    </label>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="index.php#history">Our History</a>
      <a href="index.php#leaders">Our Leaders</a>
      <a href="index.php#values">Core Values</a>
      <a href="index.php#testimonials">Testimonials</a>
      <a href="index.php#ministries">Ministries</a>
      <a href="organizations.php">Organizations</a>
      <a href="events.php" class="active">Events</a>
      <a href="index.php#contact">Contact</a>
    </div>
    <div class="nav-social">
      <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      <a href="#" aria-label="X"><i class="fab fa-x"></i></a>
    </div>
  </nav>
</header>

<!-- EVENTS HERO SECTION -->
<section class="page-hero" id="events-hero">
  <div class="hero-overlay">
    <div class="hero-text">
      <h1>Church Events</h1>
      <p>Join us for worship, fellowship, and community activities</p>
    </div>
  </div>
</section>

<!-- MAIN CONTENT -->
<main class="modern-events">
  <!-- UPCOMING EVENTS -->
  <section class="upcoming-section">
    <div class="section-header">
      <h2 class="section-title">Upcoming Events</h2>
    </div>
    
    <?php if (!empty($upcoming_events)): ?>
      <?php foreach (array_slice($upcoming_events, 0, 3) as $event): ?>
        <article class="event-card-modern">
          <div class="event-image-curve">
            <img src="<?= htmlspecialchars($event['image_url'] ?? 'assets/images/events/default.jpg') ?>" 
                 alt="<?= htmlspecialchars($event['title']) ?>">
          </div>
          <div class="event-content">
            <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
            <div class="event-meta">
              <span><i class="fas fa-calendar-alt"></i> <?= date('F j, Y', strtotime($event['date'])) ?></span>
              <span><i class="fas fa-clock"></i> <?= $event['formatted_time'] ?? date('g:i A', strtotime($event['time'])) ?></span>
              <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['location']) ?></span>
            </div>
            <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
            <div class="event-actions">
              <a href="#" class="event-button primary">
                <i class="fas fa-info-circle"></i> Details
              </a>
              <?php if (!empty($event['registration_url'])): ?>
                <a href="<?= htmlspecialchars($event['registration_url']) ?>" class="event-button secondary">
                  <i class="fas fa-user-plus"></i> Register
                </a>
              <?php endif; ?>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-events">
        <i class="fas fa-calendar-plus"></i>
        <p>No upcoming events scheduled. Check back soon!</p>
      </div>
    <?php endif; ?>
  </section>

  <!-- PAST EVENTS -->
  <section class="past-section">
    <div class="section-header">
      <h2 class="section-title">Past Events</h2>
    </div>
    
    <?php if (!empty($past_events)): ?>
      <?php foreach (array_slice($past_events, 0, 3) as $event): ?>
        <article class="event-card-modern">
          <div class="event-image-curve">
            <img src="<?= htmlspecialchars($event['image_url'] ?? 'assets/images/events/default.jpg') ?>" 
                 alt="<?= htmlspecialchars($event['title']) ?>">
          </div>
          <div class="event-content">
            <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
            <div class="event-meta">
              <span><i class="fas fa-calendar-alt"></i> <?= date('F j, Y', strtotime($event['date'])) ?></span>
              <span><i class="fas fa-clock"></i> <?= $event['formatted_time'] ?? date('g:i A', strtotime($event['time'])) ?></span>
              <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['location']) ?></span>
            </div>
            <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
            <div class="event-actions">
              <?php if (!empty($event['gallery_url'])): ?>
                <a href="<?= htmlspecialchars($event['gallery_url']) ?>" class="event-button secondary">
                  <i class="fas fa-images"></i> View Gallery
                </a>
              <?php endif; ?>
              <?php if (!empty($event['report_url'])): ?>
                <a href="<?= htmlspecialchars($event['report_url']) ?>" class="event-button secondary">
                  <i class="fas fa-file-alt"></i> Read Report
                </a>
              <?php endif; ?>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-events">
        <i class="fas fa-calendar-check"></i>
        <p>No past events to display.</p>
      </div>
    <?php endif; ?>
  </section>
</main>

<!-- EVENT MODAL -->
<div class="event-modal">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <div class="modal-body">
      <!-- Content will be loaded via JavaScript -->
    </div>
  </div>
</div>

<!-- BACK TO TOP BUTTON -->
<a href="#events-hero" class="back-to-top">
  <i class="fas fa-arrow-up"></i>
</a>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<script src="assets/js/event.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>