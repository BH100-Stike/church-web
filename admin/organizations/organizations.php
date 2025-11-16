<?php
require __DIR__ . '/../../includes/connect.php';
require __DIR__ . '/../includes/crud_functions.php';

class OrganizationsCRUD extends CRUD {
    public function __construct() {
        parent::__construct('organizations', ['name', 'description', 'image_url', 'website_url', 'contact_email']);
    }
}

$orgCRUD = new OrganizationsCRUD();
$organizations = $orgCRUD->getAll();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Partner Organizations</title>
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
<h2><i class="bi bi-building"></i> Partner Organizations</h2>
<a href="organizations-create.php" class="add-btn">
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
    <th><i class="bi bi-image"></i> Logo</th>
    <th><i class="bi bi-building"></i> Name</th>
    <th><i class="bi bi-card-text"></i> Description</th>
    <th><i class="bi bi-globe"></i> Website</th>
    <th class="action-btns"><i class="bi bi-gear"></i> Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($organizations as $org): ?>
<tr>
    <td><?= htmlspecialchars($org['id']) ?></td>
    <td>
        <img src="../../<?= htmlspecialchars($org['image_url']) ?>" 
                alt="<?= htmlspecialchars($org['name']) ?>" 
                class="hero-img">
    </td>
    <td> <?= htmlspecialchars($org['name']) ?></td>
    <td> <?= strlen($org['description']) > 50 
        ? substr(htmlspecialchars($org['description']), 0, 50) . '...' 
        : htmlspecialchars($org['description']) 
    ?></td>
    <td>
        <?php if ($org['website_url']): ?>
        <a href="<?= htmlspecialchars($org['website_url']) ?>" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Visit
        </a>
        <?php else: ?>
        <span class="text-muted">None</span>
        <?php endif; ?>
    </td>
    <td class="action-btns">
        <a href="organizations-update.php?id=<?= $org['id'] ?>" class="edit-btn">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <button type="button" onclick="showDeleteModal(<?= $org['id'] ?>)" class="delete-btn">
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
            <p>Are you sure you want to delete this organization?</p>
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
                
                fetch(`organizations-delete.php?id=${currentIdToDelete}`, {
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