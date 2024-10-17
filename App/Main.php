<?php

namespace App;

require_once 'Connect.php';

use PDO;

class Main
{
    private $pdo;

    public function __construct()
    {
        $connect = new Connect();
        $this->pdo = $connect->DBConnect();
    }

    public function getAllClients(): array
    {
        $sql = "SELECT * FROM clients";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMerchandise(): array
    {
        $sql = "SELECT * FROM merchandise";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllOrders(): array
    {
        $sql = "SELECT o.id, c.name AS client, m.name AS item, o.comment, o.status, o.order_date 
                FROM orders AS o 
                JOIN clients AS c ON o.customer_id = c.id 
                JOIN merchandise AS m ON o.item_id = m.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCountClients(): int
    {
        $sql = "SELECT count(id) FROM clients";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getCountMerchandises(): int
    {
        $sql = "SELECT count(id) FROM merchandise";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getClientNameWhoNotBaySevenDays(): array
    {
        $sql = "SELECT c.name
                FROM clients AS c
                LEFT JOIN orders AS o ON c.id = o.customer_id AND o.order_date >= date( 'now', '-7 days')
                WHERE o.id IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientNameWhoBayMostOfAll()
    {
        $sql = "SELECT c.name, COUNT(o.id) AS order_count
                FROM clients AS c
                JOIN orders AS o ON c.id = o.customer_id
                GROUP BY c.id
                ORDER BY order_count DESC
                LIMIT 5;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientNameWhoPayMostOfAll()
    {
        $sql = "SELECT c.name, SUM(m.price) AS total_spent, COUNT(m.id) AS count_bay
                FROM clients c
                JOIN orders o ON c.id = o.customer_id
                JOIN merchandise m ON o.item_id = m.id
                GROUP BY c.id
                ORDER BY total_spent DESC
                LIMIT 10;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMerchandiseNameWhoNotHaveCompleteStatus()
    {
        $sql = "SELECT m.name
                FROM merchandise AS m
                LEFT JOIN orders AS o ON m.id = o.item_id AND o.status = 'complete'
                WHERE o.id IS NULL;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateOrders($count)
    {
        $total_count_customers = $this->getCountClients();
        $total_count_merchandises = $this->getCountMerchandises();
        $statuses = ['new', 'complete'];
        $i = 0;

        while ($i != $count) {
            $rand_item_id = rand(1, $total_count_merchandises);
            $rand_customer_id = rand(1, $total_count_customers);
            $rand_status_key = array_rand($statuses);
            $rand_status = $statuses[$rand_status_key];
            $rand_comment = "...";

            $sql = "INSERT INTO orders (item_id, customer_id, comment, status) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$rand_item_id, $rand_customer_id, $rand_comment, $rand_status]);

            $i++;
        }
    }

    public function clearOrdersTable()
    {
        $sql = "DELETE FROM orders";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "DELETE FROM sqlite_sequence WHERE name='orders'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function isValidOrder($data)
    {
        return count($data) === 3 &&
            is_numeric($data[0]) && // ID товара
            is_numeric($data[1]);   // ID клиента
    }

    public function processCsvFile($filePath)
    {
        $handle = fopen($filePath, 'r');
        $invalidLines = [];

        while (($line = fgets($handle)) !== false) {
            $data = array_map('trim', explode(';', $line));
            $main = new Main();

            if ($main->isValidOrder($data)) {
                $sql = "INSERT INTO orders (item_id, customer_id, comment) VALUES (?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$data[0], $data[1], $data[2]]);
            } else {
                $invalidLines[] = $line;
            }
        }

        fclose($handle);

        if (!empty($invalidLines)) {
            $invalidFilePath = 'storage/invalid_orders/invalid_orders.txt';
            file_put_contents($invalidFilePath, implode("", $invalidLines));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_count']) && is_numeric($_POST['order_count'])) {
        $order_count = (int)$_POST['order_count'];

        $main = new Main();
        $main->generateOrders($order_count);

        header('Location: /');

    }

    if (isset($_POST['clear_orders'])) {
        $main = new Main();
        $main->clearOrdersTable();

        header('Location: /');
    }

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $filePath = $file['tmp_name'];
            $main = new Main();
            $main->processCsvFile($filePath);

            header('Location: /');

        } else {
            echo "Ошибка загрузки файла.";
        }
    }
}