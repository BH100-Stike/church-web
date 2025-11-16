<?php
require_once __DIR__ . '/../../includes/connect.php';
require_once __DIR__ . '/../includes/crud_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leadersCRUD = new CRUD('leaders', ['name', 'position', 'image_url', 'bio', 'social_facebook', 'social_twitter']);
    
    $data = [
        'name' => $leadersCRUD->sanitizeInput($_POST['name']),
        'position' => $leadersCRUD->sanitizeInput($_POST['position']),
        'bio' => $leadersCRUD->sanitizeInput($_POST['bio']),
        'social_facebook' => $leadersCRUD->sanitizeInput($_POST['social_facebook']),
        'social_twitter' => $leadersCRUD->sanitizeInput($_POST['social_twitter']),
        'image_url' => '' // Will be updated after upload
    ];

    // Handle file upload
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'leader_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $data['image_url'] = 'assets/uploads/' . $filename;
        }
    }

    if ($leadersCRUD->create($data)) {
        $_SESSION['success'] = "Leader added successfully!";
        header('Location: leaders.php');
    } else {
        $_SESSION['error'] = "Failed to add leader";
        header('Location: leaders.php');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Church Leader</title>
     <script src="../assets/js/theme.js"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="leaders.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Leaders
            </a>
            <h2>Add New Leader</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position*</label>
                        <input type="text" id="position" name="position" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">Bio*</label>
                        <textarea id="bio" name="bio" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image*</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                        <div class="form-hint">Recommended size: 400x400 pixels (JPG, PNG)</div>
                        <img id="image-preview" class="image-preview">
                    </div>
                    
                    <div class="form-group">
                        <label for="social_facebook">Facebook URL</label>
                        <input type="url" id="social_facebook" name="social_facebook">
                    </div>
                    
                    <div class="form-group">
                        <label for="social_twitter">Twitter URL</label>
                        <input type="url" id="social_twitter" name="social_twitter">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="create" class="submit-btn">
                            <i class="bi bi-save"></i> Save Leader
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