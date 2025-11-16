<?php
require_once __DIR__ . '/../../includes/connect.php';
require_once __DIR__ . '/../includes/crud_functions.php';

class OrganizationsCRUD extends CRUD {
    public function __construct() {
        parent::__construct('organizations', ['name', 'description', 'image_url', 'website_url', 'contact_email']);
    }
}

$orgCRUD = new OrganizationsCRUD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $orgCRUD->sanitizeInput($_POST['name']),
        'description' => $orgCRUD->sanitizeInput($_POST['description']),
        'website_url' => $orgCRUD->sanitizeInput($_POST['website_url']),
        'contact_email' => $orgCRUD->sanitizeInput($_POST['contact_email']),
        'image_url' => '' // Will be updated after upload
    ];

    // Handle file upload
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'org_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $data['image_url'] = 'assets/uploads/' . $filename;
        }
    }

    if ($orgCRUD->create($data)) {
        $_SESSION['success'] = "Organization added successfully!";
        header('Location: organizations.php');
    } else {
        $_SESSION['error'] = "Failed to add organization";
        header('Location: organizations.php');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Organization</title>
     <script src="../assets/js/theme.js"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="organizations.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Organizations
            </a>
            <h2>Add New Organization</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <div class="form-group">
                        <label for="name">Organization Name*</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description*</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="website_url">Website URL</label>
                        <input type="url" id="website_url" name="website_url">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Logo*</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                        <div class="form-hint">Recommended size: 400x400 pixels (JPG, PNG)</div>
                        <img id="image-preview" class="image-preview">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="create" class="submit-btn">
                            <i class="bi bi-save"></i> Save Organization
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