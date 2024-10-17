<?php

require_once 'App/Main.php';

use App\Main;

$main = new Main();

$clients = $main->getAllClients();
$merchandises = $main->getAllMerchandise();
$orders = $main->getAllOrders();

?>

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
    <div class="row my-5 d-flex">
        <div class="btn-group">
            <a  href="/" class="btn btn-dark">Главная</a>
            <a href="/sqlpage.php" class="btn btn-outline-dark">SQL</a>
            <a href="/script_code.php" class="btn btn-outline-dark">Код скрипта</a>
            <a href="/task_descriprion.php" class="btn btn-outline-dark">Описание задачи</a>
        </div>
    </div>

        <div class="col-12 col-md-6 col-lg-4 mx-auto">
            <div class="card ">
                <div class="card-header">
                    Загрузка CSV файла
                </div>
                <div class="card-body">
                    <form action="App/Main.php" method="post" enctype="multipart/form-data">

                        <div class="input-group mb-3">
                            <input type="file" name="file" accept=".csv" class="form-control" id="inputGroupFile02">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-primary" type="submit">Загрузить</button>
                            <a href="App/storage/csv_example/orders.csv" class="btn btn-dark">Скачать пример файла</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <div class="row mt-3 g-3">
        <div class="col-12 col-md-12 col-lg-8">
            <div class="card m-auto">
                <div class="card-header d-flex justify-content-between">
                    Заказы
                    <div class="d-flex">
                        <form action="App/Main.php" method="post">
                            <button class="btn btn-outline-danger" name="clear_orders">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                        <button class="btn btn-outline-dark ms-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="bi bi-stars"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 500px ; overflow-y: auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Товар</th>
                                <th scope="col">Клиент</th>
                                <th scope="col">Комментарий</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Дата заказа</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <th scope="row"><?= $order['id'] ?></th>
                                    <td><?= $order['item'] ?></td>
                                    <td><?= $order['client'] ?></td>
                                    <td><?= $order['comment'] ?></td>
                                    <td><?= $order['status'] ?></td>
                                    <td><?= $order['order_date'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-4">
            <div class="col">
                <div class="card m-auto h-100">
                    <div class="card-header">
                        Товары
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 195px ; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Наименование</th>
                                    <th scope="col">Стоимость</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($merchandises as $merchandise): ?>
                                    <tr>
                                        <th scope="row"><?= $merchandise['id'] ?></th>
                                        <td><?= $merchandise['name'] ?></td>
                                        <td><?= $merchandise['price'] ?> руб.</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card h-auto">
                    <div class="card-header">
                        Клиенты
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 230px ; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Имя</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <th scope="row"><?= $client['id'] ?></th>
                                        <td><?= $client['name'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Генерация заказов</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="App/Main.php" method="post">
                            <div class="d-flex">
                                <input class="form-control w-25" type="number" id="order_count" name="order_count"
                                       min="1"
                                       required>
                                <button class="btn btn-outline-dark m-auto" type="submit">Сгенерировать заказы</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <p class="text-secondary">Вы можете сгенерировать случайные заказы. Задайте необходимое
                            количество и нажмите кнопку.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>