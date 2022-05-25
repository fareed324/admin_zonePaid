<?php

include_once('connect.php');
$run_id = $_REQUEST['id'];
echo 'Running MANUAL Product Injection for ADVERT ID: '.$run_id.'<BR>' ;
ini_set('memory_limit', '512M'); //Raise to 512 MB

$timestamp = date("U");
$seconds_per_day = '2678400';// every 31 days
//$seconds_per_day = '1';
$check_age = $timestamp - $seconds_per_day;
$tablename = 'products';

$logText = "";

//get the next network

try
{
		//$x = " SELECT * FROM advert WHERE productfeed='1' AND status='1' AND last_update < '$check_age' AND lge > '0' ORDER BY last_update DESC LIMIT 1 ";
		//$x = " SELECT * FROM advert WHERE productfeed='1' AND status='1' AND last_update < '$check_age' AND lge <'1' ORDER BY last_update DESC LIMIT 10 ";
		$x = " SELECT * FROM advert WHERE id='$run_id' ";
	
		$xr = mysqli_query($conn, $x);
		$xn = mysqli_num_rows($xr);
		
		$logText.=" Total Adverts: ".$xn."\r\n";
		
		if($xn > "0")
		{
			while($xrow=mysqli_fetch_array($xr))
			{
				try
				{
					$logText.= "Start Process for : ".$xrow["companyname"]."\r\n";;
			
					$advertid = $xrow["id"];
					$network_id = $xrow["network"];
					$mer_id = $xrow["networks_id"];
					$file_name = 'awin_'.$timestamp.'.zip';
					$feed_url = $xrow["feed_link"];
					
					$logText.= "URL :".$feed_url."\r\n";
			
					$logText.= "Start download zip \r\n";
					file_put_contents("$file_name", file_get_contents("$feed_url"));
					//open the zip file
					$zip = zip_open("$file_name");
					$logText.= "Zip file downloaded : ".$file_name." \r\n";
			
					if ($zip)
					{
						while ($zip_entry = zip_read($zip))
						{
							try
							{
								$zip_size = 0;
								$zip_size =  zip_entry_filesize($zip_entry);
								$new_filesize = $zip_size / 1024;
								$new_filesize = $new_filesize /1024;
								$new_filesize = number_format($new_filesize, 2, '.', '');
				
								$logText.= " File Size (MB):".$new_filesize."\r\n";
				
								if ($new_filesize > "499")
								{
									$logText.= " File size is far too big 500Mb+ ";
													
									//$ud = " UPDATE advert SET lge='2', productfeed='0', feed_size='$new_filesize' WHERE network='$network_id' AND networks_id='$mer_id' ";
									//$udr = mysqli_query($conn,$ud);										
								}
								else if ($new_filesize == "0")
								{
									$logText.= " File size is ZERO, should be run by another process ";
									
									//$ud = " UPDATE advert SET productfeed='0', feed_size='$new_filesize' WHERE network='$network_id' AND networks_id='$mer_id' ";
									//$udr = mysqli_query($conn,$ud);																
								}
								else
								{
									//$ud = " UPDATE advert SET last_update='$timestamp', feed_size='$new_filesize' WHERE network='$network_id' AND networks_id='$mer_id' ";
									//$udr = mysqli_query($conn,$ud);	
									
									if (zip_entry_open($zip, $zip_entry))
									{
										$contents = zip_entry_read($zip_entry, $zip_size);
										$datapool = $contents;
										$xml = simplexml_load_string($datapool);
										
										$logText.= " Merchant Name : ".$name."\r\n";
			
											foreach($xml->product as $prod)	
											{
												try
												{
													$cats = $prod->category_name;
													$to_remove = array("'", ",", ";", "-", "_", "(", ")", "%", ":", "@", "*");
													$cats = str_replace($to_remove, "", $cats);
													
													$brands = $prod->brand_name;
													$brands = str_replace($to_remove, "", $brands);
																		
													//if($bombed_out == "1" OR $cats == "")
													if($cats == "")
													{
														$ig = ++$ig;
														//echo 'BOMBED RECORD: '.$prod_name.'<br>';
													}
													else
													{
														
														
														//update cat
														//Get Current Cats
														$q2 = "SELECT * FROM advert WHERE id = '$run_id' ";
														$qr2 = mysqli_query($conn,$q2);
														$q2row = mysqli_fetch_assoc($qr2);
														//echo 'DUPE ROWS FOUND: '.$qrn.' ';
														
														$ad_prod = $q2row['product'] ;														
														$ad_brand = $q2row['brand'] ;														
														//match the current cat see if its already in the box
														
														//$check_prod = preg_match($cats, $ad_prod);
														$cats_chk = '/'.$cats.'/';
														
														if(preg_match($cats_chk, $ad_prod)){
															//1 so dont add
															//echo 'dont add<br>';
														}else{
															//1 so add
															//echo 'add<br>';
															$ad_new_prod = $ad_prod.', '.$cats;
															//update
															$q3 = "UPDATE advert SET product='$ad_new_prod' WHERE id='$run_id'";
															$q3a = mysqli_query($conn,$q3);
															echo 'Added Product: '.$cats.'<br>';

														}
														//check the brand too
														//$check_prod = preg_match($cats, $ad_prod);
														$brands_chk = '/'.$brands.'/';
														if(preg_match($brands_chk, $ad_brand)){
															//1 so dont add
															//echo 'dont add<br>';
														}else{
															//1 so add
															//echo 'add<br>';
															$ad_new_brand = $ad_brand.', '.$brands;
															//update
															$q3 = "UPDATE advert SET brand='$ad_new_brand' WHERE id='$run_id'";
															$q3a = mysqli_query($conn,$q3);
															echo 'Added Brand: '.$brands.'<br>';	
														}
														

													}
												}
												catch(Exception $e)
												{
													$logText.= $e->getMessage()."\r\n";	
													put_log("Error", $e->getMessage());
												}
					
											}
										//add entry to db
										
											zip_entry_close($zip_entry);
											$logText.= " Zip entry closed ". "\r\n";;
										}
									}
								}
								catch (Exception $e) 
								{
									$logText.= $e->getMessage()."\r\n";	
									put_log("Error", $e->getMessage());
								}
							}
		
				//		echo 'Old Products Removed: '.$qrn.' New Accepted products added: ' . $i . ' Updated Products: ' . $b . ' Ignored Rows: '.$ig.' Total Products For Merchant: '.$t.'<p>';
		
						zip_close($zip);
						$logText.= " Zip file closed ". "\r\n";;
						
						//delete zip file
		
						unlink($file_name);
						$logText.= " Zip file deleted ". "\r\n";;
		
					}
			
					//delete old data
					/*
			
					$q = "SELECT * FROM $tablename WHERE last_update < '$timestamp' AND mer_id='$mer_id'";
			
					$qr = mysqli_query($conn,$q);
			
					$qrn = mysqli_num_rows($qr);
			
					$dq = "DELETE FROM $tablename WHERE last_update < '$timestamp' AND mer_id='$mer_id'";
			
					$dqr = mysqli_query($conn,$dq);
			
					$t = $i + $b;*/
			
					//$mess =  '<p>Old Products Removed: '.$qrn.'<br>New Accepted products added: ' . $i . '<br>Updated Products: ' . $b . '<br>Ignored Rows: '.$ig.'<p>Total Products For Merchant: '.$t.' failed: '.$f.' Deleted: '.$drn.' New EANs: '.$ean_count. ' No EAN: '.$ean_count_no.' Dupe EAN: '.$ean_count_dupe;
			
				}
				catch (Exception $e) 
				{
					$logText.= $e->getMessage()."\r\n";	
					put_log("Error", $e->getMessage());
				}
				
				
			}
		}
		else
		{
			$logText.= " All products up to date\r\n ";	
			$mess = ' All products up to date ';
		}
		/*
		$d = "SELECT * FROM $tablename WHERE fee < '0.02'";
		$dr = mysqli_query($conn,$d);
		$drn = mysqli_num_rows($dr);
		$d = "SELECT * FROM $tablename WHERE cat < '10000000'";
		$dr = mysqli_query($conn,$d);
		$drn2 = mysqli_num_rows($dr);

		$d = "DELETE FROM $tablename WHERE fee < '0.02'";	
		$dr = mysqli_query($conn,$d);
		$d = "DELETE FROM $tablename WHERE cat < '10000000'";
		$dr = mysqli_query($conn,$d);
		$drn2 = $drn2 + $drn;
		$ind = "ALTER TABLE $tablename DROP INDEX `prod_name` ,
		ADD INDEX `prod_name` ( `prod_name` ) ";
		$ind_q = mysqli_query($conn,$ind);
		*/
	
		echo $mess;
		echo $drn2;
}
catch (Exception $e) 
{
   $logText.= $e->getMessage()."\r\n";	
   put_log("Error", $e->getMessage());
   
}
	
	
	//echo $logText;
	
			

?>