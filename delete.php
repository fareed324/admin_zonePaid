<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "2";
$page_title = "Delete/Reject Transactions";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$value = $_REQUEST['value'];

// get ACT=1 Add New DB Entry
if($act =="1"){
	$colcount = 0;
	$money_value = 0;
	$affil_money_value = 0;
	$company_money_value = 0;
	//get all click info
	$transactions = explode("\n", $value);
	foreach ($transactions as $v) {
		$show = explode(",", $v);
		$clickid = $show['0'];
		//get all click info
		$q = "SELECT * FROM click WHERE clickid='$clickid'";
		$qa = mysqli_query($conn,$q);
		$qrow = mysqli_fetch_assoc($qa);
		$advertid = $qrow['advertid'];
		$userid = $qrow['userid'];
		$referrer_com = $qrow['referrer_comission'];
		$user_com = $qrow['user_commission'];
		$company_com = $qrow['company_commission'];
		$payment = $qrow['payment'];
		$clickstatus = $qrow['status'];
		//counts
		$colcount = $colcount + 1;
		$money_value = $money_value + $user_com;
		$affil_money_value = $affil_money_value + $referrer_com;
		$company_money_value = $company_money_value + $company_com;
		
		//get all user info
		$q = "SELECT * FROM users WHERE id='$userid'";
		$qa = mysqli_query($conn,$q);
		$qrow = mysqli_fetch_assoc($qa);
		$referrer = $qrow['referrer'];
		$user_cash = $qrow['cash'];
			
		//update click Status = 2 - paid = 0 - all coms = 0
		$q5 = "UPDATE click SET status='2', paid='0', user_commission='0', company_commission='0', referrer_comission='0' WHERE clickid='$clickid'";
		$qa5 = mysqli_query($conn,$q5);
		
		//update user remove any coms
		$user_cash = $user_cash - $user_com;
		$q5 = "UPDATE users SET cash='$user_cash' WHERE id='$userid'";
		$qa5 = mysqli_query($conn,$q5);	
	}
	$type = "3";
	$mess = 'Transactions Deleted Total: '.$colcount.' Total User Com Reversed: &pound;'.$money_value.' Total Referrer Reversed: &pound'.$affil_money_value.' Total Company Loss: &pound;'.$company_money_value;

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
<form name="app" method="post" action="delete.php?act=1">
						<fieldset>
				<legend>Delete/Reject Transactions</legend>
				<table class="nostyle">
					<tr>
						<td class="va-top">CLICKID's:</td>
						<td><textarea name="value" cols="75" rows="7" class="input-text"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /><br>
						</td>
					</tr>
				</table>
			</fieldset>

			</form>

		</div> <!-- /content -->


<?php
include_once('footer.php');
?>