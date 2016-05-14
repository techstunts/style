<?php
include_once("ProductLink.php");

function getLooksDetails($looks, $userid){
    $looks_count = count($looks);
    $looks_and_products = array();

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
						left join merchants m ON p.merchant_id = m.id
						left join brands b ON p.brand_id = b.id
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
                'productname' => mb_convert_encoding($current_look_products[$j][1], "UTF-8", "Windows-1252"),
                'productimage' => $current_look_products[$j][2],
                'productprice' => $current_look_products[$j][3],
                'producttype' => $current_look_products[$j][4],
                'productlink' => ProductLink::getDeepLink($current_look_products[$j][6],
                    $current_look_products[$j][7],
                    $current_look_products[$j][5]),
                'merchant' => $current_look_products[$j]['merchant_name'],
                'brand' => $current_look_products[$j]['brand_name'],
                'brand_id' => $current_look_products[$j]['brand_id'],
            );

        }

        $stylist_details = array();
        if(isset($looks[$i]['stylist_id'])){
            $stylist_details['stylish_id'] = $looks[$i]['stylist_id'];
            $stylist_details['stylist_id'] = $looks[$i]['stylist_id'];
            $stylist_details['stylish_name'] = $looks[$i]['stylist_name'];
            $stylist_details['stylist_name'] = $looks[$i]['stylist_name'];
            $stylist_details['stylish_image'] = 'http://istyleyou.in/backend/'.$looks[$i]['stylist_image'];
            $stylist_details['stylist_image'] = 'http://istyleyou.in/backend/'.$looks[$i]['stylist_image'];
        }

        $current_look_details =
            array(
                'lookdetails' =>
                    array(
                        'fav' => $looks[$i][6] == null ? 'No' : 'Yes',
                        'lookid' => $looks[$i][0],
                        'lookdescription' => mb_convert_encoding($looks[$i][1], "UTF-8", "Windows-1252"),
                        'lookimage' => $looks[$i][2],
                        'lookprice' => $looks[$i][3],
                        'occasion' => $looks[$i][4],
                        'lookname' => mb_convert_encoding($looks[$i][5], "UTF-8", "Windows-1252"),
                        'productdetails' => $productarray,
                        'stylish_details' => $stylist_details
                    )
            );
        $looks_and_products[] = $current_look_details;
        unset($current_look_products);
    }

    return $looks_and_products;
}