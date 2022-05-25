<?php
include_once('connect.php');
include_once('functions.php');

//counts
$new_brands = "0";
$dupe_brands = "0";
$new_cats = "0";
$dupe_cats = "0";

$datafeed_id = $_REQUEST['id'];

$feed_url = 'https://productdata.awin.com/datafeed/download/apikey/50d497da694e003d95ef15a9f57eca7d/language/en/cid/97,98,142,144,146,129,595,539,147,149,613,626,135,163,168,159,169,161,167,170,137,171,548,174,183,178,179,175,172,623,139,614,189,194,141,205,198,206,203,208,199,204,201,61,62,72,73,71,74,75,76,77,78,63,80,82,64,83,84,85,65,86,87,88,90,89,91,67,94,33,54,53,57,55,52,603,60,59,66,128,130,133,212,207,209,210,211,68,69,213,215,217,220,221,70,224,225,226,227,228,229,4,5,10,11,537,13,19,15,14,6,551,20,21,553,22,23,24,25,26,7,30,29,32,619,34,8,35,618,40,42,43,9,652,651,49,50,51,634,230,231,538,233,235,550,240,585,237,239,241,556,245,242,521,576,575,579,281,283,285,304,286,282,290,287,288,627,173,193,637,639,640,642,643,644,641,650,177,196,379,648,181,645,384,387,646,598,611,391,393,647,395,631,602,570,600,405,187,411,412,413,414,415,416,417,649,418,419,420,99,100,101,107,110,111,113,114,115,116,118,121,122,127,581,624,123,594,125,421,605,604,599,422,433,530,434,436,532,428,474,475,476,477,423,608,437,438,440,441,442,444,445,446,447,607,424,451,448,453,449,452,450,425,455,457,459,460,456,458,426,616,463,464,465,466,467,427,625,597,473,469,617,470,429,479,430,615,483,484,485,488,529,596,431,432,489,606,490,361,633,362,366,367,368,371,369,363,372,373,374,377,375,536,535,364,378,380,381,365,383,385,386,390,392,394,396,397,399,402,404,406,407,540,542,544,546,547,246,558,247,252,559,255,248,256,265,258,259,260,261,262,557,249,266,267,268,269,612,251,277,250,272,271,561,560,347,348,354,350,351,352,349,355,356,357,358,359,360,586,590,592,588,591,589,328,629,338,493,635,495,507,563,564,566,567,569,568/fid/'.$datafeed_id.'/columns/aw_deep_link,product_name,aw_product_id,merchant_product_id,merchant_image_url,description,merchant_category,search_price,category_name,brand_name/format/xml-tree/compression/zip/adultcontent/1/';

// This feed should grab the most cats & brands
$file_name = 'awin_'.$timestamp.'.zip';
file_put_contents("$file_name", file_get_contents("$feed_url"));
//open the zip file
$zip = zip_open("$file_name");

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
				
								echo " File Size (MB):".$new_filesize."\r\n";
				
								if ($new_filesize > "400")
								{
									echo " File size greater then 400MB, should be run by another process ";												
								}
								else if ($new_filesize == "0")
								{
									echo " File size is ZERO, should be run by another process ";									
								}
								else
								{									
									if (zip_entry_open($zip, $zip_entry))
									{
										$contents = zip_entry_read($zip_entry, $zip_size);
										$datapool = $contents;
										$xml = simplexml_load_string($datapool);
			
											foreach($xml->product as $prod)	
											{
												try
												{
													/*$aw_id = $prod->aw_product_id;
													//echo $aw_id.'<br>';
													$prod_name = addslashes($prod->product_name);
													$mer_id = $prod->merchant_id;
													$name = $prod->merchant_name;
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
													*/
													
													$cats = addslashes ($prod->category_name);
													$brands = addslashes ($prod->brand_name);
													
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

													if($cats == "")
													{
														$ig = ++$ig;
														//echo 'BOMBED RECORD: '.$prod_name.'<br>';
													}
													else
													{
						
														// Select to see if the CAT exists
														$q = "SELECT 'id' FROM feed_products WHERE name = '$cats' ";
														$qr = mysqli_query($conn,$q);
														$qrn = mysqli_num_rows($qr);
														//echo 'DUPE ROWS FOUND: '.$qrn.' ';
													
															if($qrn < "1")
															{
																//new cat
																$new_cats = $new_cats + 1;
						
																$insq = " INSERT INTO feed_products ";
																$insq.= " (name) ";
																$insq.= " VALUES ";
																$insq.= " ('$cats') ";
																$insr = mysqli_query($conn,$insq);
																//echo 'INSERT NEW RECORD: '.$prod_name.'<br>';
																
																//get cat id
																$q = "SELECT * FROM feed_products WHERE name = '$cats'";
																$qr = mysqli_query($conn,$q);
																$qrow = mysqli_fetch_array($qr);
																$catrow = $qrow['id'];
																
																//add brand to new cat					
																$insq = " INSERT INTO feed_brands ";
																$insq.= " (name, prodid) ";
																$insq.= " VALUES ";
																$insq.= " ('$brands', '$catrow') ";
																$insr = mysqli_query($conn,$insq);
																
						
															}else{
																$dupe_cats = $dupe_cats + 1;
																//get cat id
																$q = "SELECT * FROM feed_products WHERE name = '$cats'";
																$qr = mysqli_query($conn,$q);
																$qrow = mysqli_fetch_array($qr);
																$catrow = $qrow['id'];													
																
																// Select to see if the brand exists for the CAT 
																$q = "SELECT 'id' FROM feed_brands WHERE name = '$brands' AND product_id='$catrow'";
																$qr = mysqli_query($conn,$q);
																$qrn = mysqli_num_rows($qr);
																//echo 'DUPE ROWS FOUND: '.$qrn.' ';
																
																if($qrn < "1"){ //add new entry
																	$new_brands = $new_brands + 1;
																	$insq = " INSERT INTO feed_brands ";
																	$insq.= " (name, product_id) ";
																	$insq.= " VALUES ";
																	$insq.= " ('$brands', '$catrow') ";
																	$insr = mysqli_query($conn,$insq);
																	
																}else{
																	$dupe_brands = $dupe_brands + 1;
																}

															}										
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

echo '<p>SCRIPT RUN COMPLETE<p>
Stats:<br>
New Cats: '.$new_cats.'<br>
Dupe Cats: '.$dupe_cats.'<br>
New Brands: '.$new_brands.'<br>
Dupe Brands: '.$dupe_brands.'<br>


';
?>
