<?php
$db = mysqli_connect("localhost", "Ruben455", "20power20good", "online_shop");
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

    echo "$productName $productDescription $productPrice $productQuantity
        $wholeInTotalPrice $userFirstName $userLastName $userEmail";

    //MySQL update here
    //1) check, if the user already exists in DB
    $sql = mysqli_query($db, "SELECT * FROM users WHERE
                          first_name='$userFirstName' AND
                          last_name='$userLastName' AND
                          email='$userEmail'");

    echo "<br>";
    var_dump(mysqli_num_rows($sql));
    if (mysqli_num_rows($sql) >= 1) {
        echo "name already exists";
    } else {
        //2) add new user to DB
        $query = "INSERT INTO users (first_name, last_name, email)
                      VALUES ('$userFirstName','$userLastName','$userEmail')";
        mysqli_query($db, $query);
    }

    //3) get the user's ID
    $query = "SELECT * FROM users WHERE first_name='$userFirstName' AND
                          last_name='$userLastName' AND email='$userEmail'";
    $result = mysqli_query($db, $query);
    echo "<br>";
    var_dump($result);
    $row = $result->fetch_assoc();
    $user_id = $row["user_id"];

    //4) add user's order to "orders" table, here we have no need in checking
    //   is order already exists or not
    if ($limit === 0) {
        $query = "INSERT INTO orders (user_id, sum)
                      VALUES ('$user_id','$wholeInTotalPrice')";
        mysqli_query($db, $query);
        $limit++;
    }

    //5) get the product id
    $query = "SELECT * FROM products WHERE name='$productName' AND
                             description='$productDescription' AND
                             price='$productPrice'";
    $result = mysqli_query($db, $query);
    $row = $result->fetch_assoc();
    $productId = $row["product_id"];

    //6) get the order id
    $query = "SELECT * FROM orders WHERE user_id='$user_id' AND
                           sum='$wholeInTotalPrice'";
    $result = mysqli_query($db, $query);
    $row = $result->fetch_assoc();
    $orderId = $row["order_id"];

    //7) add user's order to "order_products" table, here we have no
    //   need in checking is order already exists or not
    $query = "INSERT INTO order_products (order_id, product_id, qty)
                  VALUES ('$orderId','$productId','$productQuantity')";
    echo "<br>";
    var_dump(mysqli_query($db, $query));
}
