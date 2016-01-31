<?php
class ProductLink{
    static $agency_programme_ids = [];
    static $affiliate_id = 872525;
    static $omg_url_pattern = 'http://clk.omgt5.com/?AID={aid}&PID={pid}&Type=12&r={url}';

    public static function init(){

        $sql = 'SELECT merchant_id, agency_programme_id FROM agency_merchant_programmes WHERE status_id = 1';
        $programme_ids = mysql_query($sql);

        while ($data = mysql_fetch_array($programme_ids)) {
            self::$agency_programme_ids[$data[0]] = $data[1];
        }
    }

    public static function getDeepLink($agency_id = 0, $merchant_id = 0, $product_link){
        if(self::$agency_programme_ids == []){
            self::init();
        }

        if($agency_id != 0 || $merchant_id == 0 || !isset(self::$agency_programme_ids[$merchant_id])){
            return $product_link;
        }

        $deep_link = str_replace("{aid}", self::$affiliate_id, self::$omg_url_pattern);
        $deep_link = str_replace("{pid}", self::$agency_programme_ids[$merchant_id], $deep_link);
        $deep_link = str_replace("{url}", $product_link, $deep_link);

        return $deep_link;
    }
}