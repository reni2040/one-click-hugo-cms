<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Ticket extends Model
{
    protected string $table = 'tickets';

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (society_id, user_id, flat_id, category, priority, description, status, attachment) VALUES (:society_id, :user_id, :flat_id, :category, :priority, :description, :status, :attachment)");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function allBySociety(int $societyId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE society_id = :society_id ORDER BY created_at DESC");
        $stmt->execute(['society_id' => $societyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
