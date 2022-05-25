<?php
include_once('connect.php');
//include_once('logincheck.php');
include_once('functions.php');

//$admin_level_permission = "";
//$page_title = "";
//admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];

// get ACT=1 Add New DB Entry

//Output
//include_once('header.php');
//include_once('menu.php');

$value = rand(5, 150);
$value2 = rand(150, 1500);
$advertid = 2331;
$userid = 747;

$q3 = "INSERT INTO click (advertid, timestamp , referrer, userid) VALUES ('$advertid', '$today', '$userid', '$userid') ";
$qa3 = mysqli_query($conn,$q3);


?>
