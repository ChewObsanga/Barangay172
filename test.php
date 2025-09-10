<?php
// Simple test page to check if PHP is working
echo "<h1>Barangay Management System - Test Page</h1>";
echo "<p>PHP is working!</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if we can connect to database
try {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? '5432';
    $dbname = $_ENV['DB_DATABASE'] ?? 'barangay_management';
    $user = $_ENV['DB_USERNAME'] ?? 'barangay_user';
    $pass = $_ENV['DB_PASSWORD'] ?? '';
    
    echo "<h2>Database Connection Test</h2>";
    echo "<p>Host: " . $host . "</p>";
    echo "<p>Port: " . $port . "</p>";
    echo "<p>Database: " . $dbname . "</p>";
    echo "<p>User: " . $user . "</p>";
    echo "<p>Password: " . (empty($pass) ? 'Not set' : 'Set') . "</p>";
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;options=--client_encoding=utf8";
    $pdo = new PDO($dsn, $user, $pass);
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Check environment variables
echo "<h2>Environment Variables</h2>";
echo "<p>APP_ENV: " . ($_ENV['APP_ENV'] ?? 'Not set') . "</p>";
echo "<p>APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? 'Not set') . "</p>";
echo "<p>APP_URL: " . ($_ENV['APP_URL'] ?? 'Not set') . "</p>";

// Check file permissions
echo "<h2>File System Check</h2>";
echo "<p>Current directory: " . getcwd() . "</p>";
echo "<p>Index.html exists: " . (file_exists('index.html') ? 'Yes' : 'No') . "</p>";
echo "<p>Includes directory exists: " . (is_dir('includes') ? 'Yes' : 'No') . "</p>";
echo "<p>Uploads directory exists: " . (is_dir('uploads') ? 'Yes' : 'No') . "</p>";
echo "<p>Uploads writable: " . (is_writable('uploads') ? 'Yes' : 'No') . "</p>";
?>
