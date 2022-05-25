<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Email Responses";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$subject = $_REQUEST['subject'];
$body = $_REQUEST['body'];
$email2test = $_REQUEST['email2test'];
$testemail = $_REQUEST['testemail'];


// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO admin_email (subject, message) VALUES('$subject', '$body') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Email: ".$id;
	
}
// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM admin_email WHERE id='$id'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted Email: ".$id;
}

// ACT=3 Modify Data
if ($act == "3"){
	$q5 = "UPDATE admin_email SET subject='$subject', message='$body' WHERE id='$id'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED New Email: ".$id;
	
}
// ACT=5 send test email
if ($act == "5"){
	$userid = 'test';
	send_mail($userid, $email2test, $testemail);
	$type = "3";
	$mess = "Sent Test Email To: ".$testemail;

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

			<form name="addemail" method="post" action="emails.php?act=1" enctype="multipart/form-data">
						<fieldset>
				<legend>Add New Email Response</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Ref:</td>
						<td><input type="text" size="40" name="id" class="input-text" /></td>
					</tr>
					<tr>
						<td style="width:70px;">Subject:</td>
						<td><input type="text" size="40" name="subject" class="input-text" /></td>
					</tr>					
					<tr>
						<td class="va-top">Message:</td>
						<td><textarea name="body" cols="75" rows="4" class="input-text"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>

			<div class="col50">
			
				<p class="t-justify">
			<h3 class="tit">Current Email Responses</h3>
			<table>
				<tr>
				    <th>ID</th>
				    <th>Subject</th>
				    <th>Content</th>
				    <th>Modify</th>
				    <th>Delete</th>
				</tr>
				<?php
				$colcount = 0;
				$q1 = "SELECT * FROM admin_email ORDER BY id ASC";
				$qa1 = mysqli_query($conn,$q1);
				while($qa1row = mysqli_fetch_array($qa1)){
					$colcount = $colcount + 1;
					echo '
				<form name="editemail" method="post" action="emails.php?act=3&id='.$qa1row['id'].'">
				<tr>
				    <td>'.$qa1row['id'].'</td>
				    <td><textarea name="subject" cols="10" rows="2" class="input-text">'.$qa1row['subject'].'</textarea></td>
				    <td><textarea name="body" cols="20" rows="2" class="input-text">'.$qa1row['message'].'</textarea></td>
					<td align="center" valign="middle"><input type="image" src="gfx/development-tools-icon.jpg" alt="Submit" width="24" height="24"></td>
					<td align="center" valign="middle"><a href="emails.php?act=2&id='.$qa1row['id'].'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/></a></td>
				</tr>
				</form>

					';
				}
				?>
			</table>

				</p>
				
			</div> <!-- /col50 -->

			<div class="col50 f-right">
			
				<p class="t-justify">
			<h3 class="tit">Test Data</h3>
			<table>
				<tr>
				    <th>First Name</th>
				    <td>Bobby</td>
				</tr>
				<tr>
				    <th>Surname</th>
				    <td>Charlton</td>
				</tr>
				<tr>
				    <th>Username</th>
				    <td>no-reply@paidcash.co.uk</td>
				</tr>
				<tr class="bg">
				    <th>Email</th>
				    <td>no-reply@paidcash.co.uk</td>
				</tr>
				<form name="emailtest" method="post" action="emails.php?act=5">
				<tr>
				    <th>Email To Test</th>
				    <td><input type="text" size="30" name="testemail" class="input-text" value=""/>
					<br>
						<select name="email2test">
						  <option value="0" selected>Please Select</option>
						<?php
						$q2 = "SELECT * FROM emails ORDER BY id ASC";
						$qa2 = mysqli_query($conn,$q2);
						while($qa2row = mysqli_fetch_array($qa2)){
							echo '<option value="'.$qa2row['id'].'">'.$qa2row['id'].'</option>';
						}
						?>
						
						</select>
					</td>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
					
				</tr>
				</form>
			</table>
			<h3 class="tit">Usable Tags</h3>
			<table>
				<tr>
				    <th>
						Firstname<br>
Surname
					
					</th>
				    <td>
						[firstname]<br>
						[surname]
					</td>
				</tr>
				<tr>
				    <th>Username</th>
				    <td>[username]</td>
				</tr>
				<tr class="bg">
				    <th>Email</th>
				    <td>[email]</td>
				</tr>
				<tr class="bg">
				    <th>Others</th>
				    <td>
						Email Validator = [emailchk]<br>
						Password Reset = [password_reset_link]<br>

						
					
					</td>
				</tr>
			</table>


				</p>

				
			</div> <!-- /col50 -->			
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>