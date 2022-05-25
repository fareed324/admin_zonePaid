<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Admin Sub Cat";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$main_cat_id = $_REQUEST['main_cat_id'];
$urllink = $_REQUEST['urllink'];
$name = $_REQUEST['name'];
$sub_cat_id = $_REQUEST['sub_cat_id'];
$sub_cat_name = $_REQUEST['sub_cat_name'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO adminsubcats (name, maincatid, link) VALUES('$name','$main_cat_id', '$urllink') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Admin Main Cat";
	
}
// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM adminsubcats WHERE id='$sub_cat_id'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted Admin Sub Cat ID:".$sub_cat_id;
}

// ACT=3 Modify Data

if ($act == "3"){
	$q5 = "UPDATE adminsubcats SET name='$sub_cat_name', maincatid='$main_cat_id', link='$urllink' WHERE id='$sub_cat_id'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Admin Sub Cat ID:".$sub_cat_id;
	
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

			<form name="newadminsubcat" method="post" action="adminsubcat.php?act=1">
						<fieldset>
				<legend>Add New Sub Cat For Admin Area</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Name:</td>
						<td><input type="text" size="40" name="name" class="input-text" /></td>
					</tr>
					<tr>
						<td style="width:70px;">Link:</td>
						<td><input type="text" size="40" name="urllink" class="input-text" /></td>
					</tr>					
					<tr>
						<td>Main Cat:</td>
						<td><select name="main_cat_id">
							<?php
							$q3 = "SELECT * FROM adminmaincat";
							$qa3 = mysqli_query($conn,$q3);
							while($qa3row = mysqli_fetch_array($qa3)){
								echo '<option value="'.$qa3row['id'].'">'.$qa3row['name'].'</option>';
							}
							?>
							</select></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>
			
						<!-- Table (TABLE) -->
			<h3 class="tit">List Of Current Main Admin Sub Cats</h3>
			<table>
				<tr>
				    <th width="100">Name</th>
				    <th width="100">URL</th>
				    <th width="100">Main Cat</th>
				    <th width="100">Modify</th>
				    <th width="100">Delete</th>
				</tr>
			<?php
				$colcount = '0';
				// get all the data
				$q = "SELECT * FROM adminsubcats ORDER BY id";
				$qa = mysqli_query($conn,$q);
				while($qarow = mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$sub_cat_id = $qarow['id'];
					$sub_cat_name  = $qarow['name'];
					$main_cat_id = $qarow['maincatid'];
					//get main cat name
					$q2 = "SELECT * FROM adminmaincat WHERE id='$main_cat_id'";
					$qa2 = mysqli_query($conn,$q2);
					$qa2row = mysqli_fetch_assoc($qa2);
					$main_cat_name = $qa2row['name'];
					//echo 'name: '.$main_cat_name;
					$urllink = $qarow['link'];
					echo '
					<form name="editsubCat" method="post" action="adminsubcat.php?act=3&sub_cat_id='.$sub_cat_id.'">
					<tr'.table_background($colcount).'>
						<td align="center" valign="middle"><input type="text" size="40" name="sub_cat_name" class="input-text" value="'.$sub_cat_name.'" /></td>
						<td align="center" valign="middle"><input type="text" size="40" name="urllink" class="input-text" value="'.$urllink.'" /></td>
						<td align="center" valign="middle"><select name="main_cat_id">
						<option value="'.$main_cat_id.'" selected>'.$qa2row['name'].'</option>';
						$q5 = "SELECT * FROM adminmaincat";
						$qa5 = mysqli_query($conn,$q5);
						while($qa5row = mysqli_fetch_array($qa5)){
							echo '<option value="'.$qa5row['id'].'">'.$qa5row['name'].'</option>';
						} 
						echo '
					</select></td>
						<td align="center" valign="middle"><input type="image" src="gfx/development-tools-icon.jpg" alt="Submit" width="24" height="24"></td>
						<td align="center" valign="middle"><a href="adminsubcat.php?act=2&sub_cat_id='.$sub_cat_id.'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/></a></td>
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