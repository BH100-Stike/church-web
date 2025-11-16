<?php
require __DIR__ . '/../includes/crud_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $navCRUD = new CRUD('navigation', ['link_name', 'link_url', 'position', 'is_active']);
    
    $data = [
        'link_name' => $navCRUD->sanitizeInput($_POST['link_name']),
        'link_url' => $navCRUD->sanitizeInput($_POST['link_url']),
        'position' => (int)$_POST['position'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];

    if ($navCRUD->update($_POST['id'], $data)) {
        $_SESSION['success'] = "Navigation item updated successfully!";
        header('Location: navigation.php');
    } else {
        $_SESSION['error'] = "Failed to update navigation item";
        header('Location: navigation.php');
    }
    exit;
}

// Get the navigation item to edit
$navCRUD = new CRUD('navigation', []);
$navItem = $navCRUD->getById($_GET['id'] ?? 0);
if (!$navItem) {
    header('Location: navigation.php');
    exit;
}

// Get count for position dropdown
// $navCount = $navCRUD->getCount();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Navigation Item</title>
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
            <h2>Edit Navigation Item</h2>
            <div class="spacer"></div>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <form action="" method="POST" class="hero-form">
                    <input type="hidden" name="id" value="<?= $navItem['id'] ?>">
                    
                    <div class="form-group">
                        <label for="link_name">Link Name*</label>
                        <input type="text" id="link_name" name="link_name" 
                               value="<?= htmlspecialchars($navItem['link_name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="link_url">URL*</label>
                        <input type="url" id="link_url" name="link_url"
                               value="<?= htmlspecialchars($navItem['link_url']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position*</label>
                        <select id="position" name="position" required>
                            <?php for ($i = 1; $i <= $navCount; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == $navItem['position'] ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                
                    
                    <div class="form-actions">
                        <button type="submit" name="update" class="submit-btn">
                            <i class="bi bi-save"></i> Update Navigation Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>