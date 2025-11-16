<?php
require '../../includes/connect.php';

class SettingsCRUD {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Theme settings
    public function getAdminTheme($adminId) {
        $stmt = $this->pdo->prepare("SELECT theme FROM admin_settings WHERE admin_id = ?");
        $stmt->execute([$adminId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['theme'] : 'light';
    }

    public function updateAdminTheme($adminId, $theme) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO admin_settings (admin_id, theme) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE theme = VALUES(theme)"
        );
        return $stmt->execute([$adminId, $theme]);
    }

    public function getAccentColor($adminId) {
        $stmt = $this->pdo->prepare("SELECT accent_color FROM admin_settings WHERE admin_id = ?");
        $stmt->execute([$adminId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['accent_color'] : '#4e73df';
    }

    public function updateAccentColor($adminId, $color) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO admin_settings (admin_id, accent_color) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE accent_color = VALUES(accent_color)"
        );
        return $stmt->execute([$adminId, $color]);
    }

    // General settings
    public function getSetting($adminId, $settingName) {
        $stmt = $this->pdo->prepare("SELECT $settingName FROM admin_settings WHERE admin_id = ?");
        $stmt->execute([$adminId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result[$settingName] : null;
    }

    public function updateSetting($adminId, $settingName, $value) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO admin_settings (admin_id, $settingName) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE $settingName = VALUES($settingName)"
        );
        return $stmt->execute([$adminId, $value]);
    }

    // Update multiple settings at once
    public function updateSettings($adminId, $settings) {
        $columns = [];
        $values = [$adminId];
        $updates = [];
        
        foreach ($settings as $key => $value) {
            $columns[] = $key;
            $values[] = $value;
            $updates[] = "$key = VALUES($key)";
        }
        
        $columnsStr = implode(', ', $columns);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $updatesStr = implode(', ', $updates);
        
        $sql = "INSERT INTO admin_settings (admin_id, $columnsStr) 
                VALUES (?, $placeholders)
                ON DUPLICATE KEY UPDATE $updatesStr";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }
}

class AdminCRUD {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAdminById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAdmin($id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $sql = "UPDATE admins SET " . implode(', ', $set) . " WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function verifyPassword($id, $password) {
        $stmt = $this->pdo->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        return password_verify($password, $admin['password']);
    }
}
?>