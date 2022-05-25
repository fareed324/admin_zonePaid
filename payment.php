<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Payments To Users";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];

// get ACT=1 Add New DB Entry
if($act=="1"){
	//get payment details
	$q = "SELECT * FROM payment WHERE id='$id'";
	$qa = mysqli_query($conn,$q);
	$qrow = mysqli_fetch_array($qa);
	$userid = $qrow['userid'];
	$payment_amt = $qrow['payment'];
	$payment_method = $qrow['method'];
	$pay_type = $qrow['type'];
	//get user details
	$q2 = "SELECT * FROM users WHERE id='$userid'";
	$qa2 = mysqli_query($conn,$q2);
	$qrow2 = mysqli_fetch_array($qa2);
	$user_paid_balance = $qrow2['paid'];
	$emailid = 'payment';
	
	//deduct from correct balance
	$new_user_paid_balance = $user_paid_balance - $payment_amt;
	$q5 = "UPDATE users SET paid='$new_user_paid_balance' WHERE id='$userid'";
	$qa5 = mysqli_query($conn,$q5);
	
	//update payment change status to 1 = Paid
	$q5 = "UPDATE payment SET status='1', paid_date='$today', method='$payment_method' WHERE id='$id'";
	$qa5 = mysqli_query($conn,$q5);
	send_mail($userid, $emailid, $testemail);
	$type = "3";
	$mess = 'Payment Made To UserID: '.$userid.' Total Value: &pound;'.$payment_amt;

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
			<h3 class="tit">Payments To Be Made By PayPal</h3>
			<table>
				<tr>
				    <th>Name</th>
				    <th>Type</th>
				    <th>Ammount</th>
				    <th>Paid Too</th>
				    <th>Action</th>
				</tr>
				<?php
				$colcount = '0';
				// get all the data
				$q = "SELECT * FROM payment WHERE status < 1 ORDER BY type ASC";
				$qa = mysqli_query($conn,$q);
				//$qn = mysqli_num_rows($qa);
				//echo $qn;
				while($qarow = mysqli_fetch_array($qa)){
					$colcount = $colcount + 1;
					$type = $qarow['type'];
					if($type=="1"){$type_show = 'User Acct';}
					if($type=="2"){$type_show = 'Referrer Acct';}
					$userid = $qarow['userid'];
					//get user details
					$q1 = "SELECT * FROM users WHERE id='$userid'";
					$qa1 = mysqli_query($conn,$q1);
					$qrow1 = mysqli_fetch_assoc($qa1);
					$email = $qrow1['username'];
					
					echo '
					<form name="add" method="post" action="payment.php?act=1&id='.$qarow['id'].'">
					<tr'.table_background($colcount).'>
						<td><a href="lookuser.php?id='.$qarow['userid'].'">'.get_users_name($userid).'</a></td>
						<td>'.$type_show.'</td>
						<td>&pound;'.$qarow['payment'].'</td>
						<td>'.$email.'</td>
						<td><input type="submit" class="input-submit" value="Pay" /></td>
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