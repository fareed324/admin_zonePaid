<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');
include_once('header.php');
include_once('menu.php');

//count products
$qp = "SELECT id FROM products";
$qpa = mysqli_query($conn,$qp);
$qpn = mysqli_num_rows($qpa);

// count clicks
$qc = "SELECT * FROM click";
$qpc = mysqli_query($conn,$qc);
$qpcn = mysqli_num_rows($qpc);

// count users
$qu = "SELECT * FROM users";
$qpu = mysqli_query($conn,$qu);
$qpun = mysqli_num_rows($qpu);

?>


		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1>DashBoard</h1>
			<p>Welcome to PaidCash admin centre. Please find all your options on the left menu :)</p>
			<h3>Adverts</h3>
			<table class="nostyle">
				<tr>
				    <th>Network</th>
				    <th>Activated</th>
				    <th>Suspended</th>
				    <th>Pending</th>
				</tr>
				<?php
				$colcount = '0';
				//show networks and adverts
				$q = "SELECT * FROM network ORDER BY name DESC";
				$qa = mysqli_query($conn,$q);
				while($qrow=mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$networkid = $qrow['id'];
					echo '
						<tr'.table_background($colcount).'>
							<td>'.$qrow['name'].'</td>
							<td>'.network_number($networkid,'1').'</td>
							<td>'.network_number($networkid,'2').'</td>
							<td>'.network_number($networkid,'0').'</td>
						</tr>					
					';
				}
				?>

			</table>
			
			<h3>Live Products: <?php echo $qpn; ?></h3>
			
			<h3>Members: <?php echo $qpun; ?></h3>
			<table class="nostyle">
				<tr>
				    <th>Activated</th>
				    <th>Suspended</th>
				    <th>Pending</th>
				</tr>
				<?php
				$colcount = '0';
				//show networks and adverts
					echo '
						<tr'.table_background($colcount).'>
							<td>'.users_number('1').'</td>
							<td>'.users_number('2').'</td>
							<td>'.users_number('0').'</td>
						</tr>					
					';
				?>
				</table>

			
			
			<h3>Live Clicks: <?php echo $qpcn; ?></h3>
			


		</div> <!-- /content -->


<?php
include_once('footer.php');
?>