<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "Edit Advert";
admin_level_check($admin_level_permission);

// items to remove from string
$get_rid = array("'", "!", ";", "*", "-");

// Get Variables Passed
$act = $_REQUEST['act'];
$advertid = $_REQUEST['advertid'];
$newstatus = $_REQUEST['newstatus'];
$companyname = $_REQUEST['companyname'];
$buttonimage = $_REQUEST['buttonimage'];
$bannerimage = $_REQUEST['bannerimage'];
$urllink = $_REQUEST['urllink'];
$smalldesc = $_REQUEST['smalldesc'];
$smalldesc = str_replace($get_rid, "", $smalldesc);
$largedesc = $_REQUEST['largedesc'];
$largedesc = str_replace($get_rid, "", $largedesc);
$howearn = $_REQUEST['howearn'];
$pay_out = $_REQUEST['pay_out'];
$brand_power = $_REQUEST['brand_power'];
$product = $_REQUEST['product'];
$product = str_replace($get_rid, "", $product);
$brand = $_REQUEST['brand'];
$brand = str_replace($get_rid, "", $brand);
$feed_link = $_REQUEST['feed_link'];
$subcat1 = $_REQUEST['subcat1'];
$subcat2 = $_REQUEST['subcat2'];
$subcat3 = $_REQUEST['subcat3'];

// get ACT=1 Update Status
if ($act == "1"){
	$q5 = "UPDATE advert SET status='$newstatus' WHERE id='$advertid'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Advert Status ID: ".$advertid;
	
}

// get ACT=2 Update Feed
if ($act == "2"){
	$q5 = "UPDATE advert SET productfeed='$newstatus' WHERE id='$advertid'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Adverts Product Feed ID: ".$advertid;
	
}

// get ACT=3 Update advert
if ($act == "3"){
	/*$q5 = "UPDATE advert SET 
	companyname='$companyname', 
	buttonimage='$buttonimage', 
	bannerimage='$bannerimage', 
	urllink='$urllink', 
	smalldesc='$smalldesc',  
	largedesc='$largedesc',
	howearn='$howearn',
	pay_out='$pay_out',
	brand_power='$brand_power',
	product='$product',
	brand='$brand',
	feed_link='$feed_link',
	subcat1='$subcat1',
	subcat2='$subcat2',
	subcat3='$subcat3'*/
	$q5 = "UPDATE advert SET 
	companyname='$companyname', 
	buttonimage='$buttonimage', 
	bannerimage='$bannerimage', 
	urllink='$urllink', 
	smalldesc='$smalldesc',  
	largedesc='$largedesc',
	howearn='$howearn',
	pay_out='$pay_out',
	brand_power='$brand_power',
	feed_link='$feed_link',
	subcat1='$subcat1',
	subcat2='$subcat2',
	subcat3='$subcat3',
	product='$product',
	brand='$brand'
	

	WHERE id='$advertid'";
	
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Advert: ".$companyname;
	
}

// grab advert data
$q = "SELECT * FROM advert WHERE id='$advertid'";
$qa = mysqli_query($conn,$q);
$qarow = mysqli_fetch_assoc($qa);
$next_advert = $advertid + 1;

//Output
include_once('header.php');
include_once('menu.php');

?>
<style>
.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
	border-radius: 12px;
}

.button2 {background-color: #008CBA; padding: 12px 24px;} /* Blue */
.button3 {background-color: #f44336; padding: 12px 24px;} /* Red */ 
.button4 {background-color: #e7e7e7; color: black; padding: 12px 24px;} /* Gray */ 
.button5 {background-color: #555555; padding: 12px 24px;} /* Black */
.button6 {background-color: #f542e0; padding: 4px 15px;}/* Pink */
</style>

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?> - <a href="advertlook.php?advertid=<?php echo $next_advert; ?>"><button class="button button6">Next Ad</button></a> - <?php echo status_icon($qarow['status']).' '.$qarow['companyname'] ; ?></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			?>
			<div class="col50">
			
				<p class="t-justify">
			<form name="editadvert" method="post" action="advertlook.php?act=3&advertid=<?php echo $advertid; ?>" enctype="application/x-www-form-urlencoded">
			<h3 class="tit">Edit Advert <?php echo $qarow['id'] ; ?> </h3>
			<table>
				<tr>
				    <th>Company Name</th>
				    <td><input type="text" size="40" name="companyname" class="input-text" value="<?php echo $qarow['companyname'] ; ?>" /></td>
				</tr>
				<tr>
				    <th>Button Image 120*60</th>
				    <td><input type="text" size="40" name="buttonimage" class="input-text" value="<?php echo $qarow['buttonimage'] ; ?>" /></td>
				</tr>
				<tr class="bg">
				    <th>Banner Image 468*60</th>
				    <td><input type="text" size="40" name="bannerimage" class="input-text" value="<?php echo $qarow['bannerimage'] ; ?>" /></td>
				</tr>
				<tr>
				    <th>Link URL</th>
				    <td><input type="text" size="40" name="urllink" class="input-text" value="<?php echo $qarow['urllink'] ; ?>" /> 
						<a href="<?php echo $qarow['urllink'] ; ?>" target="_blank"><b>[TEST]</b></a>
						<br>Always Add {CLICKID} For Tracking</td>
				</tr>
				<tr class="bg">
				    <th>Small Description</th>
				    <td><textarea name="smalldesc" cols="75" rows="2" class="input-text"><?php echo $qarow['smalldesc'] ; ?></textarea></td>
				</tr>
				<tr>
				    <th>Large Description</th>
				    <td><textarea name="largedesc" cols="75" rows="6" class="input-text"><?php echo $qarow['largedesc'] ; ?></textarea></td>
				</tr>
				<tr class="bg">
				    <th>How To Earn</th>
				    <td><input type="text" size="40" name="howearn" class="input-text" value="<?php echo $qarow['howearn'] ; ?>" /></td>
				</tr>
				<tr>
				    <th>Pay-Out</th>
				    <td><input type="text" size="40" name="pay_out" class="input-text" value="<?php echo $qarow['pay_out'] ; ?>" /></td>
				</tr>
					<tr class="bg">
				    <th>Brand Power</th>
				    <td>
						<select name="brand_power">						
						<?php 
						$brand_power = $qarow['brand_power'] ; 
						echo '<option value="'.$brand_power.'" selected>'.$brand_power.'</option>';
						?>
						  <option value="1">1</option>
						  <option value="2">2</option>
						  <option value="3">3</option>
						  <option value="4">4</option>
						  <option value="5">5</option>
							
						</select>
						1 = Low / 5 = High.
						</td>
				</tr>
				<tr>
				    <th>Products</th>
				    <td><textarea name="product" cols="75" rows="3" class="input-text"><?php echo $qarow['product'] ; ?></textarea></td>
				</tr>
				
				<tr class="bg">
					
				    <th>Brands</th>
				    <td><textarea name="brand" cols="75" rows="3" class="input-text"><?php echo $qarow['brand'] ; ?></textarea>
					
					</td>
				</tr>
				<tr>
				    <th>Product Feed</th>
				    <td><?php 
						$product_feed = $qarow['productfeed'] ; 
						if($product_feed < 1){//pending show approve
							echo 'NO FEED';
						}else{
							echo 'FEED LIVE';
						}
						?>
					</td>
				</tr>
				<tr class="bg">
				    <th>Feed Link</th>
				    <td><textarea name="feed_link" cols="75" rows="4" class="input-text"><?php echo $qarow['feed_link'] ; ?></textarea></td>
				</tr>
				<tr>
				    <th>Network</th>
				    <td><?php echo network_search($qarow['network']) ; ?></td>
				</tr>
				<tr class="bg">
				    <th>Status</th>
				    <td valign="middle"><?php echo status_icon($qarow['status']) ; 

						?></td>
				</tr>
				<tr>
				    <th>Sub Cat 1</th>
				    <td><select name="subcat1">
						<?php 
						echo subcat_search_return($qarow['subcat1']) ; 
						//get options
						$q1 = "SELECT * FROM cats ORDER BY name";
						$qa1 = mysqli_query($conn,$q1);
						while($qa1row = mysqli_fetch_array($qa1)){
							echo '<option value="'.$qa1row['id'].'">'.$qa1row['name'].'</option>';
						}
						?></select>
						</td>
				</tr>
				<tr class="bg">
				    <th>Sub Cat 2</th>
				    <td><select name="subcat2">
						<?php 
						echo subcat_search_return($qarow['subcat2']) ; 
						//get options
						$q1 = "SELECT * FROM cats ORDER BY name";
						$qa1 = mysqli_query($conn,$q1);
						while($qa1row = mysqli_fetch_array($qa1)){
							echo '<option value="'.$qa1row['id'].'">'.$qa1row['name'].'</option>';
						}
						?></select></td>
				</tr>
				<tr>
				    <th>Sub Cat 3</th>
				    <td><select name="subcat3">
						<?php 
						echo subcat_search_return($qarow['subcat3']) ; 
						//get options
						$q1 = "SELECT * FROM cats ORDER BY name";
						$qa1 = mysqli_query($conn,$q1);
						while($qa1row = mysqli_fetch_array($qa1)){
							echo '<option value="'.$qa1row['id'].'">'.$qa1row['name'].'</option>';
						}
						?>
						</select></td>
				</tr>
				<tr>
				    <th>Submit</th>
				    <td align="center"><input type="submit" class="input-submit" value="Submit" /></td>
				</tr>
			
			</table>
			
			</form>
				
				</p>
				
			</div> <!-- /col50 -->

			<div class="col50 f-right">
			
				<p class="t-justify">
					<h3 class="tit">Advert <?php echo $qarow['id'] ; ?> Action Buttons</h3>
				<table class="nostyle">
				<tr>
				    <th><a href="advertlook.php?advertid=<?php echo $advertid; ?>"><button class="button button2">Refresh Advert</button></a></th>
				    <th><a href="import_max.php?id=<?php echo $qarow['id'] ; ?>" target="_blank"><button class="button button5">Feed Update</button></a></th>
				    <th><a href="deletefeed.php?id=<?php echo $qarow['id'] ; ?>" target="_blank"><button class="button button3">Delete Feed</button></a></th>
				</tr>
				<tr>
				    <td><a href="import_cat.php?id=<?php echo $advertid ; ?>" target="_blank"><button class="button button4">Update Brands</button></a></td>
				    <td>
						<?php 
						$product_feed = $qarow['productfeed'] ; 
						if($product_feed < 1){//pending show approve
							echo '<a href="advertlook.php?act=2&newstatus=1&advertid='.$advertid.'"><button class="button">Activate Feed</button></a>';
						}else{
							echo '<a href="advertlook.php?act=2&newstatus=0&advertid='.$advertid.'"><button class="button button3">Suspend Feed</button></a>';
						}
						?>
						</td>
				    <td>
						<?php 
						$status = $qarow['status'];
						if($status =="0"){//pending show approve
							echo '<a href="advertlook.php?act=1&newstatus=1&advertid='.$advertid.'"><button class="button">Approve Advert</button></a>';
						}
						if($status =="1"){//approved show suspend
							echo '<a href="advertlook.php?act=1&newstatus=2&advertid='.$advertid.'"><button class="button button3">Suspend Advert</button></a>';
						}
						if($status =="2"){//Suspended show approve
							echo '<a href="advertlook.php?act=1&newstatus=1&advertid='.$advertid.'"><button class="button">Approve Advert</button></a>';
						}

						?>
					</td>
				</tr>
			</table>
			
				<h3 class="tit">Advert <?php echo $qarow['id'] ; ?> Stats</h3>

			<table class="nostyle">
				<tr>
				    <th>Clicks</th>
				    <td><?php echo $qarow['clicks'] ; ?></td>
				</tr>
				<tr>
				    <th>Earnings</th>
				    <td>&pound;<?php echo $qarow['earnings'] ; ?></td>
				</tr>
				<tr>
				    <th>Date Added</th>
				    <td><?php echo $qarow['timestamp'] ; ?></td>
				</tr>
				<?php
				//if it has a ACTIVE feed show the below:
				if($qarow['productfeed']=="1"){
					$qt = "SELECT * FROM products WHERE advertId='$advertid'";
					$qta = mysqli_query($conn,$qt);
					$qtn = mysqli_num_rows($qta);
					
				?>
				<tr>
				    <th>Feed Updated<br></th>
				    <td><?php 
						$feed_updated = date("d-m-Y",$qarow['last_update']);
						echo  $feed_updated; ?></td>
				</tr>
				<tr>
				    <th>Feed Size</th>
				    <td>
						<?php 
						if($qarow['lge']=="1"){	echo  'Large ('.$qarow['feed_size'].'mb) '.$qtn.' Products';}
						if($qarow['lge']=="0"){	echo  'Small ('.$qarow['feed_size'].'mb) '.$qtn.' Products';}
						if($qarow['lge']=="2"){	echo  'Excessive ('.$qarow['feed_size'].'mb) '.$qtn.' Products';}
						 ?></td>
				</tr>
				<?php } ?>

				<tr>
				    <th>Approved Sales</th>
				    <td><?php echo advert_count_approved_sales($advertid); ?></td>
				</tr>
				<tr>
				    <th>Pending Sales</th>
				    <td><?php echo advert_count_pending_sales($advertid);  ?></td>
				</tr>
				<tr>
				    <th>Cancelled Sales</th>
				    <td><?php echo advert_count_cancelled_sales($advertid); ?></td>
				</tr>
				<tr>
				    <th>Last Approved Sale</th>
				    <td><?php advert_last_sale_date($advertid);  ?></td>
				</tr>
				
			</table>	
				
		<h3 class="tit">Advert <?php echo $qarow['id'] ; ?> Images</h3>
				<h5>Button Image</h5>
				<img src="<?php echo $qarow['buttonimage'] ; ?>" width="120" height="60">
				<h5>Banner Image</h5>
				<img src="<?php echo $qarow['bannerimage'] ; ?>" width="468" height="60">
				<br>
				<br> <h3>Products In Database: <?php echo $qtn ; ?></h3>
				<?php
				$qt = "SELECT * FROM products WHERE advertId='$advertid' ORDER BY id ASC limit 3";
				$qta = mysqli_query($conn,$qt);
				while($qtrow=mysqli_fetch_array($qta)){
					echo '<h5>'.$qtrow['prod_name'].'</h5><img src="'.$qtrow['aw_img'].'" width="150" height="150"><br><a href="'.$qtrow['aw_deeplink'].'" target="_blank">[DEEPLINK TEST]</a>';
				}
			
				?>
				
				</p>
				
			</div> <!-- /col50 -->

		</div> <!-- /content -->


<?php
include_once('footer.php');
?>