<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Lookups\Lookup;
use Validator;
use App\Tip;

class TipMapper extends Controller
{
    protected $fields = ['id', 'name', 'description', 'image', 'created_by as stylist_id', 'video_url', 'image_url', 'external_url',
        'status_id', 'body_type_id', 'occasion_id', 'gender_id', 'budget_id', 'age_group_id', 'created_at'];

    protected $with_array = ['body_type', 'occasion', 'gender', 'budget', 'age_group'];

    protected $dropdown_fields = ['body_type_id', 'occasion_id', 'gender_id', 'budget_id', 'age_group_id'];
    protected $input_fields = ['name', 'description', 'image', 'video_url', 'image_url', 'external_url'];

    public function updateData($table, $where_conditions, $where_raw, $product_ids, $update_clauses)
    {
        try {
            DB::table($table)
                ->where($where_conditions)
                ->whereRaw($where_raw)
                ->whereIn('id', $product_ids)
                ->update($update_clauses);

        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function getDropDowns()
    {
        $lookup = new Lookup();
        return array(
            'genders' => $lookup->type('gender')->get(),
            'body_types' => $lookup->type('body_type')->get(),
            'age_groups' => $lookup->type('age_group')->get(),
            'budgets' => $lookup->type('budget')->get(),
            'occasions' => $lookup->type('occasion')->get(),
        );
    }

    public function getViewProperties($old_values, $tip = null)
    {
        $values_array = array();

        if ($tip) {
            foreach($this->dropdown_fields as $dropdown_field){
                $values_array[$dropdown_field] = isset($old_values[$dropdown_field]) && $old_values[$dropdown_field] != '' ? intval($old_values[$dropdown_field]) : $tip->$dropdown_field;
            }
        } else {
            foreach($this->dropdown_fields as $dropdown_field){
                $values_array[$dropdown_field] = isset($old_values[$dropdown_field]) && $old_values[$dropdown_field] != '' ? intval($old_values[$dropdown_field]) : '';
            }
            foreach($this->input_fields as $input_field){
                $values_array[$input_field] = isset($old_values[$input_field]) && $old_values[$input_field] != '' ? $old_values[$input_field] : '';
            }
        }

        return $values_array;
    }

    public function setObjectProperties($tip, $request)
    {
        $tip->name = isset($request->name) && $request->name != '' ? strtoupper(substr($request->name, 0, 1)) . substr($request->name, 1) : '';
        $tip->description = strtoupper(substr($request->description, 0, 1)) . substr($request->description, 1);
        $tip->image = isset($request->image) && $request->image != '' ? $request->image : '';
        $tip->image_url = isset($request->image_url) && $request->image_url != '' ? $request->image_url : '';
        $tip->video_url = isset($request->video_url) && $request->video_url != '' ? $request->video_url : '';
        $tip->external_url = isset($request->external_url) && $request->external_url != '' ? $request->external_url : '';

        foreach($this->dropdown_fields as $dropdown_field){
            $tip->$dropdown_field = isset($request->$dropdown_field) && $request->$dropdown_field != '' ? $request->$dropdown_field : '';;
        }
        return $tip;
    }

    public function inputValidator($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|max:256|min:5',
            'description' => 'required|min:25',
            'gender_id' => 'required|in:1,2',
        ]);
    }

    public function getTipById($id)
    {
        $tip = Tip::with('status')
            ->with(['entities.product' => function ($query) {
                $query->with('gender', 'primary_color', 'category', 'brand')
                    ->select('id', 'name', 'upload_image', 'product_link', 'product_type', 'price', 'gender_id', 'primary_color_id',
                        'category_id', 'brand_id');
            }])
            ->with(['entities.look' => function ($query) {
                $query->with('gender', 'status', 'occasion', 'body_type')
                    ->select('id', 'name', 'image', 'price', 'gender_id', 'status_id', 'occasion_id', 'body_type_id');
            }])
            ->with($this->with_array)
            ->select($this->fields)
            ->where('id', $id)
            ->first();
        return $tip;

    }

}