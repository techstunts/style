<?php
include_once("ProductLink.php");

$images_origin = 'http://d36o0t9p57q98i.cloudfront.net/';

function getLooksDetails($looks, $userid)
{
    global $images_origin;

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
            "select p.id, p.name, image_name as upload_image, product_link, p.agency_id, p.merchant_id,
                            m.name merchant_name, b.name brand_name, b.id as brand_id, p.category_id
						from looks l
						join looks_products lp ON l.id = lp.look_id
						join products p ON lp.product_id = p.id
						left join merchants m ON p.merchant_id = m.id
						left join brands b ON p.brand_id = b.id
						where l.id='$look_id'";

        $current_look_products_res = mysql_query($current_look_products_query);
        $current_look_products = [];
        $product_ids = array();
        while ($data1 = mysql_fetch_array($current_look_products_res)) {
            $product_ids[] = $data1['id'];
            $current_look_products[] = $data1;
        }
        $prices = count($product_ids) ? getINRPrice(1, $product_ids) : [];

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
                'productprice' => isset($prices[$current_look_products[$j][0]]) ? $prices[$current_look_products[$j][0]] : 0,
                'productlink' => ProductLink::getDeepLink($current_look_products[$j][4],
                    $current_look_products[$j][5],
                    $current_look_products[$j][3]),
                'merchant' => $current_look_products[$j]['merchant_name'],
                'brand' => $current_look_products[$j]['brand_name'],
                'brand_id' => $current_look_products[$j]['brand_id'],
                'producttype' => '',
                'discounted_price' => '',
                'category_id' => $current_look_products[$j][9],
            );
        }

        $stylist_details = array();
        if(isset($looks[$i]['stylist_id'])){
            $stylist_details['stylish_id'] = $looks[$i]['stylist_id'];
            $stylist_details['stylist_id'] = $looks[$i]['stylist_id'];
            $stylist_details['stylish_name'] = $looks[$i]['stylist_name'];
            $stylist_details['stylist_name'] = $looks[$i]['stylist_name'];
            $stylist_details['stylish_image'] = $looks[$i]['stylist_image'];
            $stylist_details['stylist_image'] = $looks[$i]['stylist_image'];
            $stylist_details['stylist_icon'] = isset($looks[$i]['stylist_icon']) ? $looks[$i]['stylist_icon'] : $looks[$i]['stylist_image'];
        }

        $current_look_details =
            array(
                'lookdetails' =>
                    array(
                        'fav' => $looks[$i][6] == null ? 'No' : 'Yes',
                        'lookid' => $looks[$i][0],
                        'lookdescription' => mb_convert_encoding($looks[$i][1], "UTF-8", "Windows-1252"),
                        'lookimage' => $looks[$i][2],
                        'lookprice' => $looks[$i]['price'] ? $looks[$i]['price'] : $looks[$i][3],
                        'occasion' => $looks[$i][4],
                        'lookname' => mb_convert_encoding($looks[$i][5], "UTF-8", "Windows-1252"),
                        'productdetails' => $productarray,
                        'stylish_details' => $stylist_details,
                        'is_collage' => isset($looks[$i]['is_collage']) ? $looks[$i]['is_collage'] : '',
                        'look_url' => $images_origin . '/uploads/images/looks/' . $looks[$i][2]
                    )
            );
        $looks_and_products[] = $current_look_details;
        unset($current_look_products);
    }

    return $looks_and_products;
}

function getINRPrice($entity_type_id, $entity_ids)
{
    $ids = implode(',', $entity_ids);
    $price = array();
    if (1 == $entity_type_id) {
        $priceRawQuery = "select pp.product_id AS entity_id, pp.value
					    from products_prices pp
						where pp.product_id IN ($ids) AND pp.price_type_id='2' AND pp.currency_id='1'";
    } elseif (2 == $entity_type_id) {
        $priceRawQuery = "select lp.look_id AS entity_id, lp.value
						from look_prices lp
						where lp.look_id IN ($ids) AND lp.price_type_id='2' AND lp.currency_id='1'";
    }
    $priceMySQLQuery = mysql_query($priceRawQuery);
    while ($data = mysql_fetch_array($priceMySQLQuery)) {
        $price[$data['entity_id']] = $data['value'];
    }
    return $price ? $price : $price;
}