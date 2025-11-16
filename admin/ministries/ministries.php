<?php
require __DIR__ . '/../../includes/connect.php';
require __DIR__ . '/../includes/crud_functions.php';

class MinistriesCRUD extends CRUD {
    public function __construct() {
        parent::__construct('ministries', ['title', 'description', 'image_url']);
    }
}

$ministriesCRUD = new MinistriesCRUD();
$ministries = $ministriesCRUD->getAll();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ministries</title>
     <script src="../assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="hero-header">
            <div>
                <a href="../dashboard.php" class="back-btn">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <h2><i class="bi bi-people-fill"></i> Ministries Management</h2>
            <a href="ministries-create.php" class="add-btn">
                <i class="bi bi-plus-circle"></i> Add New
            </a>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <div class="table-container">
                    <table class="hero-table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-image"></i> Image</th>
                                <th><i class="bi bi-tag"></i> Title</th>
                                <th><i class="bi bi-card-text"></i> Description</th>
                                <th class="action-btns"><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ministries as $ministry): ?>
                            <tr>
                                <td> <?= htmlspecialchars($ministry['id']) ?></td>
                                <td>
                                    <img src="../../<?= htmlspecialchars($ministry['image_url']) ?>" 
                                         alt="<?= htmlspecialchars($ministry['title']) ?>" 
                                         class="hero-img">
                                </td>
                                <td> <?= htmlspecialchars($ministry['title']) ?></td>
                                <td><?= strlen($ministry['description']) > 50 
                                    ? substr(htmlspecialchars($ministry['description']), 0, 50) . '...' 
                                    : htmlspecialchars($ministry['description']) 
                                ?></td>
                                <td class="action-btns">
                                    <a href="ministries-update.php?id=<?= $ministry['id'] ?>" 
                                       class="edit-btn">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" onclick="showDeleteModal(<?= $ministry['id'] ?>)" class="delete-btn">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="delete-modal">
        <div class="modal-content">
            <h3><i class="bi bi-exclamation-triangle"></i> Confirm Deletion</h3>
            <p>Are you sure you want to delete this ministry?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="confirm-btn"><i class="bi bi-check-circle"></i> Delete</button>
                <button id="cancelDelete" class="cancel-btn"><i class="bi bi-x-circle"></i> Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let currentIdToDelete = null;

        function showDeleteModal(id) {
            currentIdToDelete = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = 'none';
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (currentIdToDelete) {
                this.innerHTML = '<i class="bi bi-hourglass"></i> Deleting...';
                this.disabled = true;
                
                fetch(`ministries-delete.php?id=${currentIdToDelete}`, {
                    method: 'POST'
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        });
    </script>
</body>
</html>