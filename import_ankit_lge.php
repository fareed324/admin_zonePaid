<?php

include_once('connect.php');

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
		$x = " SELECT * FROM advert WHERE productfeed='1' AND status='1' AND last_update < '$check_age' AND lge > '0' ORDER BY last_update DESC LIMIT 1 ";
		//$x = " SELECT * FROM advert WHERE productfeed='1' AND status='1' AND last_update < '$check_age' AND lge <'1' ORDER BY last_update DESC LIMIT 10 ";
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
				
								if ($new_filesize > "14")
								{
									$logText.= " File size is far too big 15Mb+ ";
													
									$ud = " UPDATE advert SET lge='2', productfeed='0', feed_size='$new_filesize' WHERE network='$network_id' AND networks_id='$mer_id' ";
									$udr = mysqli_query($conn,$ud);										
								}
								else if ($new_filesize == "0")
								{
									$logText.= " File size is ZERO, should be run by another process ";
									
									$ud = " UPDATE advert SET productfeed='0', feed_size='$new_filesize' WHERE network='$network_id' AND networks_id='$mer_id' ";
									$udr = mysqli_query($conn,$ud);																
								}
								else
								{
									$ud = " UPDATE advert SET last_update='$timestamp', feed_size='$new_filesize' WHERE network='$network_id' AND networks_id='$mer_id' ";
									$udr = mysqli_query($conn,$ud);	
									
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
													$aw_id = $prod->aw_product_id;
													//echo $aw_id.'<br>';
													$prod_name = addslashes($prod->product_name);
													$cats = addslashes ($prod->category_name);
													$mer_id = $prod->merchant_id;
													$name = $prod->merchant_name;
													//echo $name;
													$image = $prod->merchant_image_url;
													$fee = $prod->search_price ;
													$delivery = $prod->delivery_cost;
													$stock = $prod->in_stock == "no"?0:1;
													$rrp = $prod->rrp_price;
													$valid_to = $prod->valid_to;
													$details =  addslashes ($prod->description);
													$deeplink = $prod->aw_deep_link;
													$deeplink = $deeplink.'&clickref={CLICKID}';
													$ean_no = $prod->ean;
													$ean_no = trim($ean_no);
													/*
													echo 'Product ID: '.$aw_id.'<br>';
													echo 'Product Name: '.$prod_name.'<br>';
													echo 'Cat Name: '.$cats.'<br>';
													echo 'Mer ID: '.$mer_id.'<br>';
													echo 'Merhcant Name: '.$name.'<br>';
													echo 'Product IMG URL: '.$image.'<br>';
													echo 'Item Fee: &pound;'.$fee.'<br>';
													echo 'Delivery Cost: &pound;'.$delivery.'<br>';
													echo 'In Stock: '.$stock.'<br>';
													echo 'RRP Price: &pound;'.$rrp.'<br>';
													echo 'Valid To: '.$valid_to.'<br>';
													echo 'Product Details: '.$details.'<br>';
													echo 'Deeplink URL: '.$deeplink.'<br>';
													echo 'EAN: '.$ean.'<br><br>';
													*/
													
													$logText.= $aw_id." - ".$prod_name." - ".$cats." \r\n";
						
													if($ean_no <"1")
													{
														$ean_count_no = ++$ean_count_no;
													}
					
													$isbn_no = "";	
													$stock_qty = 100;
					
													//now continue
						
													//if($bombed_out == "1" OR $cats == "")
													if($cats == "")
													{
														$ig = ++$ig;
														//echo 'BOMBED RECORD: '.$prod_name.'<br>';
													}
													else
													{
						
														$q = "SELECT 'id' FROM $tablename WHERE aw_id = '$aw_id' ";
														$qr = mysqli_query($conn,$q);
														$qrn = mysqli_num_rows($qr);
														//echo 'DUPE ROWS FOUND: '.$qrn.' ';
													
															if($qrn < "1")
															{
																//new products
																$i = ++$i;
						
																$insq = " INSERT INTO $tablename ";
																$insq.= " (name, aw_img, aw_deeplink, prod_name, cat, fee, aw_id, mer_id, advertid, delivery, in_stock, valid_to, rrp, details, last_update, ean_no, isbn_no, stock_qty) ";
																$insq.= " VALUES ";
																$insq.= " ('$name', '$image', '$deeplink', '$prod_name', '$cats', '$fee', '$aw_id', '$mer_id', '$advertid', '$delivery', '$stock', '$valid_to', '$rrp', '$details', '$timestamp', '$ean_no', '$isbn_no', '$stock_qty') ";
																$insr = mysqli_query($conn,$insq);
																//echo 'INSERT NEW RECORD: '.$prod_name.'<br>';
						
															}
															else
															{
																//its a dupe
																$ud = " UPDATE $tablename ";
																$ud.= " SET ean_no='$ean_no', name='$name', aw_img='$image', aw_deeplink='$deeplink', prod_name='$prod_name', ";
																$ud.= " cat='$cats', fee='$fee', aw_id='$aw_id', advertid='$advertid', mer_id='$mer_id', delivery='$delivery', ";
																$ud.= " in_stock='$stock', valid_to='$valid_to', rrp='$rrp', details='$details', last_update='$timestamp', ";
																$ud.= " stock_qty='$stock_qty' ";
																$ud.= " WHERE aw_id = '$aw_id' ";
																
																$udr = mysqli_query($conn,$ud);
																$b = ++$b;
																//echo 'UPDATED RECORD: '.$prod_name.'<br>';
															}
						
														$ean_no = "";					
													}
												}
												catch(Exception $e)
												{
													$logText.= $e->getMessage()."\r\n";	
													put_log("Error", $e->getMessage());
												}
					
											}
										
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
			
					$q = "SELECT * FROM $tablename WHERE last_update < '$timestamp' AND mer_id='$mer_id'";
			
					$qr = mysqli_query($conn,$q);
			
					$qrn = mysqli_num_rows($qr);
			
					$dq = "DELETE FROM $tablename WHERE last_update < '$timestamp' AND mer_id='$mer_id'";
			
					$dqr = mysqli_query($conn,$dq);
			
					$t = $i + $b;
			
					$mess =  '<p>Old Products Removed: '.$qrn.'<br>New Accepted products added: ' . $i . '<br>Updated Products: ' . $b . '<br>Ignored Rows: '.$ig.'<p>Total Products For Merchant: '.$t.' failed: '.$f.' Deleted: '.$drn.' New EANs: '.$ean_count. ' No EAN: '.$ean_count_no.' Dupe EAN: '.$ean_count_dupe;
			
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
	
		echo $mess;
		echo $drn2;
}
catch (Exception $e) 
{
   $logText.= $e->getMessage()."\r\n";	
   put_log("Error", $e->getMessage());
   
}
	
	
	//echo $logText;
	$q = "SELECT * FROM advert WHERE id = '$advertid'";
	$qa = mysqli_query($conn,$q);
	$qarow = mysqli_fetch_array($qa);
	$response = $qarow['companyname'];

	echo '<br>ADVERT ID: '.$advertid.' ('.$response.')';
	
			

?>