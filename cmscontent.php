<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Add / Edit CMS Content.";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$refname = $_REQUEST['refname'];
$pagetitle = $_REQUEST['pagetitle'];
$description = $_REQUEST['description'];
$pagebody = $_REQUEST['pagebody'];
$cmsid = $_REQUEST['cmsid'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO cmscontent (refname, description, pagetitle, pagebody) VALUES('$refname','$description', '$pagetitle', '$pagebody') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New CMS Content: ".$refname;
	
}

// ACT=2 Delete from Database
if ($act == "2"){
	$q4 = "DELETE FROM cmscontent WHERE id='$cmsid'";
	$qa4 = mysqli_query($conn,$q4);
	$type = "1";
	$mess = "Deleted CMS Content: ".$cmsid;
}

// ACT=3 Modify Data
if ($act == "3"){
	$q5 = "UPDATE cmscontent SET description='$description', pagetitle='$pagetitle', pagebody='$pagebody' WHERE id='$cmsid'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED CMS Content: ".$cmsid;
	
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
			<form name="addnewCMS" method="post" action="cmscontent.php?act=1">
			
			<fieldset>
				<legend>Add New Content</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Ref Name:</td>
						<td><input type="text" size="40" name="refname" class="input-text" /></td>
					</tr>
					<tr>
						<td style="width:70px;">Page Title:</td>
						<td><input type="text" size="40" name="pagetitle" class="input-text" /></td>
					</tr>
						<tr>
						<td style="width:70px;">Description:</td>
						<td><input type="text" size="40" name="description" class="input-text" /></td>
					</tr>
				
					<tr>
						<td class="va-top">Page Body:</td>
						<td><textarea name="pagebody" cols="75" rows="4" class="input-text"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>
</form>
						<h3 class="tit">Current CMS Content</h3>
			<table>
				<tr>
				    <th>Ref Name</th>
				    <th>Description</th>
				    <th>Page Title</th>
				    <th>Content</th>
				    <th>Modify</th>
				    <th>Delete</th>
				</tr>
								<?php
				$colcount = 0;
				$q1 = "SELECT * FROM cmscontent ORDER BY id ASC";
				$qa1 = mysqli_query($conn,$q1);
				while($qa1row = mysqli_fetch_array($qa1)){
					$colcount = $colcount + 1;
					echo '
					<form name="editCMS" method="post" action="cmscontent.php?act=3&cmsid='.$qa1row['id'].'">
				<tr'.table_background($colcount).'>
				    <td>'.$qa1row['refname'].'</td>
				    <td><textarea name="description" cols="30" rows="2" class="input-text">'.$qa1row['description'].'</textarea></td>
				    <td><input type="text" size="30" name="pagetitle" class="input-text" value="'.$qa1row['pagetitle'].'"/></td>
				    <td><textarea name="pagebody" cols="45" rows="3" class="input-text">'.$qa1row['pagebody'].'</textarea></td>
					<td align="center" valign="middle"><input type="image" src="gfx/development-tools-icon.jpg" alt="Submit" width="24" height="24"></td>
					<td align="center" valign="middle"><a href="cmscontent.php?act=2&cmsid='.$qa1row['id'].'"><img src="gfx/Close-2-icon.jpg" width="24" height="24" alt=""/></a></td>
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