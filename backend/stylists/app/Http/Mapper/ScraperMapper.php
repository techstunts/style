<?php
namespace App\Http\Mapper;

use App\Models\Scraper\Jobs;
use App\Models\Scraper\Spiders;
use App\Models\Scraper\Products;
use App\Models\Scraper\Imports;
use App\Models\Scraper\ErroneousProducts;
use App\Models\Enums\Gender;
use App\Models\Enums\ProductStatus;
use App\Models\Enums\ProductError;
use Illuminate\Support\Facades\DB;

class ScraperMapper
{
    protected $api_key = 'ae67de7e514046eb9995ecd497181dd1:';
    protected $with_array = ['merchant', 'project'];
    protected $process_data_count = 10;
    protected $start = 0;
    protected $none = 0;
    protected $diff_cur_merchants_id = [25 => 'Indianroots', 38 => 'Violetstreet'];

    public function getContent($url, $file_name = '')
    {
        $headers = array(
            "Accept: application/x-jsonlines",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_key);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        if (!empty($file_name)) {
            $file_name_with_path = env('JSONLINE_FILE_BASE_PATH') . $file_name;
            $fp = fopen($file_name_with_path, "w");
            if ($fp == false) {
                return array('status' => false);
            }
            curl_setopt($ch, CURLOPT_FILE, $fp);
            $result = curl_exec($ch);

            if (!fwrite($fp, $result)) {
                return array('status' => false);
            }
            curl_close($ch);
            fclose($fp);

            return array('status' => true, 'file_path' => env('JSONLINE_FILE_BASE_PATH'));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function getSpiders()
    {
        return Spiders::with($this->with_array)->where('status_id', true)->get();
    }

    public function noJobExists($job_id)
    {
        $job = Jobs::where('id', $job_id)->first();
        if (empty($job)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateJob($data)
    {
        if (empty($data)) {
            return false;
        }

        if (!Jobs::insert($data)) {
            return false;
        }
        return true;
    }

    public function getIncompleteJobs()
    {
        return Jobs::with('spider')
            ->where('import_completed', false)
            ->get();
    }

    public function getImportByJobId($job_id)
    {
        return Imports::where('job_id', $job_id)->first();
    }

    public function createImport($job_id)
    {
        $importObj = new Imports();

        $importObj->job_id = $job_id;
        $importObj->count = $this->start;
        $importObj->created_at = date("Y-m-d H:i:s");
        try {
            $importObj->save();
            return $importObj;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function fetchAndSaveProducts($job, $import, $fileObj)
    {
        $file_name = $job->items_file_path . $job->items_file_name;
        $merchant_id = $job->spider->merchant_id;
        $record_start = $import->count;

        if (!$file = fopen($file_name, 'r')) {
            fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Error opening file '. $file_name.PHP_EOL);
            return false;
        }

        $imported_item_count = $import->count;
        $count = $this->start;
        $line_index = $this->start;
        $product_array = array();

        while (!feof($file)) {

            while ($line_index < $record_start - 1) {
                $line_index++;
                if (feof($file) || !$json_line = fgets($file)) {
                    break;
                } else {
                    continue;
                }
            }
            if (!$json_line = fgets($file)) {
                break;
            }
            $line = json_decode($json_line);
            if ($count == $this->process_data_count) {
                if ($saved_products = $this->saveProductInLocal($product_array, $merchant_id)) {

                    $response = json_decode($this->importMerchantProducts($saved_products));
                    if (!empty($response) && $response->success == false) {
                        $this->updateProductsHavingError($response);
                    }

                    $imported_item_count += $count;
                    $last_item_key = !empty($product_array[$count - 1]->_key) ? $product_array[$count - 1]->_key : $product_array[$count - 2]->_key;
                    $this->updateImportStatus($import, $imported_item_count, $last_item_key);
                }
                $count = $this->start;
                unset($product_array);

            } elseif ($count < $this->process_data_count) {
                $product_array[$count++] = $line;
            }
        }

        if (($count > $this->start + 1) && ($count < $this->process_data_count) && (count($product_array) > 1)) {
            if ($saved_products = $this->saveProductInLocal($product_array, $merchant_id)) {
                $response = json_decode($this->importMerchantProducts($saved_products));
                if (!empty($response) && $response->success == false) {
                    $this->updateProductsHavingError($response);
                }

                $imported_item_count += $count - 1;
                $last_item_key = $product_array[$count - 2]->_key;
                $this->updateImportStatus($import, $imported_item_count, $last_item_key);

            }
        }

        if(!fclose($file)){
            return false;
        }
        return true;
    }

    public function saveProductInLocal($product_array, $merchant_id)
    {
        $count = $this->start;
        $products_to_be_saved = array();
        $index = $this->start;
        $query = '';
        $products = array();
        $updated_status = false;

        foreach ($product_array as $item) {
            if (!is_integer($item))
                $query = $query . " OR (sku = '{$item->sku}' AND merchant_id = '{$merchant_id}')";
        }
        if (!empty($query)) {
            $query = substr($query, 4);
            $existing_products = Products::whereRaw($query)->select('merchant_id', 'sku', 'mrp', 'discounted_price')->get();
            $erroneous_products = ErroneousProducts::whereRaw($query)->get();
        } else {
            $existing_products = array();
            $erroneous_products = array();
        }

        $existing_product_sku = array();
        foreach ($existing_products as $item) {
            $existing_product_sku[$item->sku] = $item;
        }

        $erroneous_products_sku = array();
        foreach ($erroneous_products as $item) {
            array_push($erroneous_products_sku, $item->sku);
        }

        for (; $count < $this->process_data_count; $count++) {

            if (empty($product_array[$count]) || empty($product_array[$count]->sku)) {
                continue;
            }

            if(in_array($product_array[$count]->sku, array_values($erroneous_products_sku))) {
                continue;
            }

            if (in_array($product_array[$count]->sku, array_keys($existing_product_sku))) {
                $productObj = $existing_product_sku[$product_array[$count]->sku];
                $discounted_price = !empty($product_array[$count]->discounted_price) ? $product_array[$count]->discounted_price : $this->none;
                $in_stock = !empty($product_array[$count]->sold_out) ? $this->getInStockId($product_array[$count]->sold_out) : true;
                if ($productObj->mrp != $product_array[$count]->mrp ||
                    ($productObj->discounted_price != $discounted_price) ||
                    ($productObj->in_stock != $in_stock)
                ) {

                    if (!$productObj->where('sku', $product_array[$count]->sku)
                        ->update(['mrp' => $product_array[$count]->mrp, 'discounted_price' => $discounted_price, 'in_stock' => $in_stock])
                    ) {
                        continue;
                    }
                    array_push($products, $this->formatDataForApi($product_array[$count], $merchant_id, ProductStatus::Updated));
                    $updated_status = true;
                }
            } else {
                array_push($products, $this->formatDataForApi($product_array[$count], $merchant_id, ProductStatus::NewProduct));
                $products_to_be_saved[$index++] = $this->createArray($product_array[$count], $merchant_id);
            }
        }

        if (!empty($products_to_be_saved)) {
            DB::beginTransaction();
            try {
                if (Products::insert($products_to_be_saved)) {
                    DB::commit();
                    return $products;
                } else {
                    DB::rollback();
                    return false;
                }
            } catch (\Exception $e) {
                DB::rollback();
                return false;
            }
        } elseif ($updated_status) {
            return $products;
        } else {
            return array();
        }
    }

    public function createArray($product, $merchant_id)
    {
        if (in_array($merchant_id, array_keys($this->diff_cur_merchants_id))){
            $price = ceil($product->mrp * env($this->diff_cur_merchants_id[$merchant_id]));
            $discounted_price = !empty($product->discounted_price) ?
                ceil($product->discounted_price * env($this->diff_cur_merchants_id[$merchant_id])) : $this->none;
        } else{
            $price = $product->mrp;
            $discounted_price = !empty($product->discounted_price) ? $product->discounted_price : $this->none;
        }

        return array(
            'merchant_id' => $merchant_id,
            'sku' => $product->sku,
            'mrp' => $price,
            'discounted_price' => $discounted_price,
            'in_stock' => !empty($product->sold_out) ? $this->getInStockId($product->sold_out) : true,
        );
    }

    public function updateImportStatus($import, $imported_item_count, $last_item_key)
    {
        $import->count = $imported_item_count;
        $import->last_item_key = $last_item_key;
        if (!$import->save()) {
            return false;
        }
        return true;
    }

    public function updateJobStatus($job_id)
    {
        try {
            Jobs::where('id', $job_id)->update(['import_completed' => true]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function importMerchantProducts($products)
    {
        $data = array(
            'product_list' => $products,
        );

        $headers = array(
            "Content-Type:application/json",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('API_ORIGIN') . "/merchant/product/create");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }

    public function formatDataForApi($product, $merchant_id, $status)
    {
        if (is_array($product->category)) {
            $category = !empty($product->category[0]) ? $product->category[0] : '';
        } else {
            $category = $product->category;
        }

        if (in_array($merchant_id, array_keys($this->diff_cur_merchants_id))){
            $price = ceil($product->mrp * env($this->diff_cur_merchants_id[$merchant_id]));
            $discounted_price = !empty($product->discounted_price) ?
                ceil($product->discounted_price * env($this->diff_cur_merchants_id[$merchant_id])) : $this->none;
        } else{
            $price = $product->mrp;
            $discounted_price = !empty($product->discounted_price) ? $product->discounted_price : $this->none;
        }


        $product_array = array(
            'name' => $product->product_name,
            'merchant_id' => $merchant_id,
            'price' => $price,
            'url' => $product->url,
            'image0' => $product->image_url,
            'sku_id' => $product->sku,
            'brand' => $merchant_id == 37 ? 'Stalk Buy Love' : $product->brand,
            'category' => $category,
            'color_id' => $product->colors,
            'gender_id' => $this->getGenderId($product->gender),
            'discounted_price' => $discounted_price,
            'in_stock' => !empty($product->sold_out) ? $this->getInStockId($product->sold_out) : true,
            'status' => $status,
            'desc' => $product->product_detail,
            'style_tip' => '',
            'care_information' => !empty($product->washing_care) ? $product->washing_care : '',
        );
        return $product_array;
    }

    public function getInStockId($sold_out)
    {
        if ($sold_out == 'NO' || $sold_out == 'no' || $sold_out == 'No') {
            return true;
        } elseif ($sold_out == 'YES' || $sold_out == 'yes' || $sold_out == 'Yes') {
            return false;
        } else {
            return true;
        }
    }

    public function getGenderId($gender)
    {
        if ($gender == 'Male' || $gender == 'male' || $gender == 'MALE' || $gender == 'Men' || $gender == 'men' || $gender == 'MEN') {
            return Gender::Male;
        } elseif ($gender == 'Female' || $gender == 'female' || $gender == 'FEMALE' || $gender == 'Women' || $gender == 'women' || $gender == 'WOMEN') {
            return Gender::Female;
        } else {
            return '';
        }
    }

    public function updateProductsHavingError($response)
    {
        $products = array();
        $index = $this->start;
        if (!empty($response->rejected_products)) {
            foreach ($response->rejected_products as $error) {
                $products[$index++] = $this->errorProductsArray($error->merchant_id, $error->sku, ProductError::REJECTED_PRODUCTS);
            }
        }
        if (!empty($response->error_import_update)) {
            foreach ($response->error_import_update as $error) {
                $products[$index++] = $this->errorProductsArray($error->merchant_id, $error->sku, ProductError::ERROR_IMPORT_UPDATE);
            }
        }
        if (!empty($response->product_status_wrong)) {
            foreach ($response->product_status_wrong as $error) {
                $products[$index++] = $this->errorProductsArray($error->merchant_id, $error->sku, ProductError::PRODUCT_STATUS_WRONG);
            }
        }
        if (!empty($response->product_not_found)) {
            foreach ($response->product_not_found as $error) {
                $products[$index++] = $this->errorProductsArray($error->merchant_id, $error->sku, ProductError::PRODUCT_NOT_FOUND);
            }
        }
        if (!empty($response->import_validation_fail)) {
            foreach ($response->import_validation_fail as $error) {
                $products[$index++] = $this->errorProductsArray($error->merchant_id, $error->sku_id, ProductError::IMPORT_VALIDATION_FAIL);
            }
        }
        if (!empty($response->error_import_insert)) {
            foreach ($response->error_import_insert as $error) {
                $products[$index++] = $this->errorProductsArray($error->merchant_id, $error->m_product_sku, ProductError::ERROR_IMPORT_INSERT);
            }
        }

        if (!empty($products)) {
            try {
                ErroneousProducts::insert($products);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return true;
    }

    public function errorProductsArray($merchnat_id, $sku, $error_id)
    {
        return array(
            'merchant_id' => $merchnat_id,
            'sku' => $sku,
            'error_id' => $error_id,
        );
    }
}