<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Approve &amp; Pay Transactions.";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$type = $_REQUEST['type'];
$value = $_REQUEST['value'];

// get ACT=1 Add New DB Entry
if ($act == "1"){
	$colcount = 0;
	$money_value = 0;
	// GET COM %
	if($type =="1"){//approval
		$showSTAT = 'Approved';
		$transactions = explode("\n", $value);
		foreach ($transactions as $v) {
			
			$show = explode(",", $v);
			$clickid = $show['0'];
			$comms = $show['1'];
			$basket = $show['2'];
			//echo 'ClickID: '.$clickid.'<br>Comms: '.$comms.'<br>Basket: '.$basket.'<br>';
			
			//Get click/user/advert info from 'CLICKID'
			$q = "SELECT * FROM click WHERE clickid='$clickid'";
			$qa = mysqli_query($conn,$q);
			$qrow = mysqli_fetch_assoc($qa);
			$advertid = $qrow['advertid'];
			$userid = $qrow['userid'];
			$clickstatus = $qrow['status'];
			
			//check status if its > 0 ignore it as its already approved
			if($clickstatus =="0"){
				$colcount = $colcount + 1;
				$money_value = $money_value + $comms;
				// Get Users Info
				$q = "SELECT * FROM users WHERE id='$userid'";
				$qa = mysqli_query($conn,$q);
				$qrow = mysqli_fetch_assoc($qa);
				$referrer = $qrow['referrer'];
				$user_cash = $qrow['cash'];

				//get advert info
				$q = "SELECT * FROM advert WHERE id='$advertid'";
				$qa = mysqli_query($conn,$q);
				$qrow = mysqli_fetch_assoc($qa);
				$ad_earnings = $qrow['earnings'];
				$ad_earnings = $ad_earnings + $comms;
				$ad_approved = $qrow['approved'];
				$ad_approved = $ad_approved + 1;

				// sort comms
				$comm_user = $comms / 100 * $user_comm;
				$comm_user = number_format($comm_user, 2, '.', '');
				$comm_ref = $comm_user / 100 * $friend_comm;
				$comm_ref = number_format($comm_ref, 2, '.', '');
				//echo 'User Com: '.$comm_user.' Ref Com: '.$comm_ref;

				$company_earnings = $comms - $comm_user;
				$company_earnings = $company_earnings - $comm_ref;
				$$company_earnings = number_format($$company_earnings, 2, '.', '');

				//add user cash to cash earned
				$total_user_cash = $comm_user + $user_cash;

				//Click Tbl => Status=1 - Devide up Comm and add figures - Add Basket Value (If Any)
				$q5 = "UPDATE click SET status='1', payment='$comms', total_basket='$basket', user_commission='$comm_user', referrer_comission='$comm_ref', approved_date='$today', company_commission='$company_earnings' WHERE clickid='$clickid'";
				$qa5 = mysqli_query($conn,$q5);
				//echo 'Updated Click: '.$clickid.' Payment: '.$comms.' Basket: '.$basket.' User Earn: '.$comm_user.' Referrer Earn: '.$comm_ref.'<br>';

				//User Tbl => Add To Cash
				$q5 = "UPDATE users SET cash='$total_user_cash' WHERE id='$userid'";
				$qa5 = mysqli_query($conn,$q5);
				//echo 'Updated User: '.$userid.' New Cash Ammount: '.$total_user_cash.'<br>';

				//Advert Tbl => +1 to Approved - Add To earnings - 
				$q5 = "UPDATE advert SET approved ='$ad_approved', earnings='$ad_earnings' WHERE id='$advertid'";
				$qa5 = mysqli_query($conn,$q5);
				//echo 'Updated Advert: '.$advertid.' Total Add Approved: '.$ad_approved.' Total Ad Earnings: '.$ad_earnings.'<br>';
				}
						
			}
		}

	
		if($type =="2"){ //payment
			
			$showSTAT = 'Paid';
			$transactions = explode("\n", $value);
			foreach ($transactions as $v) {
				
				$show = explode(",", $v);
				$clickid = $show['0'];

				//echo 'ClickID: '.$clickid.'<br>Comms: '.$comms.'<br>';	
				//Get click/user/advert info from 'CLICKID'
				$q = "SELECT * FROM click WHERE clickid='$clickid'";
				$qa = mysqli_query($conn,$q);
				$qrow = mysqli_fetch_assoc($qa);
				$advertid = $qrow['advertid'];
				$userid = $qrow['userid'];
				$referrer_com = $qrow['referrer_comission'];
				$user_com = $qrow['user_commission'];
				$payment = $qrow['payment'];
				$clickstatus = $qrow['status'];
				
				// if clickstatus  == 1 then pay it as already approved
				if($clickstatus =="1"){
					$money_value = $money_value + $payment;
					$colcount = $colcount + 1;
					// Get Users Info
					$q = "SELECT * FROM users WHERE id='$userid'";
					$qa = mysqli_query($conn,$q);
					$qrow = mysqli_fetch_assoc($qa);
					$referrer = $qrow['referrer'];
					$user_cash = $qrow['cash'];
					$user_paid = $qrow['paid'];

					if($referrer > '0'){
						//get referrer details
						$q = "SELECT * FROM users WHERE id='$referrer'";
						$qa = mysqli_query($conn,$q);
						$qrow = mysqli_fetch_assoc($qa);
						$aff_cash = $qrow['affcash'];
						//User Tbl => Add To affcash - (if has referrer) 
						$aff_cash = $aff_cash + $referrer_com;
						$q5 = "UPDATE users SET affcash='$aff_cash' WHERE id='$referrer'";
						$qa5 = mysqli_query($conn,$q5);
						//echo 'Updated User: '.$userid.' New Cash Ammount: '.$total_user_cash.'<br>';

					}


					//Click Tbl => Status=3 - Paid = 1 - Devide up Comm and update figures (Removing OLD Values)
					$q5 = "UPDATE click SET status='3', paid='1', paid_date='$today' WHERE clickid='$clickid'";
					$qa5 = mysqli_query($conn,$q5);

					//User Tbl => Add To Paid  deduct from cash
					$user_cash = $user_cash - $user_com;
					$user_paid = $user_paid + $user_com;
					$q5 = "UPDATE users SET cash='$user_cash', paid='$user_paid' WHERE id='$userid'";
					$qa5 = mysqli_query($conn,$q5);	
				}

			}
		}
		$type = "3";
		$mess = 'Transactions '.$showSTAT.' Total: '.$colcount.' Total Value: &pound;'.$money_value;
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
<form name="app" method="post" action="approvals.php?act=1">
						<fieldset>
				<legend>Approve/Pay Transactions</legend>
				<table class="nostyle">
					<tr>
						<td style="width:50px;">Type:</td>
						<td><select name="type">
							  <option value="1">Approvals</option>
							  <option value="2">Payments</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="va-top">CLICKID's:</td>
						<td><textarea name="value" cols="75" rows="7" class="input-text"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /><br>
							* MAKE SURE THAT YOU ENTER ONLY CLICKID'S ON A NEW LINE<br><br>
							<b>-APPROVALS-</b><br>
							{clickid},{Commission},{BasketValue}<br>
							<br>
							<b>-PAID-</b><br>
							{clickid}
						</td>
					</tr>
				</table>
			</fieldset>

			</form>
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>