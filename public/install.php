<?php
session_start();

$step = (int) ($_GET['step'] ?? 1);

$requirements = [
    'php_version' => PHP_VERSION_ID >= 80000,
    'extensions' => [
        'pdo' => extension_loaded('pdo'),
        'mysqli' => extension_loaded('mysqli'),
        'curl' => extension_loaded('curl'),
        'openssl' => extension_loaded('openssl'),
        'mbstring' => extension_loaded('mbstring'),
        'gd' => extension_loaded('gd'),
    ],
    'writable' => [
        'config' => is_writable(__DIR__ . '/../config'),
        'storage' => is_writable(__DIR__ . '/../'),
    ],
];

function render_header()
{
    echo '<!doctype html><html><head><title>Apartment Installer</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sakura.css/css/sakura.css"></head><body>';
}

function render_footer()
{
    echo '</body></html>';
}

if (file_exists(__DIR__ . '/../config/config.php')) {
    render_header();
    echo '<h2>Installer disabled</h2><p>The application is already installed. Remove install.php for security.</p>';
    render_footer();
    exit;
}

render_header();

echo '<h1>Apartment Management Installer</h1>';

if ($step === 1) {
    echo '<h2>Step 1: Requirements</h2><ul>';
    echo '<li>PHP 8+: ' . ($requirements['php_version'] ? 'OK' : 'Upgrade required') . '</li>';
    foreach ($requirements['extensions'] as $ext => $ok) {
        echo '<li>' . $ext . ': ' . ($ok ? 'OK' : 'Missing') . '</li>';
    }
    foreach ($requirements['writable'] as $path => $ok) {
        echo '<li>' . $path . ' writable: ' . ($ok ? 'Yes' : 'No') . '</li>';
    }
    echo '</ul><a href="?step=2">Continue</a>';
}

if ($step === 2) {
    echo '<h2>Step 2: Database</h2>';
    echo '<form method="post" action="?step=3">';
    echo '<label>Host <input required name="db_host"></label><br>';
    echo '<label>Name <input required name="db_name"></label><br>';
    echo '<label>User <input required name="db_user"></label><br>';
    echo '<label>Password <input type="password" name="db_pass"></label><br>';
    echo '<button type="submit">Test & Save</button>';
    echo '</form>';
}

if ($step === 3 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dsn = sprintf('mysql:host=%s;dbname=%s', $_POST['db_host'], $_POST['db_name']);
    try {
        new PDO($dsn, $_POST['db_user'], $_POST['db_pass']);
        $_SESSION['db'] = $_POST;
        echo '<p>Connection successful.</p>';
        echo '<form method="post" action="?step=4">';
        echo '<label>Society Name <input required name="society_name"></label><br>';
        echo '<label>Address <input required name="address"></label><br>';
        echo '<label>City <input required name="city"></label><br>';
        echo '<label>State <input required name="state"></label><br>';
        echo '<label>Country <input required name="country" value="India"></label><br>';
        echo '<label>Currency <input required name="currency" value="INR"></label><br>';
        echo '<button type="submit">Next</button>';
        echo '</form>';
    } catch (Throwable $e) {
        echo '<p>Connection failed: ' . htmlspecialchars($e->getMessage()) . '</p><a href="?step=2">Back</a>';
    }
}

if ($step === 4 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['society'] = $_POST;
    echo '<h2>Step 4: Admin User</h2>';
    echo '<form method="post" action="?step=5">';
    echo '<label>Name <input required name="name"></label><br>';
    echo '<label>Email <input required name="email"></label><br>';
    echo '<label>Mobile <input required name="mobile"></label><br>';
    echo '<label>Password <input required type="password" name="password"></label><br>';
    echo '<button type="submit">Create Admin</button>';
    echo '</form>';
}

if ($step === 5 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = $_SESSION['db'];
    $society = $_SESSION['society'];
    $config = "<?php\nreturn [\n  'db' => [\n    'host' => '{$db['db_host']}',\n    'database' => '{$db['db_name']}',\n    'username' => '{$db['db_user']}',\n    'password' => '{$db['db_pass']}',\n  ],\n  'app' => ['currency' => '{$society['currency']}'],\n  'razorpay' => ['razorpay_key' => '', 'razorpay_secret' => '', 'webhook_secret' => ''],\n];";
    file_put_contents(__DIR__ . '/../config/config.php', $config);

    $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $db['db_host'], $db['db_name']), $db['db_user'], $db['db_pass']);
    $schema = file_get_contents(__DIR__ . '/../database/migrations.sql');
    $pdo->exec($schema);

    $stmt = $pdo->prepare('INSERT INTO societies (name, address, city, state, country, currency) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$society['society_name'], $society['address'], $society['city'], $society['state'], $society['country'], $society['currency']]);
    $societyId = (int) $pdo->lastInsertId();

    $userStmt = $pdo->prepare('INSERT INTO users (society_id, name, email, mobile, password, role) VALUES (?, ?, ?, ?, ?, ?)');
    $userStmt->execute([$societyId, $_POST['name'], $_POST['email'], $_POST['mobile'], password_hash($_POST['password'], PASSWORD_BCRYPT), 'admin']);

    echo '<h3>Installation Complete</h3><p><a href="/">Go to login</a></p><p>Please remove public/install.php for security.</p>';
}

render_footer();
