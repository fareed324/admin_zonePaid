<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Add System Message";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$title = $_REQUEST['title'];
$description = $_REQUEST['desc'];
$messid = $_REQUEST['messid'];
$from = 'Admin';

// get ACT=1 Add New DB Entry
if ($act == "1"){
	//$q3 = "INSERT INTO message (from, title, description, timestamp) VALUES ('$from', '$title', '$description', '$today') ";
	$q3 = "INSERT INTO `message` (`id`, `from`, `title`, `description`, `timestamp`) VALUES (NULL, '$from', '$title', '$description', '$today') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Message";
	
}

// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM message WHERE id='$messid'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted message: ".$messid;
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
<form name="addmessage" method="post" action="messages.php?act=1">
						<fieldset>
				<legend>Add New Message</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Title:</td>
						<td><input type="text" size="40" name="title" class="input-text" /></td>
					</tr>
					<tr>
						<td class="va-top">Message:</td>
						<td><textarea name="desc" cols="75" rows="7" class="input-text"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>
			</form>
			
						<h3 class="tit">Previous Messages</h3>
			<table>
				<tr>
				    <th>From</th>
				    <th>Title</th>
				    <th>Content</th>
				    <th>Delete</th>
				</tr>
				<?php
				$colcount = 0;
				$q1 = "SELECT * FROM message ORDER BY id ASC";
				$qa1 = mysqli_query($conn,$q1);
				while($qa1row = mysqli_fetch_array($qa1)){
					$colcount = $colcount + 1;
					echo '
									<tr'.table_background($colcount).'>
				    <td>'.$qa1row['from'].'<br>
						'.$qa1row['timestamp'].'
					</td>
				    <td>'.$qa1row['title'].'</td>
				    <td><textarea cols="75" rows="3" class="input-text">'.$qa1row['description'].'</textarea></td>
				    <td><a href="messages.php?act=2&messid='.$qa1row['id'].'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/></a></td>
				</tr>
					';
				}
				?>

			</table>

			
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>