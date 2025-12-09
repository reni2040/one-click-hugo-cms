<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class User extends Model
{
    protected string $table = 'users';

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (society_id, name, email, mobile, password, role) VALUES (:society_id, :name, :email, :mobile, :password, :role)");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
