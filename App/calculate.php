<?php

namespace App;
require_once 'Infrastructure/sdbh.php';

use sdbh\sdbh;

class Calculate
{
    public function calculate1()
    {
        $dbh = new sdbh();
        $days = $this->getPostValue('days', 0);
        $product_id = $this->getPostValue('product', 0);
        $selected_services = $this->getPostValue('services', []);

        $product = $this->getProductById($dbh, $product_id);

        if (!$product) {
            echo "Ошибка, товар не найден!";
            return;
        }

        $total_price = $this->calculateProductPrice($product, $days);
        $total_price += $this->calculateServicesPrice($selected_services, $days);

        return $total_price;
    }

    private function getPostValue($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    private function getProductById($dbh, $product_id)
    {
        $product = $dbh->make_query("SELECT * FROM a25_products WHERE ID = $product_id");

        return $product ? $product[0] : null;
    }

    private function calculateProductPrice($product, $days)
    {
        $price = $product['PRICE'];
        $tarif = $product['TARIFF'];
        $tarifs = unserialize($tarif);

        if (!is_array($tarifs)) {
            return $price * $days;
        }

        $product_price = $price;
        foreach ($tarifs as $day_count => $tarif_price) {
            if ($days >= $day_count) {
                $product_price = $tarif_price;
            }
        }

        return $product_price * $days;
    }

    private function calculateServicesPrice($selected_services, $days)
    {
        $services_price = 0;
        foreach ($selected_services as $service) {
            $services_price += (float)$service * $days;
        }

        return $services_price;
    }

    public function getCurrencyPriceValue($currency_value, $total_price)
    {
        $total_price_currency = number_format($total_price / $currency_value, 2, '.', ' ');

        return $total_price_currency;
    }

    public function getCurrencyValue($valute)
    {
        $url = "https://www.cbr-xml-daily.ru/daily_json.js";

        $ch = curl_init();
        $certificate_location = 'cacert.pem';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Ошибка запроса: ' . curl_error($ch);
            return null;
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['Valute'][$valute])) {
            return $data['Valute'][$valute]['Value'];
        } else {
            echo "Валюта $valute не найдена.";
            return null;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instance = new Calculate();
    $total_price = $instance->calculate1();

    if (!$total_price) {
        exit;
    }

    $currency_value = $instance->getCurrencyValue('CNY');

    if ($currency_value) {
        $total_price_cny = $instance->getCurrencyPriceValue($currency_value, $total_price);
    } else {
        $total_price_cny = null;
    }

    echo json_encode([
        'total_price' => $total_price,
        'total_price_cny' => $total_price_cny
    ]);
}
