<?php
include("config.php");
include("model.php");

if(!($argc == 2 && isset($merchants[$argv[1]]))){
    $error = 'Invalid merchant';
    terminate($error);
}

db_connect($db_conn_params);

$merchant = $argv[1];
$feed_path = $merchants[$merchant]['feed_path'];

$insert_query_field_names = ['agency_id', 'merchant_id'];
$insert_query_values = [$merchants[$merchant]['agency_id'], $merchants[$merchant]['merchant_id']];

if(!file_exists($feed_path)){
    $error = 'File ' . $feed_path . ' not found';
    terminate($error);
}

require 'Merchant.php';
$merchant_class = strtoupper(substr($merchant,0,1)) . strtolower(substr($merchant,1));
if(!class_exists($merchant_class)){
    $error = 'Class ' . $merchant_class . ' not found';
    terminate($error);
}

$merchant_object = new $merchant_class;

require 'Lookup.php';

require 'ProductIterator.php';
$reader  = new XMLReader();
$reader->open($feed_path);

foreach (new ProductIterator($reader) as $product) {
    if(!$merchant_object->isStockAvailable($product)){
        continue;
    }

    debug_message('Found name: '. $product->ProductName);
    debug_message('Category : '. $product->CategoryName);

    $fields = $insert_query_field_names;
    $values = $insert_query_values;

    foreach($product as $key => $value){
        if($field_mapping[$key]){
            $fields[] = $field_mapping[$key];
            $values[] = addslashes($product->$key);
        }
    }

    $fields[] = 'gender_id';
    $values[] = $merchant_object->getGender($product);

    $fields[] = 'category_id';
    $category_name = $product->CategoryName->__toString();
    $values[] = Category::getId($category_name) != null ?
        Category::getId($category_name) :
        Category::addResource($category_name);

    $fields[] = 'brand_id';
    $brand_name = $product->Brand->__toString();
    $values[] = Brand::getId($brand_name) != null ?
        Brand::getId($brand_name) :
        Brand::addResource($brand_name);

    $insert_query = 'INSERT INTO merchant_products (' . implode(',', $fields) . ')' .
                    'VALUES ("' . implode('","', $values) . '")';
    debug_message($insert_query, __DEBUG__);

    $retval = execute_query($insert_query);

    if($retval) {
        debug_message("Product " . $product->ProductName . " inserted");
    }
    debug_message('==========================================');
    //exit;
}
