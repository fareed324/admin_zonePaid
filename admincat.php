<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Edit / Add Admin Cat";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$main_cat_name = $_REQUEST['main_cat_name'];
$main_cat_id = $_REQUEST['main_cat_id'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO adminmaincat (name) VALUES('$main_cat_name') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Admin Main Cat";
	
}

// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM adminmaincat WHERE id='$main_cat_id'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted Admin Main Cat ID:".$main_cat_id;
}
// ACT=3 Modify Data

if ($act == "3"){
	$q5 = "UPDATE adminmaincat SET name='$main_cat_name' WHERE id='$main_cat_id'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Admin Main Cat ID:".$main_cat_id." To: ".$main_cat_name;
	
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

		  <form name="newadmincat" method="post" action="admincat.php?act=1">
			<fieldset>
				<legend>Add New Main Cat For Admin Area</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Name:</td>
						<td><input type="text" size="40" name="main_cat_name" class="input-text" /></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>
		
		  </form>
			
						<!-- Table (TABLE) -->
		  <h3 class="tit">List Of Current Main Admin Cats</h3>
			<table>
				<tr>
				    <th width="100" align="center">Name</th>
				    <th width="100" align="center">Sub Cats</th>
				    <th width="100" align="center">Modify</th>
				    <th width="100" align="center">Delete</th>
				</tr>
				<?php
				$colcount = '0';
				// get all the data
				$q = "SELECT * FROM adminmaincat ORDER BY id";
				$qa = mysqli_query($conn,$q);
				while($qarow = mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$main_cat_name = $qarow['name'];
					$main_cat_id = $qarow['id'];
					//how many subcats does this have
					$q2 = "SELECT * FROM adminsubcats WHERE maincatid='".$main_cat_id."'";
					$qa2 = mysqli_query($conn,$q2);
					$qn2 = mysqli_num_rows($qa2);
					echo '
					<form name="editMainCat" method="post" action="admincat.php?act=3&main_cat_id='.$main_cat_id.'">
							<tr'.table_background($colcount).'>
								<td align="center"><input type="text" size="40" name="main_cat_name" class="input-text" value="'.$main_cat_name.'" /></td>
								<td align="center">'.$qn2.'</td>
								<td align="center"><input type="image" src="gfx/development-tools-icon.jpg" alt="Submit" width="24" height="24"></td>
								<td align="center"><a href="admincat.php?act=2&main_cat_id='.$main_cat_id.'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/></a></td>
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