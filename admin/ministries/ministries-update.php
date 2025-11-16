<?php
require __DIR__ . '/../includes/crud_functions.php';

class MinistriesCRUD extends CRUD {
    public function __construct() {
        parent::__construct('ministries', ['title', 'description', 'image_url']);
    }
}

$ministriesCRUD = new MinistriesCRUD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $ministriesCRUD->sanitizeInput($_POST['title']),
        'description' => $ministriesCRUD->sanitizeInput($_POST['description']),
        'image_url' => $ministriesCRUD->sanitizeInput($_POST['existing_image']) // Using existing_image from hidden field
    ];

    if ($ministriesCRUD->update($_POST['id'], $data)) {
        $_SESSION['success'] = "Ministry updated successfully!";
        header('Location: ministries.php');
    } else {
        $_SESSION['error'] = "Failed to update ministry";
        header('Location: ministries.php');
    }
    exit;
}

// If not POST request, show the edit form
$ministry = $ministriesCRUD->getById($_GET['id'] ?? 0);
if (!$ministry) {
    header('Location: ministries.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ministry</title>
     <script src="../assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .hero-img {
            max-width: 200px;
            height: auto;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="ministries.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Ministries
            </a>
            <h2><i class="bi bi-people-fill"></i> Edit Ministry</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <input type="hidden" name="id" value="<?= $ministry['id'] ?>">
                    <input type="hidden" name="existing_image" value="<?= $ministry['image_url'] ?>">
                    
                    <div class="form-group">
                        <label for="title"><i class="bi bi-tag"></i> Ministry Title</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?= htmlspecialchars($ministry['title']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description"><i class="bi bi-card-text"></i> Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5" required><?= 
                            htmlspecialchars($ministry['description']) 
                        ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="bi bi-image"></i> Current Image</label>
                        <div class="current-image">
                            <img src="../../<?= htmlspecialchars($ministry['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($ministry['title']) ?>" 
                                 class="hero-img">
                        </div>
                        
                        <label for="image"><i class="bi bi-upload"></i> Change Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <div class="form-hint">Leave blank to keep current image</div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update" class="submit-btn">
                            <i class="bi bi-save"></i> Update Ministry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Simple image preview functionality
        document.getElementById('image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.createElement('img');
            preview.className = 'hero-img';
            preview.style.display = 'block';
            preview.style.marginTop = '10px';
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    e.target.closest('.form-group').appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>