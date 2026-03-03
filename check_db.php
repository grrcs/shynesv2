<?php
try {
    $db = new PDO('pgsql:host=viaduct.proxy.rlwy.net;port=34426;dbname=railway;sslmode=prefer', 'postgres', 'uVDmKYgcfskcOAEgxwsYGIXrNeeHyWXa');
    $stmt = $db->query('SELECT id, email, password FROM users');
    if ($stmt) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            echo "No users found in database.\n";
        }
        foreach ($rows as $row) {
            echo $row['email'] . " => " . $row['password'] . "\n";
        }
    } else {
        var_dump($db->errorInfo());
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
