<?php
require_once('DB.php');
header("X-XSS-Protection: 1; mode=block");

//1.1) users table
$result = DB::getInstance()->query("SELECT * FROM users");
$usersArray=[];
tableArrayPushData($result, $usersArray);

//1.2) products table
$result = DB::getInstance()->query("SELECT * FROM products");
$productsArray=[];
tableArrayPushData($result, $productsArray);

//1.3) orders table
$result = DB::getInstance()->query("SELECT * FROM orders");
$ordersArray=[];
tableArrayPushData($result, $ordersArray);

//1.4) order_products table
$result = DB::getInstance()->query("SELECT * FROM order_products");
$orderProductsArray=[];
tableArrayPushData($result, $orderProductsArray);

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
        if (conditionFor($ordersArray[$j],1,$usersArray[$i],0)) {
            for ($k = 0; $k < count($orderProductsArray); $k++) {
                if (conditionFor($orderProductsArray[$k],1, $ordersArray[$j],0)) {
                    for ($l = 0; $l < count($productsArray); $l++) {
                        if (conditionFor($productsArray[$l],0, $orderProductsArray[$k],2) &&
                            conditionFor($ordersArray[$j],0, $orderProductsArray[$k],1)) {
                            $str = "$usersArray[$i] {} $ordersArray[$j] {} 
                                    $orderProductsArray[$k] {} 
                                    $productsArray[$l]";

                            echo "<tr>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($usersArray[$i],1) . " " . exploded($usersArray[$i],2)
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($usersArray[$i],3)
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($ordersArray[$j],0)
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($ordersArray[$j],2)
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($productsArray[$l],1)
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($productsArray[$l],3)
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($orderProductsArray[$k],3)
                                . "</td>";
                            echo "<td style = 'border: 1px solid black;'>" .
                                exploded($ordersArray[$j],3)
                                . "</td>";
                            echo "</tr>";
                        }
                    }
                }
            }
        }
    }
}

function exploded($arrayElement, $index)
{
    $separator = '|';
    return explode($separator, $arrayElement)[$index];
}

function conditionFor($first, $firstIndex, $second, $secondIndex): bool
{
    return exploded($first, $firstIndex) === exploded($second, $secondIndex);
}
