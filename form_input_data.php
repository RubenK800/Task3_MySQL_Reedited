<?php
header("X-XSS-Protection: 1; mode=block");

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];

require_once('DB.php');

DB::getInstance()->query("INSERT INTO products (name,description,price)
              VALUES ('$name','$description','$price')");


