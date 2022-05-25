<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "Advert List";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$networkselect = $_REQUEST['networkselect'];
$adstatus = $_REQUEST['adstatus'];

// get ACT=1 Add New DB Entry

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

			<form>
						<fieldset>
				<legend>Please Select The Network</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Network:</td>
						<td>
						<select name="networkselect">
							<option value="0" selected>All</option>	
							<?php
							$q = "SELECT * FROM network ORDER BY name";
							$qa = mysqli_query($conn,$q);
							while($qarow = mysqli_fetch_array($qa)){
								echo '<option value="'.$qarow['id'].'">'.$qarow['name'].'</option>';
							}
							?>
							</select>
						</td>
						<td style="width:70px;">Status:</td>
						<td>
						<select name="adstatus">
							<option value="0">Pending</option>	
							<option value="1" selected>Approved</option>	
							<option value="2">Suspended</option>	
							</select>
						</td>
						
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>
						<table>
				<tr>
				    <th>Advert ID</th>
				    <th>Advertiser</th>
				    <th>Network</th>
				    <th>Clicks</th>
				    <th>Sales (&pound;)</th>
					<th>Approved</th>
					<th>Pending</th>
					<th>Cancelled</th>
					<th>Feed</th>
					<th>Status</th>
					<th>Modify</th>
				</tr>			

			<?php
			if( isset($networkselect)){
				if($networkselect=="0"){
					//show all
					$q2 = "SELECT * FROM advert  WHERE status = '$adstatus' ORDER BY companyname"; 
				}else{
					//show selected
					$q2 = "SELECT * FROM advert WHERE network='$networkselect' AND status = '$adstatus' ORDER BY companyname"; 
				}
				$colcount = '0';
				$qa2 = mysqli_query($conn,$q2);
				while($q2row = mysqli_fetch_array($qa2)){
					$colcount = $colcount + 1;
					$networkid = $q2row['network'];
					$advertid = $q2row['id'];
					$status = $q2row['status'];
					$productfeed = $q2row['productfeed'];
					$SHOWfeedsize = '('.$q2row['feed_size'].' mb)';
					if($productfeed=="1"){$SHOWproductfeed = 'YES '.$SHOWfeedsize;}else{
						if($q2row['feed_size'] < "0.01"){$SHOWproductfeed = 'NO';}
						if($q2row['lge']=="2"){$SHOWproductfeed = '<b><font color="#FF0004">LGE</font></b> '.$SHOWfeedsize;}
						
					}
					echo '
				<tr'.table_background($colcount).'>
				    <td align="center">'.$q2row['id'].'</td>
				    <td align="center">'.$q2row['companyname'].'</td>
				    <td align="center">'.network_search($networkid).'</td>
				    <td align="center">'.$q2row['clicks'].'</td>
				    <td align="center">'.$q2row['earnings'].'</td>
					<td align="center">'.advert_count_approved_sales($advertid).'</td>
				    <td align="center">'.advert_count_pending_sales($advertid).'</td>
				    <td align="center">'.advert_count_cancelled_sales($advertid).'</td>
					<td align="center">'.$SHOWproductfeed.'</td>
				    <td align="center">'.status_icon($status).'</td>
				    <td align="center"><a href="advertlook.php?advertid='.$advertid.'"><img src="gfx/development-tools-icon.jpg" width="24" height="24"></a></td>
				</tr>

					';
				}
			?>
			
			<?php
			}
			?>
			</table>

		</div> <!-- /content -->


<?php
include_once('footer.php');
?>