	<hr class="noscreen" />

	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">

			<div class="padding box">

				<!-- Logo (Max. width = 200px) -->
				<p id="logo"><a href="https://plantback.co.uk" target="_blank"><img src="images/logo.png" alt="Our logo" title="Visit Site" /></a></p>


			</div> <!-- /padding -->

			<ul class="box">
				<?php
				/// grab all cat details from main & sub cats
				$q = "SELECT * FROM adminmaincat ORDER BY name";
				$qa = mysqli_query($conn,$q);
				//$qn = mysqli_num_rows($qa);
				//echo $qn;
				
				while($main_cat_name = mysqli_fetch_array($qa)){
					
					$maincatid = $main_cat_name['id'];
					$maincatname = $main_cat_name['name'];
					
					echo '<li><a href="#">'.$maincatname.'</a> 
					<ul>';
					
					$q2 = "SELECT * FROM adminsubcats WHERE maincatid='".$maincatid."' ORDER BY name";
					$qa2 = mysqli_query($conn,$q2);
					//$qn2 = mysqli_num_rows($qa2);
					//echo $qn2;

					
					while($sub_cat_info = mysqli_fetch_array($qa2)){
						//grab all sub cats
						$sub_cat_id = $sub_cat_info['id'];
						$sub_cat_name  = $sub_cat_info['name'];
						$sub_cat_link = $sub_cat_info['link'];
						echo '<li><a href="'.$sub_cat_link.'">'.$sub_cat_name.'</a></li>';
						}
					
					echo '</ul>
				</li>';
					}
					
				
				?>
			</ul>

		</div> <!-- /aside -->

		<hr class="noscreen" />