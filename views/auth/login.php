<section>
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="/login">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <label>Email <input type="email" name="email" required></label><br>
        <label>Password <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
    <p><a href="/install.php">Run installer</a> if you have not configured the app.</p>
</section>
