<?php
include("db_config.php");
include("ProductLink.php");
include("Lookup.php");

if($_SERVER['REQUEST_METHOD']=="GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
	$userid = mysql_real_escape_string($_REQUEST['userid']);

	$user_details_query = "SELECT user_id, gender, bodytype
							FROM userdetails
							WHERE user_id = $userid
							LIMIT 0,1";
	$user_res=mysql_query($user_details_query);
	$user_rows=mysql_num_rows($user_res);

	if($user_rows > 0){
		$user_data = mysql_fetch_array($user_res);
		$gender = $user_data[1];
		$gender_id = Lookup::getId('gender', $gender);

		$looks = array();

		$page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;
		$records_count = 10;
		$record_start = intval($page * $records_count);

		$looks_sql =
			"SELECT cl.id as look_id, cl.description as look_description, cl.image as look_image,
					cl.price as lookprice, o.name as occasion, cl.name as look_name, uf.fav_id,
					sd.stylish_id as stylist_id, sd.name as stylist_name, sd.image as stylist_image
			FROM collection_entities ce
				JOIN looks cl ON ce.entity_id = cl.id AND ce.entity_type_id = 2
				JOIN lu_occasion o on cl.occasion_id = o.id
				LEFT JOIN (SELECT * FROM usersfav WHERE user_id = '$userid') uf ON cl.id = uf.look_id
				JOIN stylists sd on sd.stylish_id = cl.stylish_id
			WHERE ce.collection_id = 1
				AND cl.gender_id = '$gender_id'
				AND cl.id NOT IN
					(SELECT look_id
					FROM users_unlike
					WHERE user_id='$userid')
			ORDER BY cl.created_at DESC
			LIMIT $record_start, $records_count ";
			//echo $looks_sql . "<br /><br />";

		$looks_res = mysql_query($looks_sql);

		while ($data = mysql_fetch_array($looks_res)) {
			$looks[] = $data;
		}
		unset($looks_res);
		//var_dump($looks);
		$looks_count = count($looks);

		if($looks_count > 0) {
			// Get all favourite products of current user
			$fav_prod_sql =
				"Select product_id
					from usersfav join products
						on usersfav.product_id = products.id
					where user_id='$userid'";
			$fav_prod_res = mysql_query($fav_prod_sql);
			$fav_prod_count = mysql_num_rows($fav_prod_res);

			while ($data = mysql_fetch_array($fav_prod_res)) {
				$fav_prods[] = $data['product_id'];
			}

			for ($i = 0; $i < $looks_count; $i++) {
				$look_id = $looks[$i][0];

				//Get products info for current look
				$current_look_products_query =
					"select p.id, p.name, upload_image, p.price, product_type, product_link, p.agency_id, p.merchant_id,
                            m.name merchant_name, b.name brand_name, b.id as brand_id
						from looks l
						join looks_products lp ON l.id = lp.look_id
						join products p ON lp.product_id = p.id
						join merchants m ON p.merchant_id = m.id
						join brands b ON p.brand_id = b.id
						where l.id='$look_id'";

				$current_look_products_res = mysql_query($current_look_products_query);
				$current_look_products = [];
				while ($data1 = mysql_fetch_array($current_look_products_res)) {
					$current_look_products[] = $data1;
				}

				$productarray = array();
				for ($j = 0; $j < count($current_look_products); $j++) {
					if ($fav_prod_count == 0) {
						$fav = 'No';
					}
					for ($k = 0; $k < $fav_prod_count; $k++) {
						if ($current_look_products[$j][0] == $fav_prods[$k]) {
							$fav = 'yes';
							break;
						} else {
							$fav = 'No';
						}
					}

					$productarray[] = array(
						'fav' => $fav,
						'productid' => $current_look_products[$j][0],
						'productname' => $current_look_products[$j][1],
						'productimage' => $current_look_products[$j][2],
						'productprice' => $current_look_products[$j][3],
						'producttype' => $current_look_products[$j][4],
						'productlink' => ProductLink::getDeepLink($current_look_products[$j][6],
														$current_look_products[$j][7],
														$current_look_products[$j][5]),
						'merchant' => $current_look_products[$j]['merchant_name'],
						'brand' => $current_look_products[$j]['brand_name'],
                        'brand_id' => $list[$j]['brand_id'],
					);
				}

				$stylist_details = array();
				$stylist_details['stylish_id'] = $looks[$i]['stylist_id'];
				$stylist_details['stylish_name'] = $looks[$i]['stylist_name'];
				$stylist_details['stylish_image'] = $looks[$i]['stylist_image'];

				$current_look_details =
					array(
						'lookdetails' =>
							array(
								'fav' => $looks[$i][6] == null ? 'No' : 'Yes',
								'lookid' => $looks[$i]['look_id'],
								'lookdescription' => $looks[$i]['look_description'],
								'lookimage' => $looks[$i]['look_image'],
								'lookprice' => $looks[$i]['lookprice'],
								'occasion' => $looks[$i]['occasion'],
								'lookname' => $looks[$i]['look_name'],
								'productdetails' => $productarray,
								'stylish_details' => $stylist_details
							)
					);
				$looks_and_products[] = $current_look_details;
				unset($current_look_products);
			}

			$response = array('result' => 'success', 'looks' => $looks_and_products);
		}
		else{
			$response=array('result'=>'fail','response_message'=>'No records');
		}
	}
	else{
		$response=array( 'result'=>'fail', 'response_message'=>'userid doesnt exist in db' );
	}
}
else{
	$response=array('result'=>'fail','response_message'=>'userid empty');
}

//var_dump($response['looks'][0]['lookdetails']);

mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($response);
?>
