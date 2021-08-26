<?php
require_once('DB.php');
header("X-XSS-Protection: 1; mode=block");

$dataFromClientSide = $_GET['q'];

$separator = ',';
$stringArray = $dataFromClientSide;
$newStringArray1 = explode($separator, $stringArray);

$ordersCount = [];

$productName = '';
$productDescription = '';
$productPrice = '';
$productQuantity = '';
$wholeInTotalPrice = '';
$userFirstName = '';
$userLastName = '';
$userEmail = '';

$limit = 0;

for ($i = 0; $i < count($newStringArray1); $i++) {
    array_push($ordersCount, $newStringArray1[$i]);
}

$ordersCountFiltered = [];
for ($i = 0; $i < count($ordersCount); $i++) {
    $newStringArray2 = explode('|', $ordersCount[$i]);
    for ($j = 0; $j < count($newStringArray2); $j++) {
        if ($j === 0) {
            $productName = $newStringArray2[$j];
        } elseif ($j === 1) {
            $productDescription = $newStringArray2[$j];
        } elseif ($j === 2) {
            $productPrice = $newStringArray2[$j];
        } elseif ($j === 3) {
            $productQuantity = $newStringArray2[$j];
            //echo $productQuantity;
        } elseif ($j === 4) {
            $wholeInTotalPrice = $newStringArray2[$j];
        } elseif ($j === 5) {
            $userFirstName = $newStringArray2[$j];
        } elseif ($j === 6) {
            $userLastName = $newStringArray2[$j];
        } elseif ($j === 7) {
            $userEmail = $newStringArray2[$j];
        }
    }
    echo "<br>";

    /*echo "$productName $productDescription $productPrice $productQuantity
        $wholeInTotalPrice $userFirstName $userLastName $userEmail";*/

    //MySQL update here
    //1) check, if the user already exists in DB
    $sql = DB::getInstance()->query("SELECT * FROM users WHERE
                          first_name='$userFirstName' AND
                          last_name='$userLastName' AND
                          email='$userEmail'");

    echo "<br>";

    //echo "num_rows = ".$sql->num_rows;
    if ($sql->num_rows >= 1) {
        echo "name of the user already exists";
    } else {
        //2) add new user to DB
          DB::getInstance()->query("INSERT INTO users (first_name, last_name, email)
                      VALUES ('$userFirstName','$userLastName','$userEmail')");
    }

    //3) get the user's ID
    $result = DB::getInstance()->query("SELECT * FROM users WHERE first_name='$userFirstName' AND
                          last_name='$userLastName' AND email='$userEmail'");
    echo "<br>";
    $row = $result->fetch_assoc();
    $user_id = $row["user_id"];

    //4) add user's order to "orders" table, here we have no need in checking
    //   is order already exists or not
    if ($limit === 0) {
        DB::getInstance()->query("INSERT INTO orders (user_id, sum)
                      VALUES ('$user_id','$wholeInTotalPrice')");
        $limit++;
        echo "your order successfully added to database";
    }

    //5) get the product id
    $result = DB::getInstance()->query("SELECT * FROM products WHERE 
                             name='$productName' AND
                             description='$productDescription' AND
                             price='$productPrice'");
    $row = $result->fetch_assoc();
    $productId = $row["product_id"];

    //6) get the order id
    $result = DB::getInstance()->query("SELECT * FROM orders WHERE 
                           user_id='$user_id' AND
                           sum='$wholeInTotalPrice'");
    $row = $result->fetch_assoc();
    $orderId = $row["order_id"];

    //7) add user's order to "order_products" table, here we have no
    //   need in checking is order already exists or not
    DB::getInstance()->query("INSERT INTO order_products (order_id, product_id, qty)
                  VALUES ('$orderId','$productId','$productQuantity')");
    echo "<br>";
}
