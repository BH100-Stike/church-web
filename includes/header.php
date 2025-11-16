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
  <!-- Navigation links from database -->
  <div class="nav-links">
   
      <?php foreach ($navigationLinks as $link): ?>
        <a href="<?= htmlspecialchars($link['link_url']) ?>">
          
            <?= htmlspecialchars($link['link_name']) ?>
          </a>
          
      <?php endforeach; ?>
      
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