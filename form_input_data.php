<?php

//var_dump($GLOBALS);
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];

$db = mysqli_connect("localhost", "Ruben455", "20power20good", "online_shop");

//it is not working, maybe I'm doing something wrong
//$query1 = "SET @num := 0; UPDATE products SET id = @num := (@num+1); ALTER
//               TABLE products AUTO_INCREMENT = 1;";
//mysqli_query($db,$query1);

$query = "INSERT INTO products (name,description,price)
              VALUES ('$name','$description','$price')";
mysqli_query($db, $query);
