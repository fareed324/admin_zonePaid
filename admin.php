<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Admin Overview";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$adminlevel = $_REQUEST['adminlevel'];
$adminid = $_REQUEST['adminid'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	//md5 the password
	$password = md5($password);
	$q3 = "INSERT INTO admin (name, email, username, password, type) VALUES('$name','$email', '$username', '$password', '$adminlevel') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Admin";
	
}
// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM admin WHERE id='$adminid'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted Admin Sub Cat ID:".$adminid;
}
// ACT=3 Modify Data
if ($act == "3"){
	$password = md5($password);
	$q5 = "UPDATE admin SET name='$name', email='$email', username='$username', password='$password', type='$adminlevel'  WHERE id='$adminid'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Admin User ID:".$adminid;
	
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
			<form  name="newadmin" method="post" action="admin.php?act=1">
						<fieldset>
				<legend>Add New Admin</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Name:</td>
						<td><input type="text" size="40" name="name" class="input-text" /></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><input type="text" size="40" name="email" class="input-text" /></td>
					</tr>
					<tr>
						<td>Username:</td>
						<td><input type="text" size="40" name="username" class="input-text" /></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" size="40" name="password" class="input-text" /></td>
					</tr>

					<tr>
						<td class="va-top">Security:</td>
						<td>
						<select name="adminlevel">
						<option value="0" selected>Please Select</option>
						<option value="advert_admin">advert_admin</option>
						<option value="system_admin">system_admin</option>	
						</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>
			
						<!-- Table (TABLE) -->
			<h3 class="tit">Table</h3>
			<table>
				<tr>
				    <th width="100">Name</th>
				    <th width="100">Email</th>
				    <th width="100">Username</th>
				    <th width="100">Password</th>
				    <th width="100">Type</th>
				    <th width="100">Modify</th>
				    <th width="100">Delete</th>
				</tr>
			<?php
				$colcount = '0';
				$q = "SELECT * FROM admin ORDER BY name";
				$qa = mysqli_query($conn,$q);
				while($qarow=mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$adminid = $qarow['id'];
					$name = $qarow['name'];
					$email = $qarow['email'];
					$username = $qarow['username'];
					$password = $qarow['password'];
					$adminlevel = $qarow['type'];
					
					echo '
						<form name="editadmin" method="post" action="admin.php?act=3&adminid='.$adminid.'">
						<tr'.table_background($colcount).'>
							<td><input type="text" size="20" name="name" class="input-text" value="'.$name.'" /></td>
							<td><input type="text" size="40" name="email" class="input-text" value="'.$email.'" /></td>
							<td><input type="text" size="20" name="username" class="input-text" value="'.$username.'" /></td>
							<td><input type="text" size="20" name="password" class="input-text" value="" /></td>
							<td>						
								<select name="adminlevel">
								<option value="'.$adminlevel.'" selected>'.$adminlevel.'</option>
								<option value="advert_admin">advert_admin</option>
								<option value="system_admin">system_admin</option>	
								</select>
							</td>
							<td align="center" valign="middle"><input type="image" src="gfx/development-tools-icon.jpg" alt="Submit" width="24" height="24"></td>
							<td align="center" valign="middle"><a href="admin.php?act=2&adminid='.$adminid.'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/></a></td>
						</form>	
						</tr>
					
					';

				}
				?>
				
			</table>

		
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>