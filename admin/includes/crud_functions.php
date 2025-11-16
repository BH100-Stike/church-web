<?php
require __DIR__ . '/../../includes/connect.php';

class CRUD {
    protected $pdo;
    protected $table;
    protected $allowedFields = [];

    public function __construct($table, $allowedFields) {
        global $pdo;
        $this->pdo = $pdo;
        $this->table = $table;
        $this->allowedFields = $allowedFields;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Filter data to only include allowed fields
        $filteredData = array_intersect_key($data, array_flip($this->allowedFields));
        
        $columns = implode(', ', array_keys($filteredData));
        $values = ':' . implode(', :', array_keys($filteredData));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($filteredData);
    }

    public function update($id, $data) {
        // Filter data to only include allowed fields
        $filteredData = array_intersect_key($data, array_flip($this->allowedFields));
        
        $set = [];
        foreach ($filteredData as $key => $value) {
            $set[] = "$key = :$key";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";
        $filteredData['id'] = $id;
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($filteredData);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public function validateImage($file) {
        // Add your image validation logic here
        return true;
    }
}
?>