<?php
require_once __DIR__ . '/../../includes/connect.php';
require_once __DIR__ . '/../includes/crud_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $navCRUD = new CRUD('navigation', ['link_name', 'link_url', 'position', 'is_active']);
    
    $data = [
        'link_name' => $navCRUD->sanitizeInput($_POST['link_name']),
        'link_url' => $navCRUD->sanitizeInput($_POST['link_url']),
        'position' => (int)$_POST['position'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];

    if ($navCRUD->create($data)) {
        $_SESSION['success'] = "Navigation item created successfully!";
        header('Location: navigation.php');
    } else {
        $_SESSION['error'] = "Failed to create navigation item";
        header('Location: navigation.php');
    }
    exit;
}

// Get count for position dropdown
//$navCount = (new CRUD('navigation', []))->getCount();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Navigation Item</title>
     <script src="../assets/js/theme.js"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <a href="navigation.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Navigation
            </a>
            <h2>Add Navigation Item</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" class="hero-form">
                    <div class="form-group">
                        <label for="link_name">Link Name*</label>
                        <input type="text" id="link_name" name="link_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="link_url">URL*</label>
                        <input type="url" id="link_url" name="link_url" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position*</label>
                        <select id="position" name="position" required>
                            <?php for ($i = 1; $i <= $navCount + 1; $i++): ?>
                                <option value="<?= $i ?>" <?= $i === ($navCount + 1) ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_active">
                            <input type="checkbox" id="is_active" name="is_active" checked>
                            Active (visible in navigation)
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="create" class="submit-btn">
                            <i class="bi bi-save"></i> Save Navigation Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>