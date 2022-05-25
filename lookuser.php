<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "User Info";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$addnote = $_REQUEST['addnote'];
$status = $_REQUEST['status'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$q3 = "INSERT INTO usernotes (userid, note, timestamp) VALUES('$id', '$addnote', '$today') ";
	$qa3 = mysqli_query($conn,$q3);
	$type = "3";
	$mess = "Added New User Note";
	
}

if ($act == "2"){
	$q5 = "UPDATE users SET status='$status' WHERE id='$id'";
	$qa5 = mysqli_query($conn,$q5);
	$type = "3";
	$mess = "UPDATED USER status";
	
}


//Grab Data USER
$q = "SELECT * FROM users WHERE id='$id'";
$qa = mysqli_query($conn,$q);
$qarow = mysqli_fetch_assoc($qa);
$userstatus = $qarow['status'];
$userid = $qarow['id'] ;
if($userstatus=="0"){$showstatus = '<font color="#d9a60f">Pending</font>'; 
					 $statusbutton = '<button class="button button1" onClick="location.href=\'lookuser.php?id='.$id.'&act=2&status=1\'">ACTIVATE</button>';}
if($userstatus=="1"){$showstatus = '<font color="#39803D">Active</font>';
					$statusbutton = '<button class="button button3" onClick="location.href=\'lookuser.php?id='.$id.'&act=2&status=2\'">SUSPEND</button>';}
if($userstatus=="2"){$showstatus = '<font color="#fc0b03">Suspended</font>';
					$statusbutton = '<button class="button button1" onClick="location.href=\'lookuser.php?id='.$id.'&act=2&status=1\'">ACTIVATE</button>';}

$dateshow = date_sort_uk($qarow['timestamp']);
//grab click stats for user



//Output
include_once('header.php');
include_once('menu.php');

?>


		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?> Account Number: <?php echo $id; ?></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			?>
			<h3 class="tit">User: <?php echo $qarow['name'].' '.$qarow['surname'].' Status: ['.$showstatus.'] - Member Since: '.$dateshow.' - Friends Referred: '.count_qty_friends($id)  ; ?></h3>
			
			<div class="col50">
			
				<p class="t-justify"><h3 class="tit">User Live Click Stats</h3>
							<table>
				<tr>
				    <th>Pending / Clicks</th>
				    <td><?php echo( count_user_stats($userid, '0')); ?></td>
				</tr>
				<tr>
				    <th>Approved</th>
				    <td><?php echo(count_user_stats($userid, '1')); ?></td>
				</tr>
				<tr class="bg">
				    <th>Paid</th>
				    <td><?php echo(count_user_stats($userid, '3')); ?></td>
				</tr>
				<tr class="bg">
				    <th>Cancelled</th>
				    <td><?php echo(count_user_stats($userid, '2')); ?></td>
				</tr>
								
			</table>

				<h3 class="tit">Last 5 User Transactions <a href="showstatement.php?id=<?php echo $id; ?>" target="_blank"></a></h3>
							<table>
				<tr>
				    <th>ID</th>
				    <th>Merchant</th>
				    <th>Date</th>
				    <th>Comm (&pound;)</th>
				    <th>Status</th>
				</tr>
				<?php
								$q3 = "SELECT * FROM click WHERE userid='$userid' ORDER BY clickid DESC LIMIT 5";
								$qa3 = mysqli_query($conn,$q3);
								$colcount = '0';
								while($qrow3 = mysqli_fetch_array($qa3)){
									$colcount = $colcount + 1;
									$transSTATUS = $qrow3['status'];
									if($transSTATUS=='0'){$showstatusTRANS = 'PENDING';}
									if($transSTATUS=='1'){$showstatusTRANS = 'APPROVED';}
									if($transSTATUS=='2'){$showstatusTRANS = 'CANCELLED';}
									if($transSTATUS=='3'){$showstatusTRANS = 'PAID';}
									$dateshow = date_sort_uk($qrow3['timestamp']);
									$advertname = advert_name_search($qrow3['advertid']);
									echo '
									<tr'.table_background($colcount).'>
				    <td>'.$qrow3['clickid'].'</td>
				    <td>'.$advertname.'</td>
				    <td>'.$dateshow.'</td>
				    <td>'.$qrow3['user_commission'].'</td>
				    <td>'.$showstatusTRANS.'</td>
				</tr>
									';
								}
				?>
				
			</table>

				<h3 class="tit">Last 5 User Payments</h3>
							<table>
				<tr>
				    <th>ID</th>
				    <th>Requested Date</th>
				    <th>Paid Date</th>
				    <th>Payment (&pound;)</th>
				    <th>Status</th>
				</tr>
				<?php
								$q3 = "SELECT * FROM payment WHERE userid='$userid' ORDER BY id DESC LIMIT 5";
								$qa3 = mysqli_query($conn,$q3);
								$colcount = '0';
								while($qrow3 = mysqli_fetch_array($qa3)){
									$colcount = $colcount + 1;
									$transSTATUS = $qrow3['status'];
									if($transSTATUS=='0'){$showstatusTRANS = 'PENDING';}
									if($transSTATUS=='1'){$showstatusTRANS = 'PAID';}
									$Rdateshow = date_sort_uk($qrow3['request_date']);
									$Pdateshow = date_sort_uk($qrow3['paid_date']);
									echo '
									<tr'.table_background($colcount).'>
				    <td>'.$qrow3['id'].'</td>
				    <td>'.$Rdateshow.'</td>
				    <td>'.$Pdateshow.'</td>
				    <td>'.$qrow3['payment'].'</td>
				    <td>'.$showstatusTRANS.'</td>
				</tr>
									';
								}
				?>
				
			</table>
				<form name="addNote" method="post" action="lookuser.php?id=<?php echo $userid; ?>&act=1">
				<fieldset>
				<legend>Add Note</legend>
				<table class="nostyle">
					<tr>
						<td class="va-top">Details:</td>
						<td><textarea name="addnote" cols="50" rows="7" class="input-text"></textarea></td>
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
			
				<p class="t-justify"><h3 class="tit">User Deatils</h3>
							<table>
				<tr class="bg">
				    <th>Name</th>
				    <td><?php echo $qarow['name'].' '.$qarow['surname']  ;?></td>
				</tr>
				<tr>
				    <th>Address</th>
				    <td><?php echo $qarow['address']; ?><br>
<?php echo $qarow['address2']; ?><br>
<?php echo $qarow['town']; ?><br>
<?php echo $qarow['county']; ?><br>
<?php echo $qarow['postcode']; ?>

						
					</td>
				</tr>
				<tr class="bg">
				    <th>Contacts</th>
				    <td>(T)<?php echo $qarow['tel']; ?><br>
(M)<?php echo $qarow['mobile']; ?></td>
				</tr>
				<tr>
				    <th>Email<br>Username</th>
				    <td><?php echo $qarow['email']; ?><br>
<?php echo $qarow['username']; ?></td>
				</tr>
				<tr class="bg">
				    <th>Last Login</th>
				    <td><?php 
						$dateshow = date_sort_uk($qarow['lastlogin']);
						echo $dateshow; ?></td>
				</tr>
				<tr>
				    <th>Referrer</th>
				    <td>(<?php echo $qarow['referrer'];  ?>) <?php echo(get_users_name($qarow['referrer'])); ?> <a href="lookuser.php?id=<?php echo $qarow['referrer'];  ?>" target="_blank">[VIEW]</a></td>
				</tr>

				<tr class="bg">
				    <th>Balances</th>
				    <td>Pending: &pound;<?php echo $qarow['cash']; ?><br>
Approved: &pound;<?php echo $qarow['paid']; ?><br>
Refer Acc: &pound;<?php echo $qarow['affcash']; ?></td>
				</tr>
				<tr>
				    <th>Last Note</th>
				    <td>
					<?php   
						$q4 = "SELECT * FROM usernotes WHERE userid='$userid' ORDER BY id DESC LIMIT 1";
						$qa4 = mysqli_query($conn,$q4);
						$qarow4 = mysqli_fetch_assoc($qa4);
						$dateshow = date_sort_uk($qarow4['timestamp']);
						echo $dateshow.'<br>'.$qarow4['note'];
						?>
					</td>
				</tr>

			</table>

				<h3 class="tit">Actions</h3>
				<table class="nostyle">
				<tr>
				    
				    <td><button class="button button5" formtarget="_blank" onClick="location.href='showstatement.php?id=<?php echo $id; ?>'">STATEMENTS &amp; NOTES</button></td>
					<td><?php echo $statusbutton; ?></td>
				    <td></td>
				    <td></td>
				    <td></td>
				</tr>
				<tr>
				    <td><button class="button button2" formtarget="_blank" onClick="location.href='addtransaction.php?id=<?php echo $id; ?>&act=2&status=1'">+ CLICK TRANSACTION</button></td>
				    <td><button class="button button4" formtarget="_blank" onClick="location.href='friendsview.php?id=<?php echo $id; ?>&act=2&status=1'">FRIENDS</button></td>
				    <td></td>
				    <td></td>
				    <td></td>
				</tr>
			</table>

				</p>
				
			</div> <!-- /col50 -->

		</div> <!-- /content -->


<?php
include_once('footer.php');
?>