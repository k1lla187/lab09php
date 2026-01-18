<?php
require_once __DIR__ . '/../core/Database.php';

class StudentModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM students ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create($data)
    {
        $sql = "INSERT INTO students (code, full_name, email, dob) VALUES (:code, :full_name, :email, :dob)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':code' => $data['code'],
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':dob' => $data['dob'] ?: null,
        ]);
        return $this->find($this->db->lastInsertId());
    }

    public function update($id, $data)
    {
        $sql = "UPDATE students SET code = :code, full_name = :full_name, email = :email, dob = :dob WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':code' => $data['code'],
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':dob' => $data['dob'] ?: null,
            ':id' => $id,
        ]);
        return $this->find($id);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM students WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // helper to check unique constraints (code/email) excluding optional id
    public function existsByCode($code, $excludeId = null)
    {
        $sql = "SELECT id FROM students WHERE code = :code";
        if ($excludeId) $sql .= " AND id <> :id";
        $stmt = $this->db->prepare($sql);
        $params = [':code' => $code];
        if ($excludeId) $params[':id'] = $excludeId;
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public function existsByEmail($email, $excludeId = null)
    {
        $sql = "SELECT id FROM students WHERE email = :email";
        if ($excludeId) $sql .= " AND id <> :id";
        $stmt = $this->db->prepare($sql);
        $params = [':email' => $email];
        if ($excludeId) $params[':id'] = $excludeId;
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }
}