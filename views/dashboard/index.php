<section>
    <h2>Welcome, <?= htmlspecialchars($user['name'] ?? '') ?></h2>
    <p>Role: <?= htmlspecialchars($user['role'] ?? '') ?> | Society #<?= htmlspecialchars($user['society_id'] ?? '') ?></p>
</section>

<section>
    <h3>Latest Invoices</h3>
    <?php if (!empty($invoices)): ?>
        <table>
            <tr><th>Month</th><th>Amount</th><th>Status</th></tr>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?= htmlspecialchars($invoice['month'] . '/' . $invoice['year']) ?></td>
                    <td><?= htmlspecialchars($invoice['amount']) ?></td>
                    <td><?= htmlspecialchars($invoice['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No invoices yet.</p>
    <?php endif; ?>
</section>

<section>
    <h3>Open Tickets</h3>
    <?php if (!empty($tickets)): ?>
        <ul>
            <?php foreach ($tickets as $ticket): ?>
                <li><?= htmlspecialchars($ticket['category']) ?> - <?= htmlspecialchars($ticket['status']) ?>: <?= htmlspecialchars($ticket['description']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tickets.</p>
    <?php endif; ?>
</section>
