<?php

namespace App\Services;

use App\Models\Flat;
use App\Models\Invoice;
use DateTime;

class BillingService
{
    public function __construct(private Flat $flats, private Invoice $invoices)
    {
    }

    public function generateMonthly(int $societyId, float $defaultAmount, string $dueDate, string $lateFeeRule = ''): void
    {
        $month = (int) date('n');
        $year = (int) date('Y');
        $due = new DateTime($dueDate);

        foreach ($this->flats->allBySociety($societyId) as $flat) {
            $this->invoices->create([
                'society_id' => $societyId,
                'flat_id' => $flat['id'],
                'month' => $month,
                'year' => $year,
                'amount' => $defaultAmount,
                'due_date' => $due->format('Y-m-d'),
                'status' => 'unpaid',
                'late_fee_rule' => $lateFeeRule,
            ]);
        }
    }
}
