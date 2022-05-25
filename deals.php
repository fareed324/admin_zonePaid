<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "ADD EDIT DEALS";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$advertid = $_REQUEST['advertid'];


$startdate = $_REQUEST['startdate'];
$finishdate = $_REQUEST['finishdate'];
$dealdiscount = $_REQUEST['discount'];
$dealcashback = $_REQUEST['cashback'];
$type = $_REQUEST['type'];
$imageurl = $_REQUEST['imageurl'];
$socialimageurl = $_REQUEST['socialimageurl'];
$newstatus = $_REQUEST['newstatus'];

if ($act == "2"){
	$q5 = "UPDATE deals SET status='$newstatus' WHERE id='$id'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED Deal ID:".$id;
	
}


// get ACT=1 Add New DB Entry


if ($act == "1"){

	$dealtitle = $_REQUEST['dealtitle'];
	$dealslug = slugify($dealtitle);
	//die($dealslug);

    
	$target_dir = "../deals/images/banner/";
	$target_file = $target_dir . basename($_FILES["imageurl"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$bannerfilename = uniqid().".".$imageFileType;
	$target_file = $target_dir .$bannerfilename;

	if(isset($_FILES['imageurl']) && !empty($_FILES['imageurl']) && $_FILES["imageurl"]["tmp_name"] != "")
	{	
		// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES["imageurl"]["tmp_name"]);
			if($check !== false) {
				//echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				$mess2 = "File is not an image.";
				$uploadOk = 0;
			}
		
		// Check if file already exists
		if (file_exists($target_file)) {
			$mess2 = "Sorry, file already exists.";
			$uploadOk = 0;
		}
		
		// Check file size
		if ($_FILES["imageurl"]["size"] > 500000) {
			$mess2 = "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$mess2 = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
	}

	$target_dir = "../deals/images/social/";
	$socialtarget_file = $target_dir . basename($_FILES["socialimageurl"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($socialtarget_file,PATHINFO_EXTENSION));
	$socialfilename = uniqid().".".$imageFileType;
	$socialtarget_file = $target_dir .$socialfilename;
	if(isset($_FILES['socialimageurl']) && !empty($_FILES['socialimageurl']) && $_FILES["socialimageurl"]["tmp_name"] != "")
	{	
		// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES["socialimageurl"]["tmp_name"]);
			if($check !== false) {
				//echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				$mess2 = "File is not an image.";
				$uploadOk = 0;
			}
		
		// Check if file already exists
		if (file_exists($socialtarget_file)) {
			$mess2 = "Sorry, file already exists.";
			$uploadOk = 0;
		}
		
		// Check file size
		if ($_FILES["socialimageurl"]["size"] > 500000) {
			$mess2 = "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$mess2 = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
	}
	
	if($dealslug != "")
	{
		$slugquery = "Select * from deals WHERE slug = '$dealslug' ";
		$slugresult = mysqli_query($conn,$slugquery);

		if(mysqli_num_rows($slugresult))
		{
			$mess2 = "Slug already in use";
			$uploadOk = 0;
		}


	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		//echo "Sorry, your file was not uploaded.";
		
	// if everything is ok, try to upload file
	} else 
	{
		if (move_uploaded_file($_FILES["imageurl"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["socialimageurl"]["tmp_name"], $socialtarget_file)) 
		{
			//echo "The file ". basename( $_FILES["imageurl"]["name"]). " has been uploaded.";
			$imageurl2 = $_FILES["imageurl"]["name"];
			$q3 = "INSERT INTO deals (
			advertid,
			slug,
			title,
			startdate,
			finishdate,
			discount,
			cashback,
			status,
			offertype,
			imageurl,
			socialimageurl
			) VALUES(
			'$advertid',
			'$dealslug',
			'$dealtitle',
			'$startdate',
			'$finishdate',
			'$dealdiscount',
			'$dealcashback',
			'1',
			'$type',
			'$bannerfilename',
			'$socialfilename'
			) ";

			echo $q3;

			$qa3 = mysqli_query($conn,$q3);
			$type = "3";
			$mess = "Added New Deal";
			
		} else {
			//echo "Sorry, there was an error uploading your file.";
			$type = "1";
			$mess = 'THERE IS AN ERROR '.$mess2;
		}
	}	
		
}


//Output
include_once('header.php');
include_once('menu.php');

?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  $( function() {
    $( "#datepicker2" ).datepicker();
  } );

  </script>

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			?>
			<!-- 2 columns -->
			<h3 class="tit">DEALS</h3>
			
			<div class="col50">
			
				<p class="t-justify">
				<form name="addeditdeals" method="post" action="deals.php?act=1" enctype="multipart/form-data">
			<fieldset>
				<legend>Add Deal</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">AdvertID:</td>
						<td>
						 <select name="advertid">
							<?php
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
						<td>Title:</td>
						<td><input type="text" size="40" name="dealtitle" id="dealtitle" class="input-text" /></td>
					</tr>

					<tr>
						<td>Start Date:</td>
						<td><input type="text" size="40" name="startdate" id="datepicker" class="input-text" /></td>
					</tr>
					<tr>
						<td>Finish Date:</td>
						<td><input type="text" size="40" name="finishdate" id="datepicker2" class="input-text" /></td>
					</tr>
					<tr>
						<td>Discount:</td>
						<td><input type="number" size="40" name="discount" id="discount" class="input-text" /></td>
					</tr>
					<tr>
						<td>Cashback:</td>
						<td><input type="number" size="40" name="cashback" id="cashback" class="input-text" /></td>
					</tr>
					<tr>
						<td>Offer Details:</td>
						<td>
							<input type="text" size="40" name="type" class="input-text" />
						</td>
					</tr>
					<tr>
						<td>Background Image Upload:</td>
						<td><input type="file" name="imageurl" id="imageurl"></td>
					</tr>
					<tr>
						<td>Social Image Upload:</td>
						<td><input type="file" name="socialimageurl" id="socialimageurl"></td>
					</tr>

					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>
				
				</form>
				</p>
				
			</div> <!-- /col50 -->

			<div class="col50 f-right">
			
				<p class="t-justify">
			<h3 class="tit">Deals List</h3>
			<table>
				<tr>
				    <th>Advert</th>
				    <th>Dates</th>
				    <th>Status</th>
				    <th>Type</th>
				    <th>Stats</th>
					<th>Actions</th>
				</tr>
				<?php
				$colcount = '0';
				// get all the data
				$q = "SELECT * FROM deals ORDER BY id DESC";
				$qa = mysqli_query($conn,$q);
				while($qarow = mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$advertid = $qarow['advertid'];
					$status = $qarow['status'];
					if($status=="1"){ $showstatusaction = '<a href="deals.php?act=2&newstatus=2&id='.$qarow['id'].'">[SUSPEND]</a>';}
					if($status=="2"){ $showstatusaction = '<a href="deals.php?act=2&newstatus=1&id='.$qarow['id'].'">[ACTIVATE]</a>';}
					if($status=="3"){ $showstatusaction = '<a href="#">[ENDED]</a>';}//ended
					echo '
				<tr'.table_background($colcount).'>
				    <td>'.advert_name_search($advertid).'<br>
					<a href="../deals/'.$qarow['imageurl'].'" target="_blank">[IMAGE]</a>
					</td>
				    <td>'.$qarow['startdate'].'<br>'.$qarow['finishdate'].'</td>
				    <td><br>'.status_icon($status).'</td>
				    <td>'.$qarow['offertype'].'</td>
				    <td>Hits: '.$qarow['hits'].'<br>
					Signups: '.$qarow['signups'].'
					</td>
					<td>'.$showstatusaction.'</td>
				</tr>

					';
				}
				?>				
			</table>
				
				</p>
				
			</div> <!-- /col50 -->
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>