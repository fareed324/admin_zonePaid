<?php
/////Funcions/////

function slugify($str) {
    $search = array('Ș', 'Ț', 'ş', 'ţ', 'Ş', 'Ţ', 'ș', 'ț', 'î', 'â', 'ă', 'Î', 'Â', 'Ă', 'ë', 'Ë');
    $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
    $str = str_ireplace($search, $replace, strtolower(trim($str)));
    $str = preg_replace('[^\w\d\-\ ]', '', $str);
    $str = str_replace(' ', '-', $str);
    return $str;
}

//System messages.
function system_message($type, $mess){
	if ($type == "1"){
	$response = '<p class="msg warning">'.$mess.'</p>';
	}
	if ($type == "2"){
	$response = '<p class="msg info">'.$mess.'</p>';
	}
	if ($type == "3"){
	$response = '<p class="msg done">'.$mess.'</p>';	
	}
	if ($type == "4"){
	$response = '<p class="msg error">'.$mess.'</p>';
	}
return $response;
}
//Verify Admin Level
function admin_level_check(){
	
}

// show background blue on multi table fields
function table_background($colcount){
if($colcount % 2 == 0){ 
        echo "";  
    } 
    else{ 
        $response = ' class="bg"'; 
    } 	
//return $response;
}
//search network
function network_search($networkid){
	include('connect.php');
	$q = "SELECT * FROM network WHERE id='$networkid'";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_assoc($qa);
	return $qarow['name'];
}
//count advert approved sales
function advert_count_approved_sales($advertid){
	include('connect.php');
	$q = "SELECT * FROM click WHERE advertid='$advertid' AND status = '1'";
	$qa = mysqli_query($conn,$q);
	$qrn = mysqli_num_rows($qa);	
	return $qrn;
}
//count advert pending sales
function advert_count_pending_sales($advertid){
	include('connect.php');
	$q = "SELECT * FROM click WHERE advertid='$advertid' AND status = '0'";
	$qa = mysqli_query($conn,$q);
	$qrn = mysqli_num_rows($qa);	
	return $qrn;
}
//count advert cancelled sales
function advert_count_cancelled_sales($advertid){
	include('connect.php');
	$q = "SELECT * FROM click WHERE advertid='$advertid' AND status = '2'";
	$qa = mysqli_query($conn,$q);
	$qrn = mysqli_num_rows($qa);	
	//echo $qrn;
	return $qrn;
}
//status icon
function status_icon($status){
	if($status == "0"){ //pending
		$show = '<img src="gfx/help.ico" valign="middle" width="20" height="20"/>';
	}	
	if($status == "1"){ //approved
		$show = '<img src="gfx/Ok-icon.jpg" valign="middle" width="20" height="20"/>';
	}	
	if($status == "2"){ //suspended
		$show = '<img src="gfx/Close-icon.jpg" valign="middle" width="20" height="20"/>';
	}	
	return $show;
}
// find last sale date
function advert_last_sale_date($advertid){
	include('connect.php');
	$q = "SELECT * FROM click WHERE advertid='$advertid' AND status = '1' ORDER BY clickid DESC LIMIT 1";
	$qa = mysqli_query($conn,$q);
	$qrn = mysqli_num_rows($qa);
		
	//echo $qrn;
	if($qrn < "1"){
		echo "No Sales";		
	}else{
		$qarow = mysqli_fetch_array($qa);
		echo $qarow['approved_date'];
	}
	return $response;
	
}
//subcat search and return option
function subcat_search_return($subcat){
	include('connect.php');
	$q = "SELECT * FROM cats WHERE id = '$subcat'";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_array($qa);
	$response = '<option value="'.$qarow['id'].'" selected>'.$qarow['name'].'</option>';
	return $response;
}
//search for advert name
function advert_name_search($advertid){
	include('connect.php');
	$q = "SELECT * FROM advert WHERE id = '$advertid'";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_array($qa);
	$response = $qarow['companyname'];
	return $response;
}
///email send function
function send_mail($userid, $emailid, $testemail){
	include('connect.php');
	//get user credentials
	if($userid == "test"){
		$firstname = "Bobby";
		$surname = "Moore";
		$email = $testemail;
		$username = "no-reply@paidcash.co.uk";
	}else{
		$q = "SELECT * FROM users WHERE id = '$userid'";
		$qa = mysqli_query($conn,$q);
		$qarow = mysqli_fetch_array($qa);
		$firstname = $qarow['name'];
		$surname = $qarow['surname'];
		$email = $qarow['email'];
		$username = $qarow['username'];		
	}
	//get email to send
	$q = "SELECT * FROM emails WHERE id = '$emailid'";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_array($qa);
	$subject = $qarow['subject'];
	$message = $qarow['body'];
	
	//get from detils from config
	$q = "SELECT * FROM emails WHERE id = 'EMS_EMAIL_FROM'";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_array($qa);
	$fromdb = $qarow['value'];
	$fromdb = 'no-reply@paidcash.co.uk';
	
	//exchange tags
	$message = str_replace("[firstname]", $firstname, $message); //av_usermeta > first_name
	$message = str_replace("[surname]", $surname, $message); //av_usermeta > last_name
	$message = str_replace("[username]", $username, $message); //av_usermeta > last_name
	$message = str_replace("[email]", $email, $message); //av_usermeta > last_name
	//Create Date Tags
	$today = date('d/m/Y');
	$month = date('F');
	$timedate = date('d/m/Y - H:i:s');
	$message = str_replace("[today]", $today, $message);
	$message = str_replace("[month]", $month, $message);
	$message = str_replace("[time_now]", $timedate, $message);							
	$message = str_replace("[mer_password]", $pass, $message);			
	$reset  =     "Click this <a href='https://paidcash.co.uk/confirmlink.php?token=$tokenid'>Link</a> to reset your password.";
    $message = str_replace("[password_reset_link]", $reset, $message);
		
	$headers    =  "Content-Type: text/html; charset=iso-8859-1\n";
    $headers   .=  "From: $fromdb\r\r\n";
	$body.=$message;
	
	//send mail
	mail($email, $subject, $body, $headers);		
	//return success
}

//get a users name
function get_users_name($userid){ 
	include('connect.php');
	$q = "SELECT * FROM users WHERE id='$userid'";
	$qa = mysqli_query($conn,$q);
	$qrow = mysqli_fetch_assoc($qa);

	$response = $qrow['name'].' '.$qrow['surname'];

	return $response;
	
}

// Count how many friends have been referred by a user
function count_qty_friends($userid){
	include('connect.php');
	$q = "SELECT * FROM users WHERE referrer='$userid'";
	$qa = mysqli_query($conn,$q);
	$qn = mysqli_num_rows($qa);	
	return $qn;
}
// count user stats as required
function count_user_stats($userid, $status2count){
	include('connect.php');
	$q2 = "SELECT * FROM click WHERE userid='$userid' AND status='$status2count'";
	$qa2 = mysqli_query($conn,$q2);
	$response = mysqli_num_rows($qa2);
	
	return $response;
}
// take any date stored and change it to UK format
function date_sort_uk($date2sort){
	$dateex =  (explode(" ",$date2sort));
	$dateexdate    = $dateex[0];
	$dateextime    = $dateex[1];
	//now switch the date so it shows as in UK DD-MM-YYYY
	$dateex2 =  (explode("-",$dateexdate));
	$newdate = $dateex2[2].'-'.$dateex2[1].'-'.$dateex2[0].' '.$dateextime;
	
	return $newdate;
}
//count network numbers
function network_number($networkid,$status2see){
	include('connect.php');
	$q = "SELECT * FROM advert WHERE network='$networkid' AND status='$status2see'";
	$qa = mysqli_query($conn,$q);
	$qrn = mysqli_num_rows($qa);
	
	return $qrn;
}
//count user numbers
function users_number($status2see){
	include('connect.php');
	$q = "SELECT * FROM users WHERE validated='$status2see'";
	$qa = mysqli_query($conn,$q);
	$qrn = mysqli_num_rows($qa);
	
	return $qrn;
}

?>