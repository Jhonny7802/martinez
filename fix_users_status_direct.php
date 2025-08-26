<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Database configuration
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'martinez',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    echo "Checking if status column exists in users table...\n";
    
    // Check if column exists
    $columns = Capsule::select("SHOW COLUMNS FROM users LIKE 'status'");
    
    if (empty($columns)) {
        echo "Status column does not exist. Adding it...\n";
        
        // Add status column
        Capsule::statement("ALTER TABLE users ADD COLUMN status VARCHAR(20) DEFAULT 'active' AFTER email_verified_at");
        
        echo "Status column added successfully.\n";
    } else {
        echo "Status column already exists.\n";
    }
    
    // Update existing users to have active status
    $updated = Capsule::table('users')
        ->whereNull('status')
        ->orWhere('status', '')
        ->update(['status' => 'active']);
    
    echo "Updated $updated users with active status.\n";
    
    // Verify the column
    $users = Capsule::table('users')->select('id', 'name', 'status')->get();
    echo "Users with status:\n";
    foreach ($users as $user) {
        echo "- ID: {$user->id}, Name: {$user->name}, Status: {$user->status}\n";
    }
    
    echo "\nUsers table status column fix completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
