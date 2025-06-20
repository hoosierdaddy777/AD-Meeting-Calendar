<?php
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Bootstrap
require 'bootstrap.php';

// 3) Load .env
require_once UTILS_PATH . '/envSetter.util.php';

// 4) Database config from env
$pgConfig = [
    'host' => $_ENV['PG_HOST'],
    'port' => $_ENV['PG_PORT'],
    'db'   => $_ENV['PG_DB'],
    'user' => $_ENV['PG_USER'],
    'pass' => $_ENV['PG_PASS'],
];

// 5) Connect to PostgreSQL
echo "🔌 Connecting to PostgreSQL database…\n";

$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
try {
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connected to {$pgConfig['db']}.\n";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage() . "\n");
}

// 6) Truncate existing tables
echo "🧹 Truncating tables…\n";
$tables = ['meeting_users', 'meetings', 'tasks', 'users'];
foreach ($tables as $table) {
    echo " - Truncating {$table}…\n";
    try {
        $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
    } catch (PDOException $e) {
        echo "❌ Error truncating {$table}: " . $e->getMessage() . "\n";
    }
}

// 7) Apply schema files
echo "📦 Applying schemas…\n";

$schemaFiles = [
    'database/user.model.sql',
    'database/meeting.model.sql',
    'database/meeting_users.model.sql',
    'database/tasks.model.sql',
];

foreach ($schemaFiles as $file) {
    echo "📄 Applying {$file}…\n";
    $sql = file_get_contents($file);

    if ($sql === false) {
        echo "❌ Could not read {$file}\n";
        continue;
    }

    try {
        $pdo->exec($sql);
        echo "✅ Schema from {$file} applied.\n";
    } catch (PDOException $e) {
        echo "❌ Error in {$file}: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 Done! Database is reset and ready to use.\n";
