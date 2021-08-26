<?php
require_once('DB.php');
header("X-XSS-Protection: 1; mode=block");
DB::getInstance()->query("DELETE FROM products WHERE name = ''");
$result = DB::getInstance()->query("SELECT * FROM products");

echo "<table id='products-table' style='width: 100%'>";

$i = 0;

while ($row = $result->fetch_assoc()) {
    if ($i == 0) {
        $i++;
        echo "<tr>";
        foreach ($row as $key => $value) {
            if ($key === 'product_id') {

            } else {
                echo "<th style='border: 1px solid black'>" . $key . "</th>";
            }
        }
        echo "</tr>";
    }
    echo "<tr>";

    $start2 = 0;
    foreach ($row as $value) {
        if ($start2 === 0) {
            $start2++;
        } else {
            echo "<td style='border: 1px solid black'>" . $value . "</td>";
        }
    }

    echo "</tr>";
}
echo "</table>";
