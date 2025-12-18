<?php

try {
    $pdo = new PDO(
        'mysql:host=database.gps.network;port=3306;dbname=APP_BPA_HRD;charset=utf8mb4',
        'APIGPS',
        'f2989fd3b6a455890fc8caee8c2f0b5a',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );

    echo 'DB2 CONNECTED SUCCESSFULLY';

} catch (PDOException $e) {
    echo 'DB2 CONNECTION FAILED: ' . $e->getMessage();
}
