<?php
include("db_config.php");
include("ProductLink.php");
include("Lookup.php");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])) {
    $userid = mysql_real_escape_string($_REQUEST['userid']);

    $user_details_query = "SELECT user_id, gender, bodytype
							FROM userdetails
							WHERE user_id = $userid
							LIMIT 0,1";
    $user_res = mysql_query($user_details_query);
    $user_rows = mysql_num_rows($user_res);

    if ($user_rows > 0) {
        $user_data = mysql_fetch_array($user_res);
        $gender = $user_data[1];
        $body_type = $user_data[2];
        $body_type_id = Lookup::getId('body_type', $body_type);
        $body_type_condition = $gender == 'female' ? " AND cl.body_type_id = '{$body_type_id}'" : "";
        $body_type_condition = "";//5-Jan-2015 : Temporarily commented out this condition as we do not have much looks in all body types.

        //Get 4 latest looks for 4 occasions which are not unliked by current user
        $latest_looks = array();

        $page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;
        $occasions = array('Wine & Dine', 'Casuals', 'Ethnic/Festive', 'Work Wear');
        if (isset($_GET['occasion']) && $_GET['occasion'] != '') {
            $occasions = array_intersect($occasions, array(mysql_real_escape_string($_GET['occasion'])));
        }

        $record_start = intval($page * 4);
        $records_count = 4;

        $gender_id = Lookup::getId('gender', $gender);

        foreach ($occasions as $occasion) {
            $occasion_id = Lookup::getId('occasion', $occasion);
            $latest_looks_sql =
                "Select cl.id as look_id, cl.description, cl.image, cl.price, o.name as occasion, cl.name, uf.fav_id
			from looks cl
		  	join lu_occasion o on cl.occasion_id = o.id
			LEFT JOIN usersfav uf ON cl.id = uf.look_id
			where cl.gender_id = '$gender_id'
				$body_type_condition
				AND cl.occasion_id = '$occasion_id'
				AND cl.status_id = 1
				AND (uf.user_id is null OR uf.user_id = '$userid')
				AND cl.id NOT IN
					(Select look_id
					from users_unlike
					where user_id='$userid')
			ORDER BY cl.created_at DESC
			LIMIT $record_start, $records_count ";
            //echo $latest_looks_sql . "<br /><br />";

            $latest_looks_res = mysql_query($latest_looks_sql);

            while ($data = mysql_fetch_array($latest_looks_res)) {
                $latest_looks[] = $data;
            }
            unset($latest_looks_res);
        }

        $latest_looks_count = count($latest_looks);

        if ($latest_looks_count > 0) {
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

            for ($i = 0; $i < $latest_looks_count; $i++) {
                $look_id = $latest_looks[$i][0];

                //Get products info for current look
                $current_look_products_query =
                    "select p.id, p.name, upload_image, p.price, product_type, product_link, p.agency_id, p.merchant_id
						from looks l
						join looks_products lp ON l.id = lp.look_id
						join products p ON lp.product_id = p.id
						where l.id='$look_id'
						AND l.status_id = 1";

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
                            $current_look_products[$j][5])
                    );
                }

                $current_look_details =
                    array(
                        'lookdetails' =>
                            array(
                                'fav' => $latest_looks[$i][6] == null ? 'No' : 'Yes',
                                'lookid' => $latest_looks[$i][0],
                                'lookdescription' => $latest_looks[$i][1],
                                'lookimage' => $latest_looks[$i][2],
                                'lookprice' => $latest_looks[$i][3],
                                'occasion' => $latest_looks[$i][4],
                                'lookname' => $latest_looks[$i][5],
                                'productdetails' => $productarray
                            )
                    );
                $latest_looks_and_products[] = $current_look_details;
                unset($current_look_products);
            }

            $response = array('result' => 'success', 'latest_looks' => $latest_looks_and_products);
        } else {
            $response = array('result' => 'fail', 'response_message' => 'No records');
        }
    } else {
        $response = array('result' => 'fail', 'response_message' => 'userid doesnt exist in db');
    }
} else {
    $response = array('result' => 'fail', 'response_message' => 'userid empty');
}

//var_dump($response['latest_looks'][0]['lookdetails']);

mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($response);
?>
