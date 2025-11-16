<?php
require __DIR__ . '/../../includes/connect.php';
require __DIR__ . '/../includes/crud_functions.php';
include '../../includes/fetch.php';

class EventsCRUD extends CRUD {
    public function __construct() {
        parent::__construct('events', ['title', 'description', 'image_url', 'date', 'time', 'location', 'is_featured']);
    }
    
    public function getUpcomingEvents() {
        $sql = "SELECT * FROM events 
                WHERE date >= CURDATE()
                ORDER BY date ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPastEvents() {
        $sql = "SELECT * FROM events 
                WHERE date < CURDATE()
                ORDER BY date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$eventsCRUD = new EventsCRUD();
$upcomingEvents = $eventsCRUD->getUpcomingEvents();
$pastEvents = $eventsCRUD->getPastEvents();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Church Events</title>
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
            <h2><i class="bi bi-calendar-event"></i> Events Management</h2>
            <a href="events-create.php" class="add-btn">
                <i class="bi bi-plus-circle"></i> Add New
            </a>
        </div>

        <?php include '../includes/alerts.php'; ?>

        <div class="hero-card">
            <div class="hero-card-body">
                <h3 class="section-title"><i class="bi bi-calendar-check"></i> Upcoming Events</h3>
                <div class="table-container">
                    <table class="hero-table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-image"></i></th>
                                <th><i class="bi bi-calendar-event"></i> Event</th>
                                <th><i class="bi bi-calendar-date"></i> Date</th>
                                <th><i class="bi bi-clock"></i> Time</th>
                                <th><i class="bi bi-geo-alt"></i> Location</th>
                                <th class="action-btns"><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcomingEvents as $event): ?>
                            <tr>
                                <td>
                                    <img src="../../<?= htmlspecialchars($event['image_url'] ?? 'assets/images/events/default.jpg') ?>" 
                                         alt="<?= htmlspecialchars($event['title']) ?>" 
                                         class="hero-img">
                                </td>
                                <td>
                                    <?= htmlspecialchars($event['title']) ?>
                                    <?php if (isset($event['is_featured']) && $event['is_featured']): ?>
                                    <span class="featured-badge"><i class="bi bi-star-fill"></i> Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M j, Y', strtotime($event['date'])) ?></td>
                                <td><?= isset($event['time']) ? date('g:i A', strtotime($event['time'])) : 'N/A' ?></td>
                                <td><?= htmlspecialchars($event['location'] ?? 'N/A') ?></td>
                                <td class="action-btns">
                                    <a href="events-update.php?id=<?= $event['id'] ?>" class="edit-btn">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" onclick="showDeleteModal(<?= $event['id'] ?>)" class="delete-btn">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($upcomingEvents)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No upcoming events found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <h3 class="section-title"><i class="bi bi-clock-history"></i> Past Events</h3>
                <div class="table-container">
                    <table class="hero-table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-image"></i></th>
                                <th><i class="bi bi-calendar-event"></i> Event</th>
                                <th><i class="bi bi-calendar-date"></i> Date</th>
                                <th><i class="bi bi-clock"></i> Time</th>
                                <th><i class="bi bi-geo-alt"></i> Location</th>
                                <th class="action-btns"><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pastEvents as $event): ?>
                            <tr>
                                <td>
                                    <img src="../../<?= htmlspecialchars($event['image_url'] ?? 'assets/images/events/default.jpg') ?>" 
                                         alt="<?= htmlspecialchars($event['title']) ?>" 
                                         class="hero-img">
                                </td>
                                <td><?= htmlspecialchars($event['title']) ?></td>
                                <td><?= date('M j, Y', strtotime($event['date'])) ?></td>
                                <td><?= isset($event['time']) ? date('g:i A', strtotime($event['time'])) : 'N/A' ?></td>
                                <td><?= htmlspecialchars($event['location'] ?? 'N/A') ?></td>
                                <td class="action-btns">
                                    <a href="events-update.php?id=<?= $event['id'] ?>" class="edit-btn">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" onclick="showDeleteModal(<?= $event['id'] ?>)" class="delete-btn">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($pastEvents)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No past events found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="delete-modal">
        <div class="modal-content">
            <h3><i class="bi bi-exclamation-triangle"></i> Confirm Deletion</h3>
            <p>Are you sure you want to delete this event?</p>
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
                
                fetch(`events-delete.php?id=${currentIdToDelete}`, {
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