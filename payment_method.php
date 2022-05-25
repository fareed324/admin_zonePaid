<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Add / Edit Payment Method";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$user_notes = $_REQUEST['user_notes'];
$admin_notes = $_REQUEST['admin_notes'];
$user_display = $_REQUEST['user_display'];
$name = $_REQUEST['name'];
$status = $_REQUEST['status'];

// get ACT=1 Add New DB Entry
if($act=="1"){
	$q3 = "INSERT INTO payment_method (name, admin_notes, user_notes, status, user_display) VALUES('$name', '$admin_notes', '$user_notes', '1', '$user_display') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Payment Method";
	
}

if($act=="2"){
	$q5 = "UPDATE payment_method SET status='$status' WHERE id='$id'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Payment Method";
	
}

//Output
include_once('header.php');
include_once('menu.php');

?>


		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			?>
			<form name="add" method="post" action="payment_method.php?act=1">
						<fieldset>
				<legend>Add New Payment Method</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Name:</td>
						<td><input type="text" size="40" name="name" class="input-text" /></td>
					</tr>
					<tr>
						<td style="width:70px;">User Display:</td>
						<td><input type="text" size="40" name="user_display" class="input-text" /></td>
					</tr>
					<tr>
						<td class="va-top">Admin Notes:</td>
						<td><textarea name="admin_notes" cols="75" rows="3" class="input-text"></textarea></td>
					</tr>
					<tr>
						<td class="va-top">User Notes:</td>
						<td><textarea name="user_notes" cols="75" rows="3" class="input-text"></textarea></td>
					</tr>

					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>
			<h3 class="tit">Current Payment Methods</h3>
			<table>
				<tr>
				    <th>Name - Status()</th>
				    <th>User Display</th>
				    <th>Admin Notes</th>
				    <th>User Notes</th>
				    <th>Action</th>
				</tr>
			<?php
				$colcount = '0';
				// get all the data
				$q = "SELECT * FROM payment_method ORDER BY name";
				$qa = mysqli_query($conn,$q);
				while($qarow = mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$status = $qarow['status'];
					if($status=="1"){ 
						$display_status = '<img src="gfx/Ok-icon.jpg" width="12" height="12" alt=""/>'; 
						$next_stage = '<a href="payment_method.php?act=2&status=2&id='.$qarow['id'].'">[SUSPEND]</a>';
					}
					if($status=="2"){ 
						$display_status = '<img src="gfx/Close-2-icon.jpg" width="12" height="12" alt=""/>'; 
						$next_stage = '<a href="payment_method.php?act=2&status=1&id='.$qarow['id'].'">[ACTIVATE]</a>';
					}
					echo '
						<tr'.table_background($colcount).'>
							<td>'.$qarow['name'].' - Status('.$display_status.')</td>
							<td>'.$qarow['user_display'].'</td>
							<td>'.$qarow['admin_notes'].'</td>
							<td>'.$qarow['user_notes'].'</td>
							<td>'.$next_stage.'</td>
						</tr>
					';
				}
				
				?>
				
			</table>
			
			
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>