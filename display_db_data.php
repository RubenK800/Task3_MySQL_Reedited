<?php

$con = mysqli_connect("localhost", "Ruben455", "20power20good", "online_shop");
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//$result = mysqli_query($con, "SELECT product_id FROM products");
mysqli_query($con, "DELETE FROM products WHERE name = ''");
$result = mysqli_query($con, "SELECT * FROM products");

//we need multi_query for this, in other case it will not work for us. this is
//for auto_increment value reset
/*mysqli_multi_query($con, "ALTER TABLE products DROP product_id;
                            ALTER TABLE products AUTO_INCREMENT = 1;
                            ALTER TABLE products ADD product_id int
                            UNSIGNED NOT NULL AUTO_INCREMENT
                            PRIMARY KEY FIRST;");*/

echo "<table id='products-table' style='width: 100%'>";

$i = 0;

$p = 0;

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

mysqli_close($con);
