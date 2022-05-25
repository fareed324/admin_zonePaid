<?php

include_once('connect.php');
$id = $_REQUEST['id'];

$q = "SELECT * FROM advert WHERE id = '$id'";
$qa = mysqli_query($conn,$q);
$qarow = mysqli_fetch_array($qa);
$response = $qarow['companyname'];


$q = "DELETE FROM products WHERE advertId = '$id'";
$qr = mysqli_query($conn,$q);


$ind = "ALTER TABLE $tablename DROP INDEX `prod_name` ,
ADD INDEX `prod_name` ( `prod_name` ) ";
$ind_q = mysqli_query($conn,$ind);

echo 'ALL PRODUCTS DELETED FROM DATABASE FOR ADVERT: ('.$response.')' ;

?>