<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Payment extends Model
{
    protected string $table = 'payments';

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (society_id, invoice_id, user_id, amount, method, status, reference, razorpay_order_id, razorpay_payment_id) VALUES (:society_id, :invoice_id, :user_id, :amount, :method, :status, :reference, :razorpay_order_id, :razorpay_payment_id)");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }
}
