<?php
namespace App\Http\Mapper;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductMapper extends Controller
{
    protected $bulk_update_fields = ['category_id', 'gender_id', 'primary_color_id'];

    public function validationRules()
    {
        return array(
            'merchant_id' => 'integer',
            'stylist_id' => 'integer',
            'brand_id' => 'integer',
            'gender_id' => 'integer',
            'primary_color_id' => 'integer',
            'category_id' => 'integer',
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

}