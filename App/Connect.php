<?php

namespace App;

use PDO;
use PDOException;

class Connect
{
    public function DBConnect()
    {
        try {
            $database_path = __DIR__.'/database/database.sqlite';
            $pdo = new PDO("sqlite:" . $database_path);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e) {
            echo "Ошибка подключения к базе данных: " . $e->getMessage();
            exit;
        }
    }
}