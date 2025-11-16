<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../includes/connect.php';

$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT name, profile_image, email FROM admin_users WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();


$profile_image = !empty($admin['profile_image']) ? $admin['profile_image'] : 'assets/admin-avatar.jpg';

$heroCount = $pdo->query("SELECT COUNT(*) FROM hero_section")->fetchColumn();
$leadersCount = $pdo->query("SELECT COUNT(*) FROM leaders")->fetchColumn();
$eventsCount = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$ministriesCount = $pdo->query("SELECT COUNT(*) FROM ministries")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBCTY Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>
    <!-- Loading Screen -->
    <div class="loader-wrapper" id="loader">
        <div class="loader"></div>
        <div class="loader-text">Loading Dashboard...</div>
    </div>

    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>CBCTY Admin</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="#"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="hero/hero.php"><i class="bi bi-image"></i> Hero Section</a></li>
                <li><a href="aboutus/aboutus.php"><i class="bi bi-info-circle"></i> About Us</a></li>
                <li><a href="leaders/leaders.php"><i class="bi bi-people"></i> Church Leaders</a></li>
                <li><a href="ministries/ministries.php"><i class="bi bi-collection"></i> Ministries</a></li>
                <li><a href="organizations/organizations.php"><i class="bi bi-building"></i> Organizations</a></li>
                <li><a href="events/events.php"><i class="bi bi-calendar-event"></i> Events</a></li>
                <li><a href="navigation/navigation.php"><i class="bi bi-link-45deg"></i> Navigation</a></li>
                <li><a href="messages/messages.php"><i class="bi bi-envelope"></i> Messages</a></li>
                <li><a href="#" id="settingsButton"><i class="bi bi-gear"></i> Settings</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="top-bar">
               <!-- search bar -->
<div class="search-box">
    <input type="text" id="search-input" placeholder="Search..." autocomplete="off">
    <button id="search-button"><i class="bi bi-search"></i></button>
    <div id="search-results" class="search-results"></div>
</div>
 <!--search bar ends  -->
                <div class="user-profile">
                    <a href="#"><img   src="<?= htmlspecialchars($profile_image) ?>" alt="<?= htmlspecialchars($admin['name'] ?? 'Admin') ?>"></a>
                    <span><?= htmlspecialchars($admin['name'] ?? 'Admin User') ?></span>
                </div>
            </div>

            <div class="content-header">
                <h2>Dashboard </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <div class="stats-cards">
            <a href="hero/hero.php"><div class="stat-card bg-primary">
                    <div class="stat-icon">
                        <i class="bi bi-image"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $heroCount ?></h3>
                        <p >Hero Slides</p>
                       
                    </div>
                </div> </a>

               <a href="leaders/leaders.php"><div class="stat-card bg-success">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $leadersCount ?></h3>
                        <p>Church Leaders</p>
                        
                    </div>
                </div></a>

                <a href="events/events.php"><div class="stat-card bg-warning">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="stat-info"> 
                        <h3><?= $eventsCount ?></h3>
                        <p>Upcoming Events</p>
                        
                    </div>
                </div></a>

               <a href="ministries/ministries.php"><div class="stat-card bg-info">
                    <div class="stat-icon">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $ministriesCount ?></h3>
                        <p>Ministries</p>
                    </div>
                </div>
            </a>

            </div>

<?php
// Fetch recent activities from database
$activities_query = "SELECT * FROM activities ORDER BY created_at DESC LIMIT 5";
$activities_result = $conn->query($activities_query);

// Determine icon and color based on activity type
function get_activity_icon($type) {
    switch($type) {
        case 'update':
            return ['bi-check-circle-fill', 'text-success'];
        case 'create':
            return ['bi-plus-circle-fill', 'text-primary'];
        case 'edit':
            return ['bi-pencil-fill', 'text-warning'];
        case 'delete':
            return ['bi-trash-fill', 'text-danger'];
        default:
            return ['bi-info-circle-fill', 'text-info'];
    }
}
?>

<div class="recent-activities">
    <div class="card">
        <div class="card-header">
            <h4>Recent Activities</h4>
        </div>
        <div class="card-body">
            <ul class="activity-list">
                <?php if ($activities_result->num_rows > 0): ?>
                    <?php while($activity = $activities_result->fetch_assoc()): ?>
                        <?php 
                            list($icon, $color) = get_activity_icon($activity['activity_type']);
                            $time_ago = time_elapsed_string($activity['created_at']);
                        ?>
                        <li>
                            <i class="bi <?= $icon ?> <?= $color ?>"></i>
                            <span><?= htmlspecialchars($activity['description']) ?></span>
                            <small><?= $time_ago ?></small>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No recent activities found</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php
// Helper function to display time as "X hours ago"
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
    </div>
</div>
<!-- Settings Modal -->
<div class="settings-modal" id="settingsModal">
    <div class="modal-content">
        <h3><i class="bi bi-gear-fill"></i> ADMIN SETTINGS</h3>
        
        <div class="settings-options">
            <!-- Theme Toggle -->
             <div class="setting-item">
    <label for="themeToggle">Dark Mode</label>
    <label class="theme-toggle">
        <input type="checkbox" id="themeToggle" onchange="toggleTheme()">
        <span class="slider"></span>
    </label>
</div>
<!-- GLOBAL TOGGLER -->
<div class="setting-item">
    <label for="globalThemeToggle">Global Theme (All Pages)</label>
    <label class="theme-toggle global-theme-toggle">
        <input type="checkbox" id="globalThemeToggle" onchange="toggleGlobalTheme()">
        <span class="slider"></span>
    </label>
</div>   
            
            <!-- Notifications -->
            <div class="setting-item">
                <label for="notifications">Notifications</label>
                <label class="cyber-toggle">
                    <input type="checkbox" id="notifications" checked>
                    <span class="slider"></span>
                </label>
            </div>
            
            <!-- Profile Settings -->
            <div class="setting-item profile-settings">
                <div class="profile-header">
                    <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile" class="profile-pic">
                    <div class="profile-info">
                        <h4><?= htmlspecialchars($admin['name'] ?? 'Admin') ?></h4>
                        <span class="role">Administrator</span>
                    </div>
                </div>
                <button class="cyber-btn small" id="editProfileBtn">
                    <i class="bi bi-pencil-square"></i> Edit Profile
                </button>
            </div>
            
            <!-- Account Security -->
            <div class="setting-item">
                <label>Account Security</label>
                <button class="cyber-btn small" id="changePasswordBtn">
                    <i class="bi bi-shield-lock"></i> Change Password
                </button>
            </div>
            
            <!-- Dashboard Layout -->
            <div class="setting-item">
                <label for="dashboardLayout">Dashboard Layout</label>
                <select id="dashboardLayout" class="cyber-select">
                    <option value="default">Default</option>
                    <option value="compact">Compact</option>
                    <option value="detailed">Detailed</option>
                </select>
            </div>
            
            <!-- Data Preferences -->
            <div class="setting-item">
                <label for="dataRefresh">Data Refresh Rate</label>
                <select id="dataRefresh" class="cyber-select">
                    <option value="30">30 seconds</option>
                    <option value="60" selected>1 minute</option>
                    <option value="300">5 minutes</option>
                    <option value="900">15 minutes</option>
                </select>
            </div>
        </div>
        
        <div class="modal-actions">
            <button class="save-btn">
                <i class="bi bi-check-lg"></i> Save Changes
            </button>
            <button class="cancel-btn" id="closeSettings">
                <i class="bi bi-x-lg"></i> Close
            </button>
        </div>
    </div>
</div>

<!-- Profile Edit Modal -->
<div class="edit-profile-modal" id="editProfileModal">
    <div class="modal-content">
        <h3><i class="bi bi-person-gear"></i> EDIT PROFILE</h3>
        
        <form id="profileForm">
            <div class="form-group">
                <label for="profileName">Full Name</label>
                <input type="text" id="profileName" value="<?= htmlspecialchars($admin['name'] ?? '') ?>" class="cyber-input">
            </div>
            
            <div class="form-group">
                <label for="profileEmail">Email</label>
                <input type="email" id="profileEmail" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" class="cyber-input">
            </div>
            
            <div class="form-group">
                <label for="profileImage">Profile Image</label>
                <div class="image-upload">
                    <img src="<?= htmlspecialchars($profile_image) ?>" id="profileImage" alt="Current Profile">
                    <label for="profileImageUpload" class="cyber-btn small">
                        <i class="bi bi-upload"></i> Upload New
                    </label>
                    <input type="file" id="profileImageUpload" accept="image/*" style="display: none;">
                </div>
            </div>
            
            <div class="form-group">
                <label for="profileBio">Bio</label>
                <textarea id="profileBio" class="cyber-textarea" rows="3">Senior Administrator</textarea>
            </div>
        </form>
        
        <div class="modal-actions">
            <button class="cancel-btn" id="cancelProfileEdit">
                <i class="bi bi-x-lg"></i> Cancel
            </button>
            <button class="save-btn">
                <i class="bi bi-check-lg"></i> Save Profile
            </button>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div class="password-modal" id="passwordModal">
    <div class="modal-content">
        <h3><i class="bi bi-shield-lock"></i> CHANGE PASSWORD</h3>
        
        <form id="passwordForm">
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <div class="password-input">
                    <input type="password" id="currentPassword" class="cyber-input">
                    <button type="button" class="toggle-password" data-target="currentPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <div class="password-input">
                    <input type="password" id="newPassword" class="cyber-input">
                    <button type="button" class="toggle-password" data-target="newPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="strength-meter"></div>
                    <span class="strength-text">Weak</span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <div class="password-input">
                    <input type="password" id="confirmPassword" class="cyber-input">
                    <button type="button" class="toggle-password" data-target="confirmPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div id="passwordMatch" class="password-feedback"></div>
            </div>
        </form>
        
        <div class="modal-actions">
            <button class="cancel-btn" id="cancelPasswordChange">
                <i class="bi bi-x-lg"></i> Cancel
            </button>
            <button class="save-btn" id="savePassword">
                <i class="bi bi-check-lg"></i> Update Password
            </button>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/loader.js"></script>
    <script src="assets/js/search.js"></script>
    <script src="assets/js/theme.js"></script>
</body>
</html>