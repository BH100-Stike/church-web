<?php
require_once __DIR__ . '/../../includes/connect.php';

class HeroCRUD {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create
    public function createHero($image_url, $title, $subtitle) {
        $stmt = $this->pdo->prepare("INSERT INTO hero_section (image_url, title, subtitle) VALUES (?, ?, ?)");
        return $stmt->execute([$image_url, $title, $subtitle]);
    }

    // Read All
    public function getAllHeroes() {
        $stmt = $this->pdo->query("SELECT * FROM hero_section ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read Single
    public function getHeroById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM hero_section WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateHero($id, $image_url, $title, $subtitle) {
        $stmt = $this->pdo->prepare("UPDATE hero_section SET image_url = ?, title = ?, subtitle = ? WHERE id = ?");
        return $stmt->execute([$image_url, $title, $subtitle, $id]);
    }

    // Delete
    public function deleteHero($id) {
        $stmt = $this->pdo->prepare("DELETE FROM hero_section WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Image Upload
    public function uploadImage($file) {
        $target_dir = "../assets/images/hero/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $imageFileType;
        $target_path = $target_dir . $new_filename;
        
        // Check if image file is actual image
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
            throw new Exception("File is not an image.");
        }
        
        // Check file size (5MB max)
        if ($file["size"] > 5000000) {
            throw new Exception("Sorry, your file is too large (max 5MB).");
        }
        
        // Allow certain file formats
        if(!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_path)) {
            return "assets/images/hero/" . $new_filename;
        } else {
            throw new Exception("Sorry, there was an error uploading your file.");
        }
    }
}

// Initialize CRUD operations
$heroCRUD = new HeroCRUD($pdo);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['create'])) {
            $image_url = $heroCRUD->uploadImage($_FILES['image']);
            $heroCRUD->createHero($image_url, $_POST['title'], $_POST['subtitle']);
            $_SESSION['success'] = "Hero item created successfully!";
        } 
        elseif (isset($_POST['update'])) {
            $image_url = $_POST['existing_image'];
            
            if (!empty($_FILES['image']['name'])) {
                $image_url = $heroCRUD->uploadImage($_FILES['image']);
            }
            
            $heroCRUD->updateHero($_POST['id'], $image_url, $_POST['title'], $_POST['subtitle']);
            $_SESSION['success'] = "Hero item updated successfully!";
        }
        
        header("Location: hero.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

?>