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
            <a href="/script_code.php" class="btn btn-outline-dark">Код скрипта</a>
            <a href="/task_descriprion.php" class="btn btn-dark">Описание задачи</a>
        </div>
    </div>
    <div class="row mt-5 d-flex">
        <div class="card">
            <div class="card-body">
                <pre>
                Есть база данных для хранения информации о клиентах, товарах и заказах со
                следующей структурой:

                clients (id, name) - ID клиента и его имя

                merchandise (id, name) - ID товара и его наименование

                orders (id, item_id, customer_id, comment, status, order_date) - ID заказа, ID товара, ID
                клиента, комментарий клиента, статус заказа (‘new’, ‘complete’), дата заказа (то есть
                структура предполагает, что один заказ - это один товар)

                Необходимо:
                1. Написать скрипт, который получает на вход текстовый файл с данными о
                заказах (разделитель “;”) вида: ID товара;ID клиента;Комментарий к заказу и
                загружает содержимое в описанную выше структуру БД, при этом все
                невалидные строки должны записываться в отдельный файл. Использование
                сторонних решений / библиотек нежелательно.

                2. Написать SQL запросы, возвращающие набор данных, соответствующий
                следующим условиям:
                    a. Выбрать имена (name) всех клиентов, которые не делали заказы в последние 7 дней.

                    b. Выбрать имена (name) 5 клиентов, которые сделали больше всего заказов в магазине.

                    c. Выбрать имена (name) 10 клиентов, которые сделали заказы на наибольшую сумму.

                    d. Выбрать имена (name) всех товаров, по которым не было доставленных заказов (со статусом “complete”).

                3. Описать, какие бы вы создали индексы для оптимизации скорости работы
                запросов из п.2 и почему

                В качестве решения тестового задания принимается архив, содержащий:
                - скрипт импорта заказов из п.1, набор тестовых данных для скрипта, соответствующий
                описанному формату, файл с SQL запросами и перечнем необходимых индексов (с
                обоснованием).
                </pre>
            </div>
        </div>
    </div>
</div>
</body>
</html>