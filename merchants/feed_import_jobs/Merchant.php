<?php
abstract class Merchant{
    protected $sku;
    protected $config;
    protected $simple;

    const __Female__ = 1;
    const __Male__ = 2;

    function __get($name){
        if(property_exists($this, $name))
            return $this->$name;
    }

    function setSku($sku){
        $this->sku = $sku;
        $this->calculateConfig();
        $this->calculateSimple();
    }

    function calculateConfig(){
        $this->config = $this->sku;
    }

    function calculateSimple(){
        $this->simple = $this->sku;
    }

    function isStockAvailable($product){
        return true;
    }

    abstract function getGender($product);

    abstract function getProductImageUrl($product);
}

class Jabong extends Merchant{

    function calculateConfig(){
        $this->config = explode('-', $this->sku)[0];
    }

    function calculateSimple(){
        $this->simple = explode('-', $this->sku)[1];
    }

    function getGender($product){
        if (property_exists($product, 'custom3')) {
            if (strpos($product->custom3, "Men") !== false)
                return Merchant::__Male__;
            else if (strpos($product->custom3, "Women") !== false)
                return Merchant::__Female__;
        }
        return "";
    }

    function getProductImageUrl($product){
        return $product->ProductImageLargeURL;
    }
}

class Flipkart extends Merchant{

    function isStockAvailable($product)
    {
        if (property_exists($product, 'StockAvailability')) {
            if ($product->StockAvailability == "Out of stock"){
                return false;
            }
        }
        return true;
    }

    function getGender($product){
        if (property_exists($product, 'custom1')) {
            if (strpos($product->custom1, "Male") !== false)
                return Merchant::__Male__;
            else if (strpos($product->custom1, "Female") !== false)
                return Merchant::__Female__;
        }
        return "";
    }

    function getProductImageUrl($product){
        $urls = explode(";", $product->ProductImageLargeURL);
        if(count($urls) > 0) {
            foreach ($urls as $url) {
                if (strpos($url, "original") !== false) {
                    break;
                }
            }
            return $url;
        }
        return $product->ProductImageLargeURL;
    }
}

class Koovs extends Merchant{

    function isStockAvailable($product)
    {
        if (property_exists($product, 'StockAvailability')) {
            if ($product->StockAvailability == "Out of stock"){
                return false;
            }
        }
        return true;
    }

    function getGender($product){
        if (property_exists($product, 'custom1')) {
            if (strpos($product->custom1, "Male") !== false)
                return Merchant::__Male__;
            else if (strpos($product->custom1, "Female") !== false)
                return Merchant::__Female__;
        }
        return "";
    }

    function getProductImageUrl($product){
        return $product->ProductImageLargeURL;
    }
}

class Limeroad extends Merchant{

    function isStockAvailable($product)
    {
        return true;
    }

    function getGender($product){
        return "";
    }

    function getProductImageUrl($product){
        return $product->ProductImageMediumURL;
    }
}
