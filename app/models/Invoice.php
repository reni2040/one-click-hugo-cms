<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Invoice extends Model
{
    protected string $table = 'invoices';

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (society_id, flat_id, month, year, amount, due_date, status, late_fee_rule) VALUES (:society_id, :flat_id, :month, :year, :amount, :due_date, :status, :late_fee_rule)");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function byFlat(int $flatId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE flat_id = :flat_id ORDER BY year DESC, month DESC");
        $stmt->execute(['flat_id' => $flatId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
