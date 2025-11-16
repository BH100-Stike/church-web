<?php
require_once __DIR__ . '/../../includes/connect.php';
require_once __DIR__ . '/../includes/crud_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventsCRUD = new CRUD('events', ['title', 'description', 'image_url', 'date', 'location', 'is_featured']);
    
    $data = [
        'title' => $eventsCRUD->sanitizeInput($_POST['title']),
        'description' => $eventsCRUD->sanitizeInput($_POST['description']),
        'date' => $eventsCRUD->sanitizeInput($_POST['date']),
        'location' => $eventsCRUD->sanitizeInput($_POST['location']),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'image_url' => '' // Will be updated after upload
    ];

    // Handle file upload
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'event_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $data['image_url'] = 'assets/uploads/' . $filename;
        }
    }

    if ($eventsCRUD->create($data)) {
        $_SESSION['success'] = "Event added successfully!";
        header('Location: events.php');
    } else {
        $_SESSION['error'] = "Failed to add event";
        header('Location: events.php');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event</title>
     <script src="../assets/js/theme.js"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="events.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Events
            </a>
            <h2>Add New Event</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <div class="form-group">
                        <label for="title">Event Title*</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description*</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date*</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location*</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Event Image*</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                        <div class="form-hint">Recommended size: 800x600 pixels (JPG, PNG)</div>
                        <img id="image-preview" class="image-preview">
                    </div>
                    
                    <div class="form-group">
                        <label for="is_featured">
                            <input type="checkbox" id="is_featured" name="is_featured">
                            Feature this event
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="create" class="submit-btn">
                            <i class="bi bi-save"></i> Create Event
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
        
        // Set minimum date to today
        document.getElementById('date').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>