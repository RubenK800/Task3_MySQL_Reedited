<?php

$con = mysqli_connect("localhost", "Ruben455", "20power20good", "online_shop");
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//1.1) users table
$result = mysqli_query($con, "SELECT * FROM users");
$usersArray=[];
tableArrayPushData($result, $usersArray);

//1.2) products table
$result = mysqli_query($con, "SELECT * FROM products");
$productsArray=[];
tableArrayPushData($result, $productsArray);

//1.3) orders table
$result = mysqli_query($con, "SELECT * FROM orders");
$ordersArray=[];
tableArrayPushData($result, $ordersArray);

//1.4) order_products table
$result = mysqli_query($con, "SELECT * FROM order_products");
$orderProductsArray=[];
tableArrayPushData($result, $orderProductsArray);

mysqli_close($con);


function tableArrayPushData($result, &$tableArray)
{
    while ($row = $result->fetch_assoc()) {
        $str = '';
        foreach ($row as $value) {
            $str = $str . $value . '|';
        }
        $newStr = rtrim($str, "| ");
        array_push($tableArray, $newStr);
    }
}

$strForTableRow = '';

for ($i = 0; $i < count($usersArray); $i++) {
    for ($j = 0; $j < count($ordersArray); $j++) {
        $separator = '|';
        $stringArray1 = $ordersArray[$j];
        $newStringArray1 = explode($separator, $stringArray1);
        $stringArray2 = $usersArray[$i];
        $newStringArray2 = explode($separator, $stringArray2);

        if ($newStringArray1[1] === $newStringArray2[0]) {
            for ($k = 0; $k < count($orderProductsArray); $k++) {
                $stringArray1 = $orderProductsArray[$k];
                $newStringArray1 = explode($separator, $stringArray1);
                $stringArray2 = $ordersArray[$j];
                $newStringArray2 = explode($separator, $stringArray2);
                if ($newStringArray1[1] === $newStringArray2[0]) {
                    for ($l = 0; $l < count($productsArray); $l++) {
                        $stringArray1 = $productsArray[$l];
                        $newStringArray1 = explode($separator, $stringArray1);
                        $stringArray2 = $orderProductsArray[$k];
                        $newStringArray2 = explode($separator, $stringArray2);
                        $stringArray3 = $ordersArray[$j];
                        $newStringArray3 = explode($separator, $stringArray3);

                        if ($newStringArray1[0] === $newStringArray2[2] &&
                            $newStringArray3[0] == $newStringArray2[1]) {
                            $str = "$usersArray[$i] {} $ordersArray[$j] {} 
                                    $orderProductsArray[$k] {} 
                                    $productsArray[$l]";

                            $usersArrayExplode = explode($separator,
                                $usersArray[$i]);
                            $ordersArrayExplode = explode($separator,
                                $ordersArray[$j]);
                            $orderProductsArrayExplode = explode($separator,
                                $orderProductsArray[$k]);
                            $productsArrayExplode = explode($separator,
                                $productsArray[$l]);

                            echo "<tr>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $usersArrayExplode[1] . " " . $usersArrayExplode[2]
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $usersArrayExplode[3] . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $ordersArrayExplode[0] . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $ordersArrayExplode[2] . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $productsArrayExplode[1] . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $productsArrayExplode[3] . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $orderProductsArrayExplode[3] . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                $ordersArrayExplode[3] . "</td>";
                            echo "</tr>";
                        }
                    }
                }
            }
        }
    }
}
