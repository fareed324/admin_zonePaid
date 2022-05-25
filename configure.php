<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Website Config";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$varname = $_REQUEST['varname'];
$comment = $_REQUEST['comment'];
$value = $_REQUEST['value'];
$configid = $_REQUEST['configid'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO config (varname, value, comment) VALUES('$varname','$value', '$comment') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Config";
	
}

// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM config WHERE varname='$configid'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted Config Item:".$varname;
}

// ACT=3 Modify Data
if ($act == "3"){
	$q5 = "UPDATE config SET comment='$comment', value='$value' WHERE varname='$configid'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Config:".$configid;
	
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
<form name="addconfig" method="post" action="configure.php?act=1">
						<fieldset>
				<legend>Add New Config Part</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Variable Name:</td>
						<td><input type="text" size="40" name="varname" class="input-text" /></td>
					</tr>
					<tr>
						<td style="width:70px;">Details:</td>
						<td><input type="text" size="40" name="comment" class="input-text" /></td>
					</tr>
					<tr>
						<td class="va-top">Value:</td>
						<td><textarea name="value" cols="75" rows="3" class="input-text"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>

						<h3 class="tit">Current Config Table</h3>
			
			<table valign="top">
				<tr valign="top">
				    <th valign="top">Variable Name</th>
				    <th valign="top">Comment</th>
				    <th width="234px" valign="top">Value</th>
				    <th valign="top">Modify</th>
					<th valign="top">Delete</th>
				</tr>
				
				<?php
				$colcount = 0;
				$q1 = "SELECT * FROM config ORDER BY varname ASC";
				$qa1 = mysqli_query($conn,$q1);
				while($qa1row = mysqli_fetch_array($qa1)){
					$colcount = $colcount + 1;
					echo '
				<form name="editconfig" method="post" action="configure.php?act=3&configid='.$qa1row['varname'].'">	
				<tr'.table_background($colcount).'>
				    <td valign="top">'.$qa1row['varname'].'</td>
				    <td valign="top"><input type="text" size="40" name="comment" class="input-text" value="'.$qa1row['comment'].'"/></td>
				    <td valign="top"><textarea name="value" cols="45" rows="3" class="input-text">'.$qa1row['value'].'</textarea></td>
					<td align="center" valign="middle"><input type="image" src="gfx/development-tools-icon.jpg" alt="Submit" width="24" height="24"></td>
					<td align="center" valign="middle"><a href="configure.php?act=2&configid='.$qa1row['varname'].'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/></a></td>
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