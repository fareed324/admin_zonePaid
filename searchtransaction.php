<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "Search Transaction";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];

// get ACT=1 Add New DB Entry
if($act =="1"){
	$q = "SELECT * FROM click WHERE clickid='$id'";
	$qa = mysqli_query($conn,$q);
	$qn = mysqli_num_rows($qa);
	$qrow = mysqli_fetch_assoc($qa);
	$advertid = $qrow['advertid'];
	$userid = $qrow['userid'];
	$ipaddr = $qrow['ipaddr'];
	$time_of_click = $qrow['timestamp'];
	$paid = $qrow['paid'];
	$paid_date = $qrow['paid_date'];
	$referer = $qrow['referrer'];
	$basket = $qrow['total_basket'];
	$affilpaid = $qrow['affilpaid'];
	$referrer_com = $qrow['referrer_comission'];
	$user_com = $qrow['user_commission'];
	$company_com = $qrow['company_commission'];
	$payment = $qrow['payment'];
	$status = $qrow['status'];
	$link_click_url = $qrow['link_click_url'];
	$approved_date = $qrow['approved_date'];
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
<form name="transaction" method="post" action="searchtransaction.php?act=1">
						<fieldset>
				<legend>Search For A Transaction</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">ClickID:</td>
						<td><input type="text" size="40" name="id" class="input-text" /></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>

			</form>
			
			<?php
			if($act=="1" AND $qn > "0"){
				if($status=="0"){$showstatus = 'PENDING';}
				if($status=="1"){$showstatus = 'APPROVED';}
				if($status=="2"){$showstatus = 'REJECTED';}
				if($status=="3"){$showstatus = 'PAID';}
				
				echo '
							<h3 class="tit">Click Information</h3>
			<table>
				<tr>
				    <th>Info</th>
				    <td>For Click ID: '.$id.' Status '.$showstatus.'</td>
				</tr>
				<tr>
				    <th>Advert </th>
				    <td><a href="advertlook.php?advertid='.$advertid.'" target="_blank">'.advert_name_search($advertid).'</a> (ID: '.$advertid.')</td>
				</tr>
								<tr>
				    <th>User</th>
				    <td><a href="lookuser.php?id='.$userid.'" target="_blank">'.get_users_name($userid).'</a> (Friends: <a href="friendsview.php?id='.$userid.'" target="_blank">'.count_qty_friends($userid).'</a>)<br>
						IP: ('.$ipaddr.')</td>
				</tr>
				<tr>
				    <th>Referrer</th>
				    <td><a href="lookuser.php?id='.$referer.'" target="_blank">'.get_users_name($referer).'</a> (Other Friends: <a href="friendsview.php?id='.$referer.'" target="_blank">'.count_qty_friends($referer).'</a>)</td>
				</tr>

				<tr>
				    <th>Financial</th>
				    <td>Basket Size: &pound;'.$basket.'<br>
					User Com: &pound;'.$user_com.'<br>
					Referrer Com: &pound;'.$referrer_com.'<br>
					Company Com: &pound;'.$company_com.'<br>
					Overall Com: &pound;'.$payment.'</td>
				</tr>
				<tr>
				    <th>Dates</th>
				    <td>Clicked: '.$time_of_click.'<br>
					Approved: '.$approved_date.'<br>
					Paid: '.$paid_date.'</td>
				</tr>
				<tr>
				    <th>URL Clicked</th>
				    <td>'.$link_click_url.'</td>
				</tr>

			</table>

				';
			}
			?>
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>