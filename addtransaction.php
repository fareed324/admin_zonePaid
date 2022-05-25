<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "ADD TRANSACTION";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$type = $_REQUEST['type'];
$value = $_REQUEST['value'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO click (
	advertid, 
	userid, 
	timestamp, 
	status, 
	paid, 
	user_commission, 
	paid_date, 
	approved_date
	) VALUES(
	'$type', 
	'$id', 
	'$today', 
	'3', 
	'1', 
	'$value', 
	'$today', 
	'$today'
	) ";
	$qa3 = mysqli_query($conn,$q3);
	
	//get user balance and add
	$q = "SELECT * FROM users WHERE id='$id'";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_assoc($qa);
	$paid = $qarow['paid'];
	$paid = $paid + $value;
	
	// add balance to user account
	$q5 = "UPDATE users SET paid='$paid' WHERE id='$id'";
	$qa5 = mysqli_query($conn,$q5);
	
	$type = "3";
	$mess = "Added New Transaction To Acc of: ".$id;
	
}


// Get Data
$q = "SELECT * FROM users WHERE id='$id'";
$qa = mysqli_query($conn,$q);
$qarow = mysqli_fetch_assoc($qa);


//Output
include_once('header.php');
include_once('menu.php');

?>


		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?> FOR: <?php echo $qarow['name'].' '.$qarow['surname'].' ID:'.$id  ; ?> <a href="lookuser.php?id=<?php echo $id; ?>">[VIEW]</a></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			?>
			<form name="addtransaction" method="post" action="addtransaction.php?act=1&id=<?php echo $id; ?>">
						<fieldset>
				<legend>Add Approved &amp; Paid Transaction</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Value (&pound;):</td>
						<td><input type="text" size="40" name="value" class="input-text" /></td>
					</tr>
					<tr>
						<td>Type:</td>
						<td>
						 <select name="type">
						  <option value="1">Adjustment</option>
						  <option value="2">Competition Win</option>
						</select> 
						</td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>