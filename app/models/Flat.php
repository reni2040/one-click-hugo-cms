<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Flat extends Model
{
    protected string $table = 'flats';

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function allBySociety(int $societyId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE society_id = :society_id ORDER BY block, flat_number");
        $stmt->execute(['society_id' => $societyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
