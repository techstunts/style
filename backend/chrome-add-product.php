<?php
include 'databaseconnect.php';

		if(isset($_GET['name'])) {
			$name	=	$_GET['name'];
			$price		=	str_replace(array(","," "), "", $_GET['price']);
			$image	=	$_GET['image0'];
			$url		=	$_GET['url'];
			
			$insertQuery  	=	"INSERT INTO `products`
			(`product_name`, `product_type`, `product_price`, `product_link`, `upload_image`, `image_name`) 
			VALUES ( '$name', 'Dress', '$price', '$url', '$image', '$image')";
			
			$executeQuery	=	mysql_query($insertQuery);

			$product_url = "http://" . $_SERVER['HTTP_HOST'] . "/backend/product_view.php?id=" . mysql_insert_id();
			if($executeQuery){
				echo  json_encode([true, $product_url]);
			}
		}
?> 
