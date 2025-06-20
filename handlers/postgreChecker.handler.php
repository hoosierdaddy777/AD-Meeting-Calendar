<?php
require_once __DIR__ . '/../util/envSetter.util.php';

$host = getenv('POSTGRES_HOST');
$port = getenv('POSTGRES_PORT');
$dbname = getenv('POSTGRES_DB');
$user = getenv('POSTGRES_USER');
$password = getenv('POSTGRES_PASSWORD');

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo "❌ Connection Failed: " . pg_last_error();
} else {
    echo "✅ PostgreSQL Connection";
    pg_close($conn);
}
