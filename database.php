<?php

$host = '127.0.0.1';
$dbName = 'clearerio_test_db';
$username = 'root';
$password = '';

try {

    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    //Connect
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // customers
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $pdo->exec($createTableSql);
    echo "Table 'customers' is ready.\n";

    $customers = [
        ['Le Gia Le', 'legiale@example.com'],
        ['Nguyen Van Bin', 'binbin@example.com'],
        ['Nguyen Le Anh Vy', 'anhvy@example.com'],
    ];

    $insertSql = "INSERT IGNORE INTO customers (name, email) VALUES (:name, :email)";
    $stmt = $pdo->prepare($insertSql);

    foreach ($customers as [$name, $email]) {
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
        ]);
    }

    // Fetch all customers
    $query = $pdo->query("SELECT id, name, email, created_at FROM customers ORDER BY id ASC");
    $allCustomers = $query->fetchAll(PDO::FETCH_ASSOC);

    echo "All Customers:\n";
    echo str_repeat('-', 50) . "\n";

    foreach ($allCustomers as $customer) {
        echo "ID: {$customer['id']}\n";
        echo "Name: {$customer['name']}\n";
        echo "Email: {$customer['email']}\n";
        echo "Created At: {$customer['created_at']}\n";
        echo str_repeat('-', 50) . "\n";
    }

    //bonus: get by email
    function getCustomerByEmail(PDO $pdo, string $email): ?array
    {
        $sql = "SELECT id, name, email, created_at FROM customers WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        return $customer ?: null;
    }

    echo "\nSearch result for legiale@example.com:\n";
    $customer = getCustomerByEmail($pdo, 'legiale@example.com');

    if ($customer) {
        echo "ID: {$customer['id']}\n";
        echo "Name: {$customer['name']}\n";
        echo "Email: {$customer['email']}\n";
        echo "Created At: {$customer['created_at']}\n";
    } else {
        echo "Customer not found.\n";
    }
} catch (PDOException $e) {
    die('Database error: ' . $e->getMessage() . "\n");
}
