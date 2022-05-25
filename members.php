<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "Member Services";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];
$search_term = $_REQUEST['search_term'];
$type = $_REQUEST['type'];


// get ACT=1 Add New DB Entry

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
<form name="search" method="post" action="members.php?act=1">
			<fieldset>
				<legend>Search For Member</legend>
				<table class="nostyle">
					<tr>
						<td style="width:70px;">Input:</td>
						<td><input type="text" size="40" name="search_term" class="input-text" /></td>
					</tr>
					<tr>
						<td>Type:</td>
						<td> <select name="type">
							  <option value="surname ">Surname</option>
							  <option value="email">Email</option>
							  <option value="id">id</option>
							</select> </td>
					</tr>
					<tr>
						<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Submit" /></td>
					</tr>
				</table>
			</fieldset>
			
			</form>
			<?php
				if($act=="1"){
					$colcount = '0';
					$q = "SELECT * FROM users WHERE $type='$search_term' ORDER BY id";
					//echo $q;
					$qa = mysqli_query($conn,$q);
					$qn = mysqli_num_rows($qa);
					if($qn > "0"){
						echo '			
						<h3 class="tit">Users Found: '.$qn.'</h3>
						<table>
							<tr>
								<th>Name - (ID)</th>
								<th>Email</th>
								<th>User Balance (Paid)</th>
								<th>Ref Balance</th>
								<th>Action</th>
							</tr>
';
						while($qrow = mysqli_fetch_array($qa)){
							$colcount = $colcount + 1;	
							echo '
							<tr'.table_background($colcount).'>
								<td>'.$qrow['name'].' '.$qrow['surname'].' ('.$qrow['id'].')</td>
								<td>'.$qrow['email'].'</td>
								<td>&pound;'.$qrow['cash'].' (&pound;'.$qrow['paid'].')</td>
								<td>&pound;'.$qrow['affcash'].'</td>
								<td><a href="lookuser.php?id='.$qrow['id'].'" target="_blank">[VIEW]</a></td>
							</tr>

							';
						}
						echo '</table>';
					}else{
						echo '<h1>No User Found</h1>';
					}

				}
			
			
			?>

			
		</div> <!-- /content -->


<?php
include_once('footer.php');
?>