<?php

namespace App\Services;

class NotificationService
{
    public function send(int $userId, string $title, string $message, string $channel = 'in-app'): void
    {
        // Placeholder for DB insert and email/SMS integrations.
        error_log("Notify {$userId}: {$title} - {$message} via {$channel}");
    }
}
