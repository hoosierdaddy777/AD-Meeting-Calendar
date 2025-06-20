<?php
require_once __DIR__ . '/../util/envSetter.util.php';

$host = getenv('MONGO_HOST');
$port = getenv('MONGO_PORT');
$dbname = getenv('MONGO_DB');
$username = getenv('MONGO_ROOT_USER');
$password = getenv('MONGO_ROOT_PASSWORD');

$uri = "mongodb://$username:$password@$host:$port";

try {
    $mongo = new MongoDB\Driver\Manager($uri);
    // Attempt a ping to test the connection
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $mongo->executeCommand($dbname, $command);
    echo "✅ Connected to MongoDB successfully.";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "❌ MongoDB connection failed: " . $e->getMessage();
}
