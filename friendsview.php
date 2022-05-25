<?php
include_once('connect.php');
include_once('logincheck.php');
include_once('functions.php');

$admin_level_permission = "1";
$page_title = "VIEW FRIENDS OF ";
admin_level_check($admin_level_permission);

// Get Variables Passed
$act = $_REQUEST['act'];
$id = $_REQUEST['id'];

// get ACT=1 Add New DB Entry

//Grab the Data from USER
$q = "SELECT * FROM users WHERE id='$id'";
$qa = mysqli_query($conn,$q);
$qarow = mysqli_fetch_assoc($qa);

//Output
include_once('header.php');
include_once('menu.php');

?>


		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1><?php echo $page_title ; ?><?php echo $qarow['name'].' '.$qarow['surname']  ; ?> <a href="lookuser.php?id=<?php echo $id; ?>">[VIEW]</a></h1>
			<?php  ; ?></h1>
			<?php
			if ($act > "0"){ echo system_message($type, $mess);}
			$q2 = "SELECT * FROM users WHERE referrer='$id'";
			$qa2 = mysqli_query($conn,$q2);
			$qan2 = mysqli_num_rows($qa2);
			?>
			<h3 class="tit">Total Friends <?php echo  $qan2 ;?></h3>
			<table>
				<tr>
				    <th>Name</th>
				    <th>Status</th>
				    <th>Pending</th>
				    <th>Available</th>
					<th>Aff Cash</th>
				    <th>ACTION</th>
				</tr>
				<?php 
				$colcount = '0';
				while($qarow2=mysqli_fetch_array($qa2)){
					$colcount = $colcount + 1;
				$userstatus = $qarow2['status'];

				if($userstatus=="0"){$showstatus = '<font color="#d9a60f">Pending</font>'; 
									 $statusbutton = '<button class="button button1" onClick="location.href=\'lookuser.php?id='.$id.'&act=2&status=1\'">ACTIVATE</button>';}
				if($userstatus=="1"){$showstatus = '<font color="#39803D">Active</font>';
									$statusbutton = '<button class="button button3" onClick="location.href=\'lookuser.php?id='.$id.'&act=2&status=2\'">SUSPEND</button>';}
				if($userstatus=="2"){$showstatus = '<font color="#fc0b03">Suspended</font>';
									$statusbutton = '<button class="button button1" onClick="location.href=\'lookuser.php?id='.$id.'&act=2&status=1\'">ACTIVATE</button>';}
				echo '
								<tr'.table_background($colcount).'>
				    <td>'.$qarow2['name'].' '.$qarow2['surname'].'</td>
				    <td>'.$showstatus.'</td>
				    <td>&pound;'.$qarow2['cash'].'</td>
				    <td>&pound;'.$qarow2['paid'].'</td>
					<td>&pound;'.$qarow2['affcash'].'</td>
				    <td><a href="lookuser.php?id='.$qarow2['id'].'" target="_blank">[VIEW]</a></td>
				</tr>
				';
}
				?>

			</table>

		</div> <!-- /content -->


<?php
include_once('footer.php');
?>