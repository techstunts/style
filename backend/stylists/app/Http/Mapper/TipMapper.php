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

    public function getViewProperties($request)
    {
        return array(
            'name' => isset($request->name) && $request->name != '' ? $request->name : '',
            'description' => isset($request->description) && $request->description != '' ? $request->description : '',
            'image' => isset($request->image) && $request->image != '' ? $request->image : '',
            'image_url' => isset($request->image_url) && $request->image_url != '' ? $request->image_url : '',
            'video_url' => isset($request->video_url) && $request->video_url != '' ? $request->video_url : '',
            'external_url' => isset($request->external_url) && $request->external_url != '' ? $request->external_url : '',
            'body_type_id' => isset($request->body_type_id) && $request->body_type_id != '' ? $request->body_type_id : '',
            'budget_id' => isset($request->budget_id) && $request->budget_id != '' ? $request->budget_id : '',
            'age_group_id' => isset($request->age_group_id) && $request->age_group_id != '' ? $request->age_group_id : '',
            'occasion_id' => isset($request->occasion_id) && $request->occasion_id != '' ? $request->occasion_id : '',
            'gender_id' => isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '',
        );
    }

    public function setObjectProperties($tip, $request)
    {
        $tip->name = isset($request->name) && $request->name != '' ? strtoupper(substr($request->name, 0, 1)) . substr($request->name, 1) : '';
        $tip->description = strtoupper(substr($request->description, 0, 1)) . substr($request->description, 1);
        $tip->gender_id = $request->gender_id;
        $tip->image = isset($request->image) && $request->image != '' ? $request->image : '';
        $tip->image_url = isset($request->image_url) && $request->image_url != '' ? $request->image_url : '';
        $tip->video_url = isset($request->video_url) && $request->video_url != '' ? $request->video_url : '';
        $tip->external_url = isset($request->external_url) && $request->external_url != '' ? $request->external_url : '';
        $tip->budget_id = isset($request->budget_id) && $request->budget_id != '' ? $request->budget_id : '';
        $tip->age_group_id = isset($request->age_group_id) && $request->age_group_id != '' ? $request->age_group_id : '';
        $tip->body_type_id = isset($request->body_type_id) && $request->body_type_id != '' ? $request->body_type_id : '';
        $tip->occasion_id = isset($request->occasion_id) && $request->occasion_id != '' ? $request->occasion_id : '';
        $tip->created_by = $request->user()->id != '' ? $request->user()->id : '';

        return $tip;
    }

    public function inputValidator($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|max:256|min:5',
            'description' => 'required|min:25',
            'gender_id' => 'required',
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