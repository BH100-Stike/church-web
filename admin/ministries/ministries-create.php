<?php
require_once __DIR__ . '/../../includes/connect.php';
require_once __DIR__ . '/../includes/crud_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ministriesCRUD = new CRUD('ministries', ['title', 'description', 'image_url']);
    
    $data = [
        'title' => $ministriesCRUD->sanitizeInput($_POST['title']),
        'description' => $ministriesCRUD->sanitizeInput($_POST['description']),
        'image_url' => '' // Will be updated after upload
    ];

    // Handle file upload
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'ministry_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $data['image_url'] = 'assets/uploads/' . $filename;
        }
    }

    if ($ministriesCRUD->create($data)) {
        $_SESSION['success'] = "Ministry added successfully!";
        header('Location: ministries.php');
    } else {
        $_SESSION['error'] = "Failed to add ministry";
        header('Location: ministries.php');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Ministry</title>
     <script src="../assets/js/theme.js"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="ministries.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Ministries
            </a>
            <h2><i class="bi bi-plus-circle"></i> Add New Ministry</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <div class="form-group">
                        <label for="title"><i class="bi bi-tag"></i> Ministry Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description"><i class="bi bi-card-text"></i> Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image"><i class="bi bi-image"></i> Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                        <div class="form-hint">Recommended size: 800x600 pixels (JPG, PNG)</div>
                        <img id="image-preview" class="hero-img" style="display: none;">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="create" class="submit-btn">
                            <i class="bi bi-save"></i> Save Ministry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
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