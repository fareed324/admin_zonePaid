<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Network Management";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$name = $_REQUEST['name'];
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$networkid = $_REQUEST['networkid'];

if ($act == "1"){
	$q3 = "INSERT INTO network (name, username, password) VALUES('$name', '$username', '$password') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Network";
	
}
// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM network WHERE id='$networkid'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted Network ID:".$networkid;
}
// ACT=3 Modify Data
if ($act == "3"){
	$q5 = "UPDATE network SET name='$name', username='$username', password='$password'  WHERE id='$networkid'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Network ID:".$networkid;
	
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
			
			<form name="newnetwork" method="post" action="network.php?act=1">
			<fieldset>
				<legend>Add New Network</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Name:</td>
						<td><input type="text" size="40" name="name" class="input-text" /></td>
					</tr>
					<tr>
						<td>Username:</td>
						<td><input type="text" size="40" name="username" class="input-text" /></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="text" size="40" name="password" class="input-text" /></td>
					</tr>					
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>
			
			</form>

						<!-- Table (TABLE) -->
			<h3 class="tit">Affiliate Networks</h3>
			<table>
				<tr>
				    <th width="100">Name</th>
				    <th width="100">Username</th>
				    <th width="100">Password</th>
					<th width="100">Adverts</th>
				    <th width="100">Modify</th>
				    <th width="100">Delete</th>
				</tr>
				<?php
				$colcount = '0';
				$q = "SELECT * FROM network ORDER BY name";
				$qa = mysqli_query($conn,$q);
				while($qarow = mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$networkid = $qarow['id'];
					$name = $qarow['name'];
					$username = $qarow['username'];
					$password = $qarow['password'];
					//count adverts
					$q2 = "SELECT * FROM advert WHERE network = '$networkid' AND status > 0 ";
					$qa2 = mysqli_query($conn,$q2);
					$qn2 = mysqli_num_rows($qa2);
					echo '
					<form name="editnetwork" method="post" action="network.php?act=3&networkid='.$networkid.'">
								<tr'.table_background($colcount).'>
									<td align="center"><input type="text" size="20" name="name" class="input-text" value="'.$name.'" /></td>
									<td align="center"><input type="text" size="20" name="username" class="input-text" value="'.$username.'" /></td>
									<td align="center"><input type="text" size="20" name="password" class="input-text" value="'.$password.'" /></td>
									<td align="center">'.$qn2.'</td>
									<td align="center" valign="middle"><input type="image" src="gfx/development-tools-icon.jpg" alt="Submit" width="24" height="24"></td>
									<td align="center" valign="middle"><a href="network.php?act=2&networkid='.$networkid.'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/>	
								</tr>
					</form>
					
					';
				}
				?>
			</table>
			
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>