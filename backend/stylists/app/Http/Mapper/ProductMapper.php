<?php
namespace App\Http\Mapper;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\ProductTag;
use App\AgencyMerchantProgramme;
use App\Models\Enums\AgencyMerchantProgrammeStatus;
use Illuminate\Support\Facades\Log;

class ProductMapper extends Controller
{
    protected $bulk_update_fields = ['category_id', 'gender_id', 'primary_color_id', 'rating_id', 'status_id'];

    public function validationRules()
    {
        return array(
            'merchant_id' => 'integer',
            'stylist_id' => 'integer',
            'brand_id' => 'integer',
            'gender_id' => 'integer',
            'primary_color_id' => 'integer',
            'category_id' => 'integer',
            'status_id' => 'integer',
            'search' => 'regex:/[\w]+/',
        );
    }
    public function updateData($table, $where_conditions, $where_raw, $product_ids, $update_clauses)
    {
        try {
            DB::table($table)
                ->where($where_conditions)
                ->whereRaw($where_raw)
                ->whereIn('id', $product_ids)
                ->update($update_clauses);

        } catch (\Exception $e){
            Log::info($e->getMessage());
            return false;
        }
        return true;
    }

    public function getUpdateClauses($request)
    {
        $update_clauses = [];

        foreach ($this->bulk_update_fields as $filter) {
            if ($request->input($filter) != "") {
                $valdation_clauses[$filter] = 'required|integer|min:1';

                unset($this->where_conditions[$this->base_table . '.' . $filter]);

                $update_clauses[$filter] = $request->input($filter);
            }

            if ($request->input('old_' . $filter) != "") {
                $this->where_conditions[$this->base_table . '.' . $filter] = $request->input('old_' . $filter);
            }
        }

        return $update_clauses;
    }

    public function addTagToProducts($product_ids, $tag_id)
    {
        $all_tags = array();
        $query = '';

        foreach ($product_ids as $product_id) {
            $query .= " OR (product_id=$product_id AND tag_id=$tag_id)";
        }
        $existingTaggedProducts = ProductTag::whereRaw(substr($query, 4))->get();
        $existingTaggedProductIds = array();
        if (count($existingTaggedProducts) > 0) {
            foreach ($existingTaggedProducts as $existingTaggedProduct) {
                array_push($existingTaggedProductIds, $existingTaggedProduct->product_id);
            }
            $product_ids = array_diff(array_values($product_ids), array_values($existingTaggedProductIds));
        }

        foreach ($product_ids as $product_id) {
            $all_tags[] = array('product_id' => $product_id, 'tag_id' => $tag_id);
        }

        try {
            ProductTag::insert($all_tags);
            $status = true;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            $status = false;
        }

        return $status;
    }

    protected $agency_programme_ids = [];
    protected $affiliate_id = 872525;
    protected $omg_url_pattern = 'http://clk.omgt5.com/?AID={aid}&PID={pid}&Type=12&r={url}';

    protected function init()
    {
        $agency_merchant_programmes =
            AgencyMerchantProgramme::where('status_id', AgencyMerchantProgrammeStatus::Active)
                ->select('merchant_id', 'agency_programme_id')
                ->get();

        foreach ($agency_merchant_programmes as $agency_merchant_programme) {
            $this->agency_programme_ids[$agency_merchant_programme->merchant_id] = $agency_merchant_programme->agency_programme_id;
        }
    }

    public function getDeepLink($merchant_id = 0, $product_link)
    {
        if ($this->agency_programme_ids == []) {
            $this->init();
        }

        if ($merchant_id == 0 || !isset($this->agency_programme_ids[$merchant_id])) {
            return $product_link;
        }

        $deep_link = str_replace("{aid}", $this->affiliate_id, $this->omg_url_pattern);
        $deep_link = str_replace("{pid}", $this->agency_programme_ids[$merchant_id], $deep_link);
        $deep_link = str_replace("{url}", $product_link, $deep_link);

        return $deep_link;
    }

}