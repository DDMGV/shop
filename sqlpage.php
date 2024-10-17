<?php

require_once 'App/Main.php';

use App\Main;

$main = new Main();

$first_query_result = $main->getClientNameWhoNotBaySevenDays();
$second_query_result = $main->getClientNameWhoBayMostOfAll();
$third_query_result = $main->getClientNameWhoPayMostOfAll();
$fourth_query_result = $main->getMerchandiseNameWhoNotHaveCompleteStatus();
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
    <div class="row mt-5 d-flex">
        <div class="btn-group">
            <a  href="/" class="btn btn-outline-dark">Главная</a>
            <a href="/sqlpage.php" class="btn btn-dark">SQL</a>
            <a href="/script_code.php" class="btn btn-outline-dark">Код скрипта</a>
            <a href="/task_descriprion.php" class="btn btn-outline-dark">Описание задачи</a>
        </div>
    </div>

    <div class="row mt-5 d-flex g-3">
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">SQL</span> | Имена всех клиентов, которые не делали заказы
                    в последние 7 дней.
                </div>
                <div class="card-body">
                    <b class="text-primary">SELECT</b> c.name <br>
                    <b class="text-primary">FROM</b> clients <b class="text-primary">AS</b> c <br>
                    <b class="text-primary">LEFT JOIN</b> orders <b class="text-primary">AS</b> o <b
                            class="text-primary">ON</b> c.id = o.customer_id <b class="text-primary">AND</b>
                    o.order_date >= <b class="text-primary">date(</b><em class="text-success">'now', '-7 days'</em><b
                            class="text-primary">)</b><br>
                    <b class="text-primary">WHERE</b> o.id <b class="text-primary">IS NULL</b>
                </div>
                <div class="card-footer">
                    <em>Индекс для ускорения соединения по customer_id</em><br>
                    <code>CREATE INDEX idx_orders_customer_id ON orders(customer_id);</code><br>
                    <em>Индекс для ускорения фильтрации по дате</em><br>
                    <code>CREATE INDEX idx_orders_order_date ON orders(order_date);</code>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">Результат</span> | Имена всех клиентов, которые не делали
                    заказы в последние 7 дней.
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 185px ; overflow-y: auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Имя</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($first_query_result as $result): ?>
                                <tr>
                                    <td><?= $result['name'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row d-flex g-3">
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">SQL</span> | Имена 5 клиентов, которые сделали больше всего
                    заказов в магазине.
                </div>
                <div class="card-body">
                    <b class="text-primary">SELECT</b> c.name, <b class="text-primary">COUNT(</b>o.id<b
                            class="text-primary">) AS</b> order_count <br>
                    <b class="text-primary">FROM</b> clients <b class="text-primary">AS</b> c <br>
                    <b class="text-primary">JOIN</b> orders <b class="text-primary">AS</b> o <b
                            class="text-primary">ON</b>
                    c.id = o.customer_id <br>
                    <b class="text-primary">GROUP BY</b> c.id <br>
                    <b class="text-primary">ORDER BY</b> order_count <b class="text-primary">DESC</b> <br>
                    <b class="text-primary">LIMIT</b> 5;
                </div>
                <div class="card-footer">
                    <em>Индекс для ускорения соединения по customer_id</em><br>
                    <code>CREATE INDEX idx_orders_customer_id ON orders(customer_id);</code><br>

                    <em>Индекс для ускорения группировки по клиентам</em><br>
                    <code>CREATE INDEX idx_clients_id ON clients(id);</code><br>

                    <em>Композитный индекс для оптимизации соединения и группировки</em><br>
                    <code>CREATE INDEX idx_orders_customer_id_id ON orders(customer_id, id);</code>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">Результат</span> | Имена 5 клиентов, которые сделали больше
                    всего заказов в магазине.
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 275px ; overflow-y: auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Имя</th>
                                <th scope="col">Количество заказов</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($second_query_result as $result): ?>
                                <tr>
                                    <td><?= $result['name'] ?></td>
                                    <td><?= $result['order_count'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row d-flex g-3">
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">SQL</span> | Имена 10 клиентов, которые сделали заказы на
                    наибольшую сумму.
                </div>
                <div class="card-body">
                    <b class="text-primary">SELECT</b> c.name, <b class="text-primary">SUM(</b>m.price<b
                            class="text-primary">) AS </b>total_spent, <b class="text-primary">COUNT(</b>m.id<b
                            class="text-primary">) AS</b> count_bay<br>
                    <b class="text-primary">FROM</b> clients <b class="text-primary">AS</b> c<br>
                    <b class="text-primary">JOIN</b> orders <b class="text-primary">AS</b> o <b
                            class="text-primary">ON</b> c.id = o.customer_id<br>
                    <b class="text-primary">JOIN</b> merchandise <b class="text-primary">AS</b> m <b
                            class="text-primary">ON</b> o.item_id = m.id<br>
                    <b class="text-primary">GROUP BY</b> c.id<br>
                    <b class="text-primary">ORDER BY</b> total_spent <b class="text-primary">DESC</b><br>
                    <b class="text-primary">LIMIT</b> 10;
                </div>
                <div class="card-footer">
                    <em>Индекс для ускорения соединения между таблицами clients и orders</em><br>
                    <code>CREATE INDEX idx_orders_customer_id ON orders(customer_id);</code><br>

                    <em>Индекс для ускорения соединение между таблицами orders и merchandise, когда ищутся товары для
                        каждого заказа.</em><br>
                    <code>CREATE INDEX idx_orders_item_id ON orders(item_id);</code><br>

                    <em>Индекс для эффективного поиска цен товаров.</em><br>
                    <code>CREATE INDEX idx_merchandise_id ON merchandise(id);</code>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">Результат</span> | Имена 10 клиентов, которые сделали
                    заказы на наибольшую сумму.
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 330px ; overflow-y: auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Имя</th>
                                <th scope="col">Сумма заказов</th>
                                <th scope="col">Количество заказов</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($third_query_result as $result): ?>
                                <tr>
                                    <td><?= $result['name'] ?></td>
                                    <td><?= $result['total_spent'] ?> руб.</td>
                                    <td><?= $result['count_bay'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row d-flex g-3">
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">SQL</span> | Наименование всех товаров, по которым не было
                    доставленных заказов.
                </div>
                <div class="card-body">
                    <b class="text-primary">SELECT</b> m.name <br>
                    <b class="text-primary">FROM</b> merchandise <b class="text-primary">AS</b> m<br>
                    <b class="text-primary">LEFT JOIN</b> orders <b class="text-primary">AS</b> o <b
                            class="text-primary">ON</b> m.id = o.item_id <b class="text-primary">AND</b> o.status = <em
                            class="text-success">'complete'</em><br>
                    <b class="text-primary">WHERE</b> o.id <b class="text-primary">IS NULL</b>;
                </div>
                <div class="card-footer">
                    <em>Индекс для ускорения соединение между таблицами merchandise и orders</em><br>
                    <code>CREATE INDEX idx_orders_item_id ON orders(item_id);</code><br>

                    <em>Индекс для ускорения фильтрации заказов по статусу 'complete'.</em><br>
                    <code>CREATE INDEX idx_orders_status ON orders(status);</code><br>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <span class="badge text-bg-warning fs-6">Результат</span> | Наименование всех товаров, по которым не
                    было доставленных заказов.
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 185px ; overflow-y: auto;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Наименование</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($fourth_query_result as $result): ?>
                                <tr>
                                    <td><?= $result['name'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="card mb-5">
        <div class="card-header">
            <span class="badge text-bg-warning fs-6">Индексы</span> | Итоги
        </div>
        <div class="card-body">
        <h6>1. Индекс на customer_id в таблице orders</h6>
        <code>CREATE INDEX idx_orders_customer_id ON orders(customer_id);</code>
        <p>В запросах где соединяются таблицы clients и orders по полю customer_id. Индекс на customer_id позволяет
            быстрее находить заказы, относящиеся к каждому клиенту.</p>

        <h6>2. Индекс на item_id в таблице orders</h6>
        <code>CREATE INDEX idx_orders_item_id ON orders(item_id);</code>
        <p>При соединении таблиц orders и merchandise по полю item_id, этот индекс позволяет быстро находить заказы для
            каждого товара. Это ускоряет запросы, в которых фильтруется или вычисляется информация по товарам.</p>

        <h6>3. Индекс на id в таблице merchandise</h6>
        <code>CREATE INDEX idx_merchandise_id ON merchandise(id);</code>
        <p>Поле id в таблице merchandise является первичным ключом, который используется для соединения таблиц и
            идентификации каждого товара. Наличие индекса позволяет ускорить поиск товаров и их цен при работе с
            таблицей orders.</p>

        <h6>4. Индекс на status в таблице orders</h6>
        <code>CREATE INDEX idx_orders_status ON orders(status);</code>
        <p>Когда запрос фильтрует заказы по статусу, индекс на поле status ускоряет эту операцию. Индекс позволяет базе
            данных быстро находить заказы с определенным статусом, вместо того чтобы сканировать всю таблицу.</p>

        <h6>5. Индекс на id в таблице clients</h6>
        <code>CREATE INDEX idx_clients_id ON clients(id);</code>
        <p>Поле id в таблице clients должно быть индексировано. Это ускоряет операции группировки и агрегации по
            клиентам, особенно когда подсчитывается количество заказов или общая сумма.</p>

        <h6>6. Композитный индекс на customer_id и id в таблице orders</h6>
        <code>CREATE INDEX idx_orders_customer_id_id ON orders(customer_id, id);</code>
        <p>Композитный индекс на двух полях одновременно ускоряет и операцию соединения по customer_id, и группировку по
            заказам. Когда часто выполняются запросы, использующие обе эти операции, как в случае с подсчетом количества
            заказов на клиента или сумм по каждому клиенту.</p>
    </div>
</div>

</body>
</html>
