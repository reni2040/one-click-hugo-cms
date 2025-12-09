<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Core\Database;

class DashboardController extends Controller
{
    public function __construct(private Database $db)
    {
    }

    public function index(): void
    {
        $user = Auth::user();
        if (! $user) {
            header('Location: /login');
            return;
        }

        $invoiceModel = new Invoice($this->db);
        $ticketModel = new Ticket($this->db);

        $invoices = $user['role'] === 'owner' ? $invoiceModel->byFlat((int) $user['flat_id']) : [];
        $tickets = $ticketModel->allBySociety((int) $user['society_id']);

        $this->view('dashboard/index', compact('user', 'invoices', 'tickets'));
    }
}
