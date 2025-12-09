<?php

namespace App\Services;

use App\Models\Ticket;
use App\Services\NotificationService;

class TicketService
{
    public function __construct(private Ticket $tickets, private NotificationService $notifications)
    {
    }

    public function create(array $data): int
    {
        $id = $this->tickets->create($data);
        $this->notifications->send($data['user_id'], 'Ticket created', 'Your ticket has been created', 'email-ready');
        return $id;
    }
}
