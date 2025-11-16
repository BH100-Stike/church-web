<?php
require_once __DIR__ . '/../../includes/connect.php';
require_once __DIR__ . '/../includes/hero-crud.php'; 

if (!isset($_GET['id'])) {
    header("Location: hero.php");
    exit;
}

$hero = $heroCRUD->getHeroById($_GET['id']);
if (!$hero) {
    header("Location: hero.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Item</title>
     <script src="../assets/js/theme.js"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="hero.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Hero Section
            </a>
            <h2>Edit Hero Item</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <input type="hidden" name="id" value="<?= $hero['id'] ?>">
                    <input type="hidden" name="existing_image" value="<?= $hero['image_url'] ?>">
                    
                    <div class="form-group">
                        <label for="title">Title*</label>
                        <input type="text" id="title" name="title" 
                               value="<?= htmlspecialchars($hero['title']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitle">Subtitle*</label>
                        <textarea id="subtitle" name="subtitle" rows="3" required><?= htmlspecialchars($hero['subtitle']) ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Current Image</label>
                        <div class="image-group">
                            <img src="../../<?= $hero['image_url'] ?>" 
                                 alt="<?= htmlspecialchars($hero['title']) ?>" 
                                 class="hero-img">
                        </div>
                        
                        <label for="image">Change Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <div class="form-hint">Leave blank to keep current image (JPG, PNG, GIF)</div>
                        <img id="image-preview" class="image-preview">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update" class="submit-btn">
                            <i class="bi bi-save"></i> Update Hero Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>