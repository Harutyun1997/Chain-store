<?php
require_once 'database.php';


class  Shop extends database
{
    public static $table_name = 'shops';
    public static $db_fields = [
        'id',
        'name',
        'address',
    ];
    public $id;
    public $name;
    public $address;


    public function productsName()
    {
        $sql = "SELECT * FROM products ";
        return $this->get_sql_result($sql);
    }
}

class  Rout extends database
{
    public static $table_name = 'routes';
    public static $db_fields = [
        'id',
        'name',
    ];

    public function routes()
    {
        $sql = "SELECT   o.shop_id, r.route_id , COUNT(*) AS Asartiment_Count, o.delivery_date FROM orders o
INNER JOIN route_shop r
ON o.shop_id = r.shop_Id
GROUP BY r.shop_id,o.delivery_date ";
        return $this->get_sql_result($sql);
    }

    public function routesDate($date)
    {
        $sql = "SELECT   o.shop_id, r.route_id , COUNT(*) AS Asartiment_Count, o.delivery_date FROM orders o
INNER JOIN route_shop r
ON o.shop_id = r.shop_Id
GROUP BY r.shop_id,o.delivery_date 
HAVING  o.delivery_date  =" . $date;
        return $this->get_sql_result($sql);
    }

    public function route($number)
    {
        $sql = "SELECT   o.shop_id, r.route_id , COUNT(*) AS Asartiment_Count, o.delivery_date FROM orders o
INNER JOIN route_shop r
ON o.shop_id = r.shop_Id
GROUP BY r.shop_id,o.delivery_date 
HAVING   r.route_id  =" . $number;
        return $this->get_sql_result($sql);
    }

    public function routeDate($date, $number)
    {
        $sql = "SELECT   o.shop_id, r.route_id , COUNT(*) AS Asartiment_Count, o.delivery_date FROM orders o
INNER JOIN route_shop r
ON o.shop_id = r.shop_Id
GROUP BY r.shop_id,o.delivery_date 
HAVING  o.delivery_date  =" . $date . " AND    r.route_id =" . $number;

        return $this->get_sql_result($sql);
    }
}

class Order extends database
{
    public static $table_name = 'orders';
    public static $db_fields = [
        'shop_id',
        'product_id',
        'count',
        'delivery_date'
    ];

    public $shop_id;
    public $product_id;
    public $count;
    public $delivery_date;

    public function save()
    {

        if (!isset($this->shop_id)) {
            echo 'Set up an order';
            return false;
        }
        return parent::save();
    }

    public function orderDate($date)
    {
        $sql = " SELECT * FROM " . static::$table_name . " WHERE delivery_date = " . $date;
        return $this->get_sql_result($sql);
    }
}

$shop = new Shop();
$route = new Rout();
$ord = new Order();
$name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
    }

//else {
////    exit();
//}


    if ($name == "shops") {
        echo json_encode($shop->get_table());
    }

    if ($name == "orders") {
        echo json_encode($ord->get_table());
    }

    if ($name == "routes") {
        echo json_encode($route->routes());
    }

    $arr = json_decode($name);
    if ($arr[0] == "orders") {
        echo json_encode($ord->orderDate(json_encode($arr[1])));
    } else if ($arr[0] == "routes") {
        if (!empty($arr[1]) and !isset($arr[2])) {
            echo json_encode($route->routesDate(json_encode($arr[1])));
        }

        if (!empty($arr[1]) and !empty($arr[2])) {
            echo json_encode($route->routeDate(json_encode($arr[1]), $arr[2]));
        }
        if (empty($arr[1]) and !empty($arr[2])) {
            echo json_encode($route->route($arr[2]));
        }
    }
}
