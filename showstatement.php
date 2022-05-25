<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "User Statements &amp; Notes";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];

// get ACT=1 Add New DB Entry

// Get Data
$q = "SELECT * FROM users WHERE id='$id'";
$qa = mysqli_query($conn,$q);
$qarow = mysqli_fetch_assoc($qa);


//Output
include_once('header.php');
include_once('menu.php');

?>


		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?> FOR: <?php echo $qarow['name'].' '.$qarow['surname'].' ID:'.$id  ; ?> <a href="lookuser.php?id=<?php echo $id; ?>">[VIEW]</a></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			?>
			<!-- Tabs -->
			<div class="tabs box">
				<ul>
					<li><a href="#tab01"><span>CLICKS</span></a></li>
					<li><a href="#tab02"><span>PAYMENTS</span></a></li>
					<li><a href="#tab03"><span>REFERRAL COMS</span></a></li>
					<li><a href="#tab04"><span>NOTES</span></a></li>
				</ul>
			</div> <!-- /tabs -->

			<!-- Tab01 -->
			<div id="tab01">
			
				<p>
			<h3 class="tit">Pending, Approved, Paid Transactions</h3>
			<table>
				<tr>
				    <th>ID</th>
				    <th>Merchant</th>
				    <th>Date</th>
				    <th>Comm (&pound;)</th>
				    <th>Status</th>
				</tr>
				<?php
				$q3 = "SELECT * FROM click WHERE userid='$id' ORDER BY clickid DESC";
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
				    <td>&pound;'.$qrow3['user_commission'].'</td>
				    <td>'.$showstatusTRANS.'</td>
					</tr>
					';
				}
				
				?>
			</table>

				</p>
			
			</div> <!-- /tab01 -->

			<!-- Tab02 -->
			<div id="tab02">

				<p>
				<h3 class="tit">User Payments</h3>
							<table>
				<tr>
				    <th>ID</th>
				    <th>Requested Date</th>
				    <th>Paid Date</th>
				    <th>Payment (&pound;)</th>
				    <th>Status</th>
				</tr>
				<?php
								$q3 = "SELECT * FROM payment WHERE userid='$id' ORDER BY id DESC";
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
				</p>

			</div> <!-- /tab02 -->

			<!-- Tab03 -->
			<div id="tab03">

				<p>
			<h3 class="tit">Refferal Commission</h3>
			<table>
				<tr>
				    <th>ID</th>
				    <th>Merchant</th>
				    <th>Date</th>
				    <th>Comm (&pound;)</th>
				    <th>Status</th>
				</tr>
				<?php
				$q3 = "SELECT * FROM click WHERE referrer='$id' AND paid='1' ORDER BY clickid DESC";
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
				    <td>&pound;'.$qrow3['referrer_comission'].'</td>
				    <td>'.$showstatusTRANS.'</td>
					</tr>
					';
				}
				
				?>
			</table>
				</p>

			</div> <!-- /tab03 -->
			<!-- Tab04 -->
			<div id="tab04">

				<p>
				<?php 
					$q5 = "SELECT * FROM usernotes WHERE userid='$id' ORDER BY id DESC";
					$qa5 = mysqli_query($conn,$q5);
					while($qarow5 = mysqli_fetch_array($qa5)){
						echo 'Date: '.$qarow5['timestamp'].'<br>
						'.$qarow5['note'].'
						<p>
						';
						
					}
					?>
				</p>

			</div> <!-- /tab04 -->

		</div> <!-- /content -->


<?php
include_once('footer.php');
?>