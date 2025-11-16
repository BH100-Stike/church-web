<?php
require __DIR__ . '/../../includes/connect.php';
require __DIR__ . '/../includes/crud_functions.php';

class NavigationCRUD extends CRUD {
    public function __construct() {
        parent::__construct('navigation', ['link_name', 'link_url', 'position', 'is_active']);
    }
}

$navCRUD = new NavigationCRUD();
$navItems = $navCRUD->getAll();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Navigation</title>
    <script src="../assets/js/theme.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .sortable-handle { cursor: move; }
        .nav-preview { background: #f8f9fa; border-radius: 5px; padding: 15px; }
        .nav-preview .nav-item { margin-bottom: 5px; }
    </style>
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
            <h2><i class="bi bi-list"></i> Navigation Management</h2>
            <a href="navigation-create.php" class="add-btn">
                <i class="bi bi-plus-circle"></i> Add New
            </a>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <div class="table-container">
                    <table class="hero-table" id="sortable-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;"><i class="bi bi-grip-vertical"></i></th>
                                <th><i class="bi bi-link"></i> Link Name</th>
                                <th><i class="bi bi-box-arrow-up-right"></i> URL</th>
                                <th class="action-btns"><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($navItems as $item): ?>
                            <tr data-id="<?= $item['id'] ?>">
                                <td class="sortable-handle"><i class="bi bi-grip-vertical"></i></td>
                                <td><?= htmlspecialchars($item['link_name']) ?></td>
                                <td><?= htmlspecialchars($item['link_url']) ?></td>
                                <td class="action-btns">
                                    <a href="navigation-update.php?id=<?= $item['id'] ?>" class="edit-btn">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" onclick="showDeleteModal(<?= $item['id'] ?>)" class="delete-btn">
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
            <p>Are you sure you want to delete this navigation item?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="confirm-btn"><i class="bi bi-check-circle"></i> Delete</button>
                <button id="cancelDelete" class="cancel-btn"><i class="bi bi-x-circle"></i> Cancel</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        // Make table rows sortable
        new Sortable(document.getElementById('sortable-table').getElementsByTagName('tbody')[0], {
            handle: '.sortable-handle',
            animation: 150,
            onEnd: function() {
                const rows = document.querySelectorAll('#sortable-table tbody tr');
                const order = Array.from(rows).map(row => row.getAttribute('data-id'));
                
                fetch('navigation-update-order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ order: order })
                }).then(response => {
                    if (!response.ok) {
                        console.error('Failed to update order');
                    }
                });
            }
        });

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
                
                fetch(`navigation-delete.php?id=${currentIdToDelete}`, {
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