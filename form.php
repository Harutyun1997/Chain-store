<?php
require_once 'database.php';
require_once 'ajax.php';

$ord = new Order();
$shop = new Shop();
$route = new Rout();

// define variables and set to empty values
$nameShop = $numberProduct = $productCount = $dateOrder = "";
$nameShopErr = $numberProductErr = $productCountErr = $dateOrderErr = "";
$i = 0;
$arr = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $arr = json_decode($_POST["name"]);
    foreach ($arr as $value) {
        $value = $ord->clear_value($value);
    }
    $nameShop = $arr[0];
    $numberProduct = $arr[1];
    $productCount = $arr[2];
    $dateOrder = $arr[3];

//check there is such a shop

    foreach ($shop->get_table() as $value) {
        if ($nameShop == $value['id']) {
            $i++;
            break;
        }
    }
    if ($i == 0) {
        $nameShopErr = "Токова магазина не существует";
    }

    foreach ($shop->productsName() as $value) {
        if ($numberProduct == $value['id']) {
            $i++;
            break;
        }
    }
    if ((!empty ($nameShopErr) && $i == 0) || empty ($nameShopErr) && $i == 1) {
        $numberProductErr = "Токой  торт  не существует";
    }


    if ($productCount < 1000) {
        $i++;
    } else {
        $productCountErr = "Уменьшите пожалуйста количества торта";
    }


    $today = date('Y-m-d');

    if ($today > $dateOrder) {
        $dateOrderErr = "Попровите дату время уже прошло ";
    } else {
        $i++;
    }


    if ($i == 4) {
        $ord->shop_id = $nameShop;
        $ord->product_id = $numberProduct;
        $ord->count = $productCount;
        $ord->delivery_date = $dateOrder;
        $ord->save();
        echo 'true';
    } else {
        $error [] = $nameShopErr;
        $error [] = $numberProductErr;
        $error [] = $productCountErr;
        $error [] = $dateOrderErr;
        echo json_encode($error);
    }
}







