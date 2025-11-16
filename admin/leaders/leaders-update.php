<?php
require __DIR__ . '/../includes/crud_functions.php';

class LeadersCRUD extends CRUD {
    public function __construct() {
        parent::__construct('leaders', ['name', 'position', 'image_url', 'bio', 'social_facebook', 'social_twitter']);
    }
}

$leadersCRUD = new LeadersCRUD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $leadersCRUD->sanitizeInput($_POST['name']),
        'position' => $leadersCRUD->sanitizeInput($_POST['position']),
        'bio' => $leadersCRUD->sanitizeInput($_POST['bio']),
        'social_facebook' => $leadersCRUD->sanitizeInput($_POST['social_facebook']),
        'social_twitter' => $leadersCRUD->sanitizeInput($_POST['social_twitter']),
        'image_url' => $leadersCRUD->sanitizeInput($_POST['existing_image']) // Using existing_image from hidden field
    ];

    if ($leadersCRUD->update($_POST['id'], $data)) {
        $_SESSION['success'] = "Leader updated successfully!";
        header('Location: leaders.php');
    } else {
        $_SESSION['error'] = "Failed to update leader";
        header('Location: leaders.php');
    }
    exit;
}

// If not POST request, show the edit form
$leader = $leadersCRUD->getById($_GET['id'] ?? 0);
if (!$leader) {
    header('Location: leaders.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Church Leader</title>
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
<a href="leaders.php" class="back-btn">
<i class="bi bi-arrow-left"></i> Back to Leaders
</a>
<h2><i class="bi bi-person-gear"></i> Edit Leader</h2>
<div class="spacer"></div>
</div>

<?php include '../includes/alerts.php'; ?>

<div class="hero-card">
<div class="hero-card-body">
<form action="" method="POST" enctype="multipart/form-data" class="hero-form">
<input type="hidden" name="id" value="<?= $leader['id'] ?>">
<input type="hidden" name="existing_image" value="<?= $leader['image_url'] ?>">

<div class="form-group">
    <label for="name"><i class="bi bi-person"></i> Name</label>
    <input type="text" id="name" name="name" class="form-control" 
            value="<?= htmlspecialchars($leader['name']) ?>" required>
</div>

<div class="form-group">
    <label for="position"><i class="bi bi-briefcase"></i> Position</label>
    <input type="text" id="position" name="position" class="form-control" 
            value="<?= htmlspecialchars($leader['position']) ?>" required>
</div>

<div class="form-group">
    <label for="bio"><i class="bi bi-card-text"></i> Bio</label>
    <textarea id="bio" name="bio" class="form-control" rows="5" required><?= 
        htmlspecialchars($leader['bio']) 
    ?></textarea>
</div>

<div class="form-group">
    <label><i class="bi bi-image"></i> Current Image</label>
    <div class="current-image">
        <img src="../../<?= htmlspecialchars($leader['image_url']) ?>" 
                alt="<?= htmlspecialchars($leader['name']) ?>" 
                class="hero-img">
    </div>
    
    <label for="image"><i class="bi bi-upload"></i> Change Image</label>
    <input type="file" id="image" name="image" accept="image/*">
    <div class="form-hint">Leave blank to keep current image</div>
</div>

<div class="form-group">
    <label for="social_facebook"><i class="bi bi-facebook"></i> Facebook URL</label>
    <input type="url" id="social_facebook" name="social_facebook" class="form-control" 
            value="<?= htmlspecialchars($leader['social_facebook']) ?>">
</div>

<div class="form-group">
    <label for="social_twitter"><i class="bi bi-twitter"></i> Twitter URL</label>
    <input type="url" id="social_twitter" name="social_twitter" class="form-control" 
            value="<?= htmlspecialchars($leader['social_twitter']) ?>">
</div>

<div class="form-actions">
    <button type="submit" name="update" class="submit-btn">
        <i class="bi bi-save"></i> Update Leader
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