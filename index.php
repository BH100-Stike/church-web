<?php
// connecting to database
include 'includes/connect.php';

include 'includes/fetch.php';

// Process contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form_submitted'])) {
    require_once 'includes/connect.php'; // Adjust path as needed
    
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $address = htmlspecialchars(trim($_POST['address']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($phone)) $errors[] = "Phone is required";
    if (empty($message)) $errors[] = "Message is required";
    
    if (empty($errors)) {
        try {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, address, phone, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $address, $phone, $message);
            $stmt->execute();
            
            // Redirect with success
            header("Location: index.php?contact_status=success&message=Message+sent+successfully#contact");
            exit();
        } catch (Exception $e) {
            // Redirect with error
            header("Location: index.php?contact_status=error&message=Error+sending+message#contact");
            exit();
        }
    } else {
        // Redirect with validation errors
        $errorString = implode(', ', $errors);
        header("Location: index.php?contact_status=error&message=" . urlencode($errorString) . "#contact");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CBCTY</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    

<!-- HEADER SECTION -->
<?php
include 'includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero" id="home">
  <div class="hero-images">
    <?php foreach ($heroContent as $hero): ?>
      <img src="<?= $hero['image_url'] ?>" alt="Hero Image">
    <?php endforeach; ?>
  </div>
  <div class="hero-overlay">
    <div class="hero-text">
      <h1><?= $heroContent[0]['title'] ?></h1>
      <p><?= $heroContent[0]['subtitle'] ?></p>
    </div>
  </div>
  <div class="slider-controls">
    <i class="fas fa-chevron-left"></i>
    <i class="fas fa-chevron-right"></i>
  </div>
</section>

<!-- ABOUT SECTION -->
<section class="about-section" id="about">
  <h2 class="section-title">About Us</h2>
  <div class="heading-line"></div>
  <div class="about-container">
    <div class="about-image">
      <img src="<?= $aboutContent['image_url'] ?>" alt="About Us">
    </div>
    <div class="about-content">
      <h3><?= $aboutContent['title'] ?></h3>
      <p><?= $aboutContent['description'] ?></p>
      <a href="about.php"><button class="read-more-btn">Read More</button></a>
    </div>
  </div>
</section>

<!-- LEADERS SECTION -->
<section class="leaders-section" id="leaders">
  <h2 class="section-title">Our Church Leaders</h2>
  <div class="heading-line"></div>
  <div class="leaders-wrapper">
    <div class="leaders-slider">
      <?php foreach ($leaders as $leader): ?>
        <div class="leader-card">
          <img src="<?= $leader['image_url'] ?>" alt="<?= $leader['name'] ?>">
          <h3><?= $leader['name'] ?></h3>
          <p><?= $leader['position'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- MINISTRIES SECTION -->
<section class="ministries-section" id="ministries">
  <h2 class="section-title">Our Ministries</h2>
  <div class="heading-line"></div>
  <div class="ministries-wrapper">
    <div class="ministries-slider">
      <?php foreach ($ministries as $ministry): ?>
        <div class="ministries-card">
          <img src="<?= $ministry['image_url'] ?>" alt="<?= $ministry['title'] ?>">
          <h3><?= $ministry['title'] ?></h3>
          <p><?= $ministry['description'] ?></p>
          <a href="ministries.php"><button class="read-more-btn">See More</button></a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="slider-controls">
      <button class="prev-btn" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
      <button class="next-btn" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</section>

<!-- ORGANIZATIONS SECTION -->
<section class="organizations-section" id="organizations">
  <!-- Floating shapes (add as many as you want) -->
  <div class="floating-shape dark-green circle" style="width: 30px; height: 30px; top: 20%; left: 10%; animation-delay: 0s;"></div>
  <div class="floating-shape light-green star" style="width: 25px; height: 25px; top: 70%; left: 85%; animation-delay: 2s;"></div>
  <div class="floating-shape dark-green triangle" style="width: 40px; height: 40px; top: 40%; left: 75%; animation-delay: 4s;"></div>
  <div class="floating-shape light-green circle" style="width: 20px; height: 20px; top: 80%; left: 15%; animation-delay: 1s;"></div>
  <div class="floating-shape dark-green star" style="width: 15px; height: 15px; top: 30%; left: 50%; animation-delay: 3s;"></div>
  
  <h2 class="section-title">Organizations and Daughter churches</h2>
  <div class="heading-line"></div>
  <div class="organization-wrapper">
    <?php foreach ($organizations as $organization): ?>
      <a href="organizations.php"><div class="organization-card">
        <img src="<?= $organization['image_url'] ?>" alt="<?= $organization['name'] ?>">
        <h3><?= $organization['name'] ?></h3>
        <p><?= $organization['description'] ?></p>
      </div></a>
    <?php endforeach; ?>
  </div>
</section>

<!-- UPCOMING EVENTS SECTION -->
<section class="upcoming-events" id="events">
  <div class="section-container">
    <h2 class="section-title">Upcoming Events</h2>
    <div class="heading-line"></div>
    
    <div class="events-grid">
      <?php 
      $upcoming_shown = 0;
      $current_date = date('Y-m-d');
      
      foreach ($events as $event): 
        if (strtotime($event['date']) >= strtotime($current_date) && $upcoming_shown < 3): 
          $upcoming_shown++;
      ?>
          <div class="event-card">
            <div class="event-image">
              <img src="<?= htmlspecialchars($event['image_url'] ?? 'assets/images/events/default.jpg') ?>" 
                   alt="<?= htmlspecialchars($event['title']) ?>">
              <div class="event-date-badge">
                <span class="event-day"><?= date('d', strtotime($event['date'])) ?></span>
                <span class="event-month"><?= date('M', strtotime($event['date'])) ?></span>
              </div>
            </div>
            <div class="event-content">
              <h3><?= htmlspecialchars($event['title']) ?></h3>
              <div class="event-meta">
                <span><i class="fas fa-clock"></i> <?= date('g:i A', strtotime($event['time'])) ?></span>
                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['location']) ?></span>
              </div>
              <p class="event-excerpt"><?= htmlspecialchars(substr($event['description'], 0, 100)) ?>...</p>
              <a href="event.php" class="event-link">View Details <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
      
      <?php if ($upcoming_shown === 0): ?>
        <div class="no-events-message">
          <div class="empty-state-icon">
            <i class="fas fa-calendar-plus"></i>
          </div>
          <h3>No Upcoming Events</h3>
          <p>We don't have any events scheduled right now. Please check back later!</p>
          <a href="event.php" class="cta-button">Browse Past Events</a>
        </div>
      <?php endif; ?>
    </div>
    
    <?php if ($upcoming_shown > 0): ?>
      <div class="view-all-container">
        <a href="event.php" class="view-all-btn">View All Events <i class="fas fa-arrow-right"></i></a>
      </div>
    <?php endif; ?>
  </div>
</section>

<style>
  /* Upcoming Events Section */
  .upcoming-events {
    padding: 4rem 0;
    background-color: #f8f9fa;
  }
  
  .section-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
  }
  
  .section-title {
    text-align: center;
    font-size: 2rem;
    color: var(--dark-green);
    margin-bottom: 1rem;
  }
  
  .heading-line {
    width: 80px;
    height: 3px;
    background: var(--dark-green);
    margin: 0 auto 2rem;
  }
  
  /* Events Grid */
  .events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
  }
  
  /* Event Card */
  .event-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
  }
  
  .event-image {
    position: relative;
    height: 200px;
    overflow: hidden;
  }
  
  .event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  
  .event-card:hover .event-image img {
    transform: scale(1.05);
  }
  
  .event-date-badge {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: rgba(6, 78, 59, 0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    text-align: center;
    line-height: 1.2;
  }
  
  .event-day {
    display: block;
    font-size: 1.4rem;
    font-weight: bold;
  }
  
  .event-month {
    display: block;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  
  .event-content {
    padding: 1.5rem;
  }
  
  .event-content h3 {
    color: var(--dark-green);
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
  }
  
  .event-meta {
    display: flex;
    gap: 1rem;
    margin: 0.8rem 0;
    font-size: 0.9rem;
    color: #555;
  }
  
  .event-meta i {
    color: var(--dark-green);
    margin-right: 0.3rem;
  }
  
  .event-excerpt {
    color: #555;
    line-height: 1.5;
    margin-bottom: 1.2rem;
    font-size: 0.95rem;
  }
  
  .event-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--dark-green);
    font-weight: 500;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  
  .event-link:hover {
    color: #053a2b;
    text-decoration: underline;
  }
  
  /* Empty State */
  .no-events-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  }
  
  .empty-state-icon {
    font-size: 3rem;
    color: var(--dark-green);
    margin-bottom: 1rem;
    opacity: 0.8;
  }
  
  .no-events-message h3 {
    color: var(--dark-green);
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
  }
  
  .no-events-message p {
    color: #666;
    margin-bottom: 1.5rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
  }
  
  .cta-button {
    display: inline-block;
    padding: 0.8rem 1.8rem;
    background: var(--dark-green);
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease, transform 0.3s ease;
  }
  
  .cta-button:hover {
    background: #053a2b;
    transform: translateY(-2px);
  }
  
  /* View All Button */
  .view-all-container {
    text-align: center;
    margin-top: 3rem;
  }
  
  .view-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 2rem;
    background: var(--dark-green);
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease, transform 0.3s ease;
  }
  
  .view-all-btn:hover {
    background: #053a2b;
    transform: translateY(-2px);
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .events-grid {
      grid-template-columns: 1fr;
    }
    
    .event-meta {
      flex-direction: column;
      gap: 0.5rem;
    }
    
    .no-events-message {
      padding: 2rem 1.5rem;
    }
  }
</style>

<!-- CONTACT FORM -->
<!-- CONTACT FORM SECTION -->
<section class="contact-section" id="contact">
  <div class="contact-container">
    <div class="contact-image">
      <img src="assets/images/contact.png" alt="Contact Us">
    </div>
    
    <?php
    if (isset($_GET['contact_status'])) {
        echo '<div class="alert ' . ($_GET['contact_status'] === 'success' ? 'alert-success' : 'alert-danger') . '" id="auto-dismiss-alert">'
             . htmlspecialchars($_GET['message'] ?? '') . '</div>';
    }
    ?>
    
    <form class="contact-form" action="index.php#contact" method="POST">
      <h2>Contact Us</h2>
      <div class="heading-line"></div>
      
      <input type="hidden" name="contact_form_submitted" value="1">
      
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" 
               value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" 
               required>
      </div>
      
      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" 
               value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>" 
               required>
      </div>
      
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" name="phone" id="phone" 
               value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" 
               required>
      </div>
      
      <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
      </div>
      
      <button type="submit" class="submit-btn">Submit Message</button>
    </form>
  </div>
  <script>
// Auto-dismiss alert after 2 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('auto-dismiss-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // Fade-out animation
        }, 2000);
    }
});
</script>
</section>

<!-- BACK TO TOP BUTTON -->
<a href="#home" class="back-to-top">
  <i class="fas fa-arrow-up"></i>
</a>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>
<!-- Loading Screen -->
    <div class="loader-wrapper" id="loader">
        <div class="loader"></div>
        <div class="loader-text">Loading CBCTY...</div>
    </div>
<script src="assets/js/script.js"></script>
<script src="admin/assets/js/loader.js"></script>
</body>
</html>