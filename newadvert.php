<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "Insert New Advert";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$advertid = $_REQUEST['advertid'];
$newstatus = $_REQUEST['newstatus'];
$companyname = $_REQUEST['companyname'];
$buttonimage = $_REQUEST['buttonimage'];
$bannerimage = $_REQUEST['bannerimage'];
$urllink = $_REQUEST['urllink'];
$smalldesc = $_REQUEST['smalldesc'];
$largedesc = $_REQUEST['largedesc'];
$howearn = $_REQUEST['howearn'];
$pay_out = $_REQUEST['pay_out'];
$brand_power = $_REQUEST['brand_power'];
$product = $_REQUEST['product'];
$brand = $_REQUEST['brand'];
$feed_link = $_REQUEST['feed_link'];
$subcat1 = $_REQUEST['subcat1'];
$subcat2 = $_REQUEST['subcat2'];
$subcat3 = $_REQUEST['subcat3'];
$today = date("F j, Y, g:i a");
$today = date("d-m-Y H:i:s");
$networkid = $_REQUEST['network'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO advert (companyname, network, timestamp) VALUES('$companyname', '$networkid', '$today') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New Advert you now need to approve it!";
	$q = "SELECT * FROM advert WHERE companyname='$companyname' ORDER BY id DESC LIMIT 1";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_array($qa);
	$advertid = $qarow['id'];
	header("Location: advertlook.php?advertid=$advertid");
	exit;
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
			<form name="newadvert" method="post" action="newadvert.php?act=1">

			<table>
				<tr>
				    <th>Company Name</th>
				    <td><input type="text" size="40" name="companyname" class="input-text" value="" /></td>
				</tr>
				<tr>
				    <th>Network</th>
				    <td><select name="network">
					<?php
						$q = "SELECT * FROM network ORDER BY name";
						$qa = mysqli_query($conn,$q);
						while($qarow = mysqli_fetch_array($qa)){
							$networkid = $qarow['id'];
							$name = $qarow['name'];
							echo '<option value="'.$networkid.'">'.$name.'</option>';
						}

						?>
						</select>
					</td>
				</tr>
				<tr>
				    <th>Submit</th>
				    <td align="center"><input type="submit" class="input-submit" value="Submit" /></td>
				</tr>
			
			</table>
			
			</form>
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>