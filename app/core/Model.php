<?php

namespace App\Core;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;

    public function __construct(Database $database)
    {
        $this->db = $database->pdo();
    }
}
