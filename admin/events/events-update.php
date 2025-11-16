<?php
require __DIR__ . '/../includes/crud_functions.php';

class EventsCRUD extends CRUD {
    public function __construct() {
        parent::__construct('events', ['title', 'description', 'image_url', 'date', 'time', 'location', 'is_featured']);
    }
}

$eventsCRUD = new EventsCRUD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $eventsCRUD->sanitizeInput($_POST['title'] ?? ''),
        'description' => $eventsCRUD->sanitizeInput($_POST['description'] ?? ''),
        'date' => $eventsCRUD->sanitizeInput($_POST['date'] ?? ''),
        'time' => $eventsCRUD->sanitizeInput($_POST['time'] ?? ''),
        'location' => $eventsCRUD->sanitizeInput($_POST['location'] ?? ''),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'image_url' => $eventsCRUD->sanitizeInput($_POST['existing_image'] ?? '')
    ];

    // Handle file upload if new image was provided
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'event_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $data['image_url'] = 'assets/uploads/' . $filename;
            // Optionally delete old image file here if it's not the default
            $oldImage = $_POST['existing_image'] ?? '';
            if ($oldImage && $oldImage !== 'assets/images/events/default.jpg' && file_exists(__DIR__ . '/../../' . $oldImage)) {
                unlink(__DIR__ . '/../../' . $oldImage);
            }
        }
    }

    if ($eventsCRUD->update($_POST['id'] ?? 0, $data)) {
        $_SESSION['success'] = "Event updated successfully!";
        header('Location: events.php');
        exit;
    } else {
        $_SESSION['error'] = "Failed to update event";
        header('Location: events-update.php?id=' . ($_POST['id'] ?? 0));
        exit;
    }
}

// If not POST request, show the edit form
$event = $eventsCRUD->getById($_GET['id'] ?? 0);
if (!$event) {
    $_SESSION['error'] = "Event not found";
    header('Location: events.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <script src="../assets/js/theme.js"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .hero-form {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="date"],
        input[type="time"],
        textarea,
        select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .current-image {
            margin: 1rem 0;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-hint {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.3rem;
        }
        
        .submit-btn {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s ease;
        }
        
        .submit-btn:hover {
            background: var(--primary-dark);
        }
        
        .form-actions {
            text-align: right;
            margin-top: 2rem;
        }
        
        input[type="checkbox"] {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="events.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Events
            </a>
            <h2>Edit Event</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" enctype="multipart/form-data" class="hero-form">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($event['id'] ?? '') ?>">
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($event['image_url'] ?? '') ?>">
                    
                    <div class="form-group">
                        <label for="title">Event Title*</label>
                        <input type="text" id="title" name="title" 
                               value="<?= htmlspecialchars($event['title'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description*</label>
                        <textarea id="description" name="description" rows="5" required><?= 
                            htmlspecialchars($event['description'] ?? '') 
                        ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date*</label>
                        <input type="date" id="date" name="date" 
                               value="<?= htmlspecialchars($event['date'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="time">Time*</label>
                        <input type="time" id="time" name="time" 
                               value="<?= htmlspecialchars($event['time'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location*</label>
                        <input type="text" id="location" name="location"
                               value="<?= htmlspecialchars($event['location'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="bi bi-image"></i> Current Image</label>
                        <div class="current-image">
                            <img src="../<?= htmlspecialchars($event['image_url'] ?? 'assets/images/events/default.jpg') ?>" 
                                 alt="<?= htmlspecialchars($event['title'] ?? 'Event Image') ?>" 
                                 class="image-preview">
                        </div>
                        
                        <label for="image">Change Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <div class="form-hint">Leave blank to keep current image</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_featured">
                            <input type="checkbox" id="is_featured" name="is_featured" 
                                   <?= (isset($event['is_featured']) && $event['is_featured']) ? 'checked' : '' ?>>
                            Feature this event
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update" class="submit-btn">
                            <i class="bi bi-save"></i> Update Event
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
            const preview = document.querySelector('.current-image .image-preview');
            
            if (file && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>