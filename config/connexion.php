<?php


require('config.php');

$dsn = "mysql:host={$settings['host']};dbname={$settings['dbname']};port={$settings['port']};";

try {
    $database = new \PDO($dsn , $settings['username'], $settings['password']);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('error on db ' . $e->getMessage());
}

