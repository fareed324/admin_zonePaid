<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "Front Page Ads";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$p1 = $_REQUEST['p1'];
$p2 = $_REQUEST['p2'];
$p3 = $_REQUEST['p3'];
$p4 = $_REQUEST['p4'];
$p5 = $_REQUEST['p5'];
$p6 = $_REQUEST['p6'];
$p7 = $_REQUEST['p7'];
$p8 = $_REQUEST['p8'];

// get ACT=1 Add New DB Entry
if ($act=="1"){
	$q5 = "UPDATE advertise SET advertids='$p1' WHERE orderno=1";
	$qa5 = mysqli_query($conn,$q5);
	$q5 = "UPDATE advertise SET advertids='$p2' WHERE orderno=2";
	$qa5 = mysqli_query($conn,$q5);
	$q5 = "UPDATE advertise SET advertids='$p3' WHERE orderno=3";
	$qa5 = mysqli_query($conn,$q5);
	$q5 = "UPDATE advertise SET advertids='$p4' WHERE orderno=4";
	$qa5 = mysqli_query($conn,$q5);
	$q5 = "UPDATE advertise SET advertids='$p5' WHERE orderno=5";
	$qa5 = mysqli_query($conn,$q5);
	$q5 = "UPDATE advertise SET advertids='$p6' WHERE orderno=6";
	$qa5 = mysqli_query($conn,$q5);
	$q5 = "UPDATE advertise SET advertids='$p7' WHERE orderno=7";
	$qa5 = mysqli_query($conn,$q5);
	$q5 = "UPDATE advertise SET advertids='$p8' WHERE orderno=8";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Front Page Adverts";

}

//Output
include_once('header.php');
include_once('menu.php');

// run the general query
$q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
$qa = mysqli_query($conn,$q);

?>


		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			?>
			<form name="updatefrontpage" method="post" action="frontpageads.php?act=1">
						<h3 class="tit">Edit the adverts displayed on the front page!</h3>
			<table>
				<tr>
				    <th>Position</th>
				    <th>Advert</th>
				</tr>
				<tr>
				    <td>1</td>
				    <td>
						 <select name="p1">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 1";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>
				</tr>
				<tr class="bg">
				    <td>2</td>
				    <td>
						 <select name="p2">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 2";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							 $q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
							 $qa = mysqli_query($conn,$q);

							while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>
				</tr>
				<tr>
				    <td>3</td>
				    <td>
						 <select name="p3">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 3";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							 $q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
							 $qa = mysqli_query($conn,$q);
							 
							while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>

				</tr>
				<tr class="bg">
				    <td>4</td>
				    <td>
						 <select name="p4">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 4";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							 $q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
							 $qa = mysqli_query($conn,$q);

							 while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>

				</tr>
				<tr>
				    <td>5</td>
				    <td>
						 <select name="p5">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 5";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							 $q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
							 $qa = mysqli_query($conn,$q);

							 while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>
				</tr>
				<tr class="bg">
				    <td>6</td>
				    <td>
						 <select name="p6">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 6";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							 $q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
							 $qa = mysqli_query($conn,$q);

							 while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>
				</tr>
				<tr>
				    <td>7</td>
				    <td>
						 <select name="p7">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 7";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							 $q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
							 $qa = mysqli_query($conn,$q);

							 while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>

				</tr>
				<tr class="bg">
				    <td>8</td>
				    <td>
						 <select name="p8">
							 <?php
							 $q1 = "SELECT * FROM advertise WHERE orderno = 8";
							 $qa1 = mysqli_query($conn,$q1);
							 $qa1row = mysqli_fetch_assoc($qa1);
							 $advertname = advert_name_search($qa1row['advertids']);
							 echo '<option value="'.$qa1row['advertids'].'" selected>'.$advertname.'</option>';
							 $q = "SELECT * FROM advert WHERE status=1 ORDER BY companyname ASC";
							 $qa = mysqli_query($conn,$q);

							 while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['companyname'].'</option>';
							}
							?>
						</select> 
					</td>

				</tr>

			</table>
			<input type="submit" class="input-submit" value="Submit" />

			</form>
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>