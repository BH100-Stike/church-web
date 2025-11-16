<?php
include '../includes/connect.php';
require __DIR__ . '/../includes/auth_functions.php'; // Assuming you have authentication
include 'includes/settings-crud.php';

$settingsCRUD = new SettingsCRUD();
$adminCRUD = new AdminCRUD();

// Get current admin data
$adminData = $adminCRUD->getAdminById($_SESSION['admin_id']);
$currentTheme = $settingsCRUD->getAdminTheme($_SESSION['admin_id']);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Profile update logic
    } elseif (isset($_POST['update_theme'])) {
        // Theme update logic
    } elseif (isset($_POST['change_password'])) {
        // Password change logic
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($currentTheme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="includes/settings-style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="settings-container">
        <div class="settings-sidebar">
            <div class="admin-profile-card">
                <div class="profile-picture">
                    <img src="../<?= htmlspecialchars($adminData['profile_pic'] ?? 'assets/images/default-admin.jpg') ?>" 
                         alt="Profile Picture" id="profile-preview">
                    <div class="upload-overlay">
                        <i class="bi bi-camera-fill"></i>
                        <input type="file" id="profile-upload" accept="image/*">
                    </div>
                </div>
                <h5><?= htmlspecialchars($adminData['name']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($adminData['email']) ?></p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <i class="bi bi-calendar-check"></i>
                        <span>Member since <?= date('M Y', strtotime($adminData['created_at'])) ?></span>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-activity"></i>
                        <span>Last login: <?= date('M j, g:i a', strtotime($adminData['last_login'])) ?></span>
                    </div>
                </div>
            </div>

            <nav class="settings-menu">
                <a href="#profile" class="active"><i class="bi bi-person"></i> Profile</a>
                <a href="#theme"><i class="bi bi-palette"></i> Theme</a>
                <a href="#security"><i class="bi bi-shield-lock"></i> Security</a>
                <a href="#notifications"><i class="bi bi-bell"></i> Notifications</a>
            </nav>
        </div>

        <div class="settings-content">
            <!-- Profile Settings Tab -->
            <div id="profile" class="settings-tab active">
                <h3><i class="bi bi-person"></i> Profile Settings</h3>
                <form id="profile-form" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="name" 
                                       value="<?= htmlspecialchars($adminData['name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?= htmlspecialchars($adminData['email']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Bio</label>
                        <textarea class="form-control" name="bio" rows="3"><?= htmlspecialchars($adminData['bio'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" class="form-control" name="phone" 
                                       value="<?= htmlspecialchars($adminData['phone'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Profile Visibility</label>
                                <select class="form-select" name="visibility">
                                    <option value="public" <?= ($adminData['visibility'] ?? '') === 'public' ? 'selected' : '' ?>>Public</option>
                                    <option value="private" <?= ($adminData['visibility'] ?? '') === 'private' ? 'selected' : '' ?>>Private</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="profile_pic" id="profile-pic-input" 
                           value="<?= htmlspecialchars($adminData['profile_pic'] ?? '') ?>">

                    <div class="form-footer">
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Theme Settings Tab -->
            <div id="theme" class="settings-tab">
                <h3><i class="bi bi-palette"></i> Dashboard Theme</h3>
                <form id="theme-form" method="POST">
                    <div class="theme-options">
                        <h5>Color Scheme</h5>
                        <div class="theme-colors">
                            <div class="color-option" data-theme="light">
                                <div class="color-preview light-theme">
                                    <div class="color-primary"></div>
                                    <div class="color-secondary"></div>
                                    <div class="color-accent"></div>
                                </div>
                                <span>Light</span>
                                <input type="radio" name="theme" value="light" <?= $currentTheme === 'light' ? 'checked' : '' ?>>
                            </div>
                            <div class="color-option" data-theme="dark">
                                <div class="color-preview dark-theme">
                                    <div class="color-primary"></div>
                                    <div class="color-secondary"></div>
                                    <div class="color-accent"></div>
                                </div>
                                <span>Dark</span>
                                <input type="radio" name="theme" value="dark" <?= $currentTheme === 'dark' ? 'checked' : '' ?>>
                            </div>
                            <div class="color-option" data-theme="blue">
                                <div class="color-preview blue-theme">
                                    <div class="color-primary"></div>
                                    <div class="color-secondary"></div>
                                    <div class="color-accent"></div>
                                </div>
                                <span>Ocean</span>
                                <input type="radio" name="theme" value="blue" <?= $currentTheme === 'blue' ? 'checked' : '' ?>>
                            </div>
                            <div class="color-option" data-theme="green">
                                <div class="color-preview green-theme">
                                    <div class="color-primary"></div>
                                    <div class="color-secondary"></div>
                                    <div class="color-accent"></div>
                                </div>
                                <span>Forest</span>
                                <input type="radio" name="theme" value="green" <?= $currentTheme === 'green' ? 'checked' : '' ?>>
                            </div>
                        </div>

                       <h5 class="mt-4">Accent Color</h5>
<div class="accent-colors">
    <input type="color" id="accent-color" name="accent_color" 
           value="<?= htmlspecialchars($settingsCRUD->getAccentColor($_SESSION['admin_id'])) ?>">
    <label for="accent-color">Customize</label>
</div>

<h5 class="mt-4">Layout Preferences</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Sidebar Position</label>
            <select class="form-select" name="sidebar_position">
                <option value="left" <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'sidebar_position') === 'left') ? 'selected' : '' ?>>Left</option>
                <option value="right" <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'sidebar_position') === 'right') ? 'selected' : '' ?>>Right</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Navigation Style</label>
            <select class="form-select" name="nav_style">
                <option value="expanded" <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'nav_style') === 'expanded') ? 'selected' : '' ?>>Expanded</option>
                <option value="compact" <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'nav_style') === 'compact') ? 'selected' : '' ?>>Compact</option>
            </select>
        </div>
    </div>
</div>
                    <div class="form-footer">
                        <button type="submit" name="update_theme" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Theme
                        </button>
                        <button type="button" id="reset-theme" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset to Default
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Settings Tab -->
            <div id="security" class="settings-tab">
                <h3><i class="bi bi-shield-lock"></i> Security Settings</h3>
                <form id="security-form" method="POST">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" name="new_password" required>
                                <div class="password-strength mt-2">
                                    <div class="strength-meter">
                                        <div class="strength-bar"></div>
                                    </div>
                                    <small class="strength-text">Password strength: <span>Weak</span></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                        </div>
                    </div>

                   <div class="security-options mt-4">
    <h5>Two-Factor Authentication</h5>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="2fa-toggle" 
               name="two_factor" <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'two_factor')) ? 'checked' : '' ?>>
        <label class="form-check-label" for="2fa-toggle">Enable 2FA</label>
    </div>

    <div id="2fa-setup" class="mt-3" style="display: none;">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Scan this QR code with your authenticator app
        </div>
        <div id="qrcode" class="text-center mb-3"></div>
        <div class="form-group">
            <label>Verification Code</label>
            <input type="text" class="form-control" name="2fa_code" 
                   placeholder="Enter 6-digit code">
        </div>
    </div>
</div>

<div class="form-footer">
    <button type="submit" name="change_password" class="btn btn-primary">
        <i class="bi bi-shield-check"></i> Update Security Settings
    </button>
</div>
</form>
</div>

<!-- Notification Settings Tab -->
<div id="notifications" class="settings-tab">
    <h3><i class="bi bi-bell"></i> Notification Preferences</h3>
    <form id="notifications-form">
        <div class="notification-category">
            <h5>Email Notifications</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="email-notifications" 
                       <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'email_notifications')) ? 'checked' : '' ?>>
                <label class="form-check-label" for="email-notifications">Enable Email Notifications</label>
            </div>

            <div class="notification-options mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="new-user-email" 
                           <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'new_user_email')) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="new-user-email">New user registrations</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="message-email" 
                           <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'message_email')) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="message-email">New contact messages</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="system-email" 
                           <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'system_email')) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="system-email">System updates</label>
                </div>
            </div>
        </div>

        <div class="notification-category mt-4">
            <h5>In-App Notifications</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="app-notifications" 
                       <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'app_notifications')) ? 'checked' : '' ?>>
                <label class="form-check-label" for="app-notifications">Enable In-App Notifications</label>
            </div>

            <div class="notification-options mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="activity-app" 
                           <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'activity_app')) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="activity-app">User activity</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="updates-app" 
                           <?= ($settingsCRUD->getSetting($_SESSION['admin_id'], 'updates_app')) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="updates-app">Content updates</label>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="button" id="save-notifications" class="btn btn-primary">
                <i class="bi bi-save"></i> Save Preferences
            </button>
        </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="includes/theme-changer.js"></script>
    <script src="includes/settings-scripts.js"></script>
</body>
</html>