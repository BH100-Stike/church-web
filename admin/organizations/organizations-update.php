<?php
require __DIR__ . '/../includes/crud_functions.php';

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
        'image_url' => $orgCRUD->sanitizeInput($_POST['existing_image']) // Using existing_image from hidden field
    ];

    if ($orgCRUD->update($_POST['id'], $data)) {
        $_SESSION['success'] = "Organization updated successfully!";
        header('Location: organizations.php');
    } else {
        $_SESSION['error'] = "Failed to update organization";
        header('Location: organizations.php');
    }
    exit;
}

// If not POST request, show the edit form
$organization = $orgCRUD->getById($_GET['id'] ?? 0);
if (!$organization) {
    header('Location: organizations.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Organization</title>
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
            <h2>Edit Organization</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <input type="hidden" name="id" value="<?= $organization['id'] ?>">
                    <input type="hidden" name="existing_image" value="<?= $organization['image_url'] ?>">
                    
                    <div class="form-group">
                        <label for="name">Organization Name*</label>
                        <input type="text" id="name" name="name" 
                               value="<?= htmlspecialchars($organization['name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description*</label>
                        <textarea id="description" name="description" rows="5" required><?= 
                            htmlspecialchars($organization['description']) 
                        ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="website_url">Website URL</label>
                        <input type="url" id="website_url" name="website_url"
                               value="<?= htmlspecialchars($organization['website_url']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email"
                               value="<?= htmlspecialchars($organization['contact_email']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Current Logo</label>
                        <div class="current-image">
                            <img src="../../<?= htmlspecialchars($organization['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($organization['name']) ?>" 
                                 class="image-preview">
                        </div>
                        
                        <label for="image">Change Logo</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <div class="form-hint">Leave blank to keep current logo</div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update" class="submit-btn">
                            <i class="bi bi-save"></i> Update Organization
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.createElement('img');
            preview.className = 'image-preview';
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