<?php
$merchants = [
    'jabong' => ['agency_id' => 1, 'merchant_id' => 1, 'feed_path' => '../feeds/jabong/283-02122015-121912.xml'],
    'koovs' => ['agency_id' => 1, 'merchant_id' => 2, 'feed_path' => '../feeds/koovs/335-05122015-031811.xml'],
    'limeroad' => ['agency_id' => 1, 'merchant_id' => 3, 'feed_path' => '../feeds/limeroad/316-05122015-031827.xml'],
    'flipkart' => ['agency_id' => 1, 'merchant_id' => 4, 'feed_path' => '../feeds/flipkart/382-05122015-031841.xml'],
    'nykaa' => ['agency_id' => 1, 'merchant_id' => 5, 'feed_path' => '../feeds/nykaa/363-07122015-103305.xml'],
    'trendin' => ['agency_id' => 1, 'merchant_id' => 6, 'feed_path' => '../feeds/trendin/321-08122015-053450.xml'],
    'yepme' => ['agency_id' => 1, 'merchant_id' => 7, 'feed_path' => '../feeds/yepme/323-07122015-103311.xml'],
    'zivame' => ['agency_id' => 1, 'merchant_id' => 8, 'feed_path' => '../feeds/zivame/361-08122015-053436.xml'],


];

global $db_conn;

$db_conn_params = [
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'istylrwd_istyleyou',
    'username'  => 'root',
    'password'  => 'mysqlpass',
];

$field_mapping = [
    'ProductID'=>'m_product_id',
    'ProductSKU'=>'m_product_sku',
    'ProductName'=>'m_product_name',
    'ProductDescription'=>'m_product_description',
    'ProductPrice'=>'m_product_price',
    'ProductPriceCurrency'=>'m_product_price_currency',
    'WasPrice'=>'m_was_price',
    'DiscountedPrice'=>'m_discounted_price',
    'ProductURL'=>'m_product_url',
    'PID'=>'m_agency_programme_id',
    'MID'=>'m_agency_merchant_id',
    'ProductImageSmallURL'=>'m_product_image_small_url',
    'ProductImageMediumURL'=>'m_product_image_medium_url',
    'ProductImageLargeURL'=>'m_product_image_large_url',
    'StockAvailability'=>'m_stock_availability', //koovs
    'Brand'=>'m_brand',
    'Colour'=>'m_color',
    'custom1'=>'m_custom_field_1',
    'custom2'=>'m_custom_field_2',
    'custom3'=>'m_custom_field_3',
    'custom4'=>'m_custom_field_4',
    'custom5'=>'m_custom_field_5',
    'CategoryName'=>'m_category_name',
    'CategoryPathAsString'=>'m_category_path',
];

define("__DEBUG__", 1);
define("__PROGRESS__", 2);

global $debug_on;
$debug_on = false;
