<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Notice extends Model
{
    protected string $table = 'notices';

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (society_id, title, body, visibility, attachment, is_pinned) VALUES (:society_id, :title, :body, :visibility, :attachment, :is_pinned)");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }
}
