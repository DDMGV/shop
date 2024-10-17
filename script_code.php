<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>CSV</title>
</head>
<body>
<div class="container align-items-center">
    <div class="row mt-5 d-flex">
        <div class="btn-group">
            <a href="/" class="btn btn-outline-dark">Главная</a>
            <a href="/sqlpage.php" class="btn btn-outline-dark">SQL</a>
            <a href="/script_code.php" class="btn btn-dark">Код скрипта</a>
            <a href="/task_descriprion.php" class="btn btn-outline-dark">Описание задачи</a>
        </div>
    </div>
    <div class="row mt-5 d-flex">
        <div class="card">
            <div class="card-body">
                <pre>
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
                </pre>
            </div>
        </div>
    </div>
</div>
</body>
</html>