<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lookups\Lookup;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Lookups\AppSections;
use App\Models\Enums\RecommendationType;
use Validator;
use App\Tip;
use App\TipEntity;

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
            foreach ($this->dropdown_fields as $dropdown_field) {
                $values_array[$dropdown_field] = isset($old_values[$dropdown_field]) && $old_values[$dropdown_field] != '' ? intval($old_values[$dropdown_field]) : $tip->$dropdown_field;
            }
        } else {
            foreach ($this->dropdown_fields as $dropdown_field) {
                $values_array[$dropdown_field] = isset($old_values[$dropdown_field]) && $old_values[$dropdown_field] != '' ? intval($old_values[$dropdown_field]) : '';
            }
            foreach ($this->input_fields as $input_field) {
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

        foreach ($this->dropdown_fields as $dropdown_field) {
            $tip->$dropdown_field = isset($request->$dropdown_field) && $request->$dropdown_field != '' ? $request->$dropdown_field : '';;
        }
        return $tip;
    }

    public function saveEntities($tip_id, $products, $looks)
    {
        $entity_product_ids = $products ? explode(',', $products) : [];

        $entity_array = [];
        $index = 0;
        foreach ($entity_product_ids as $entity_product_id) {
            $entity_array[$index++] = array(
                'tip_id' => $tip_id,
                'entity_type_id' => EntityType::PRODUCT,
                'entity_id' => $entity_product_id,
            );
        }

        $entity_look_ids = $looks ? explode(',', $looks) : [];
        foreach ($entity_look_ids as $entity_look_id) {
            $entity_array[$index++] = array(
                'tip_id' => $tip_id,
                'entity_type_id' => EntityType::LOOK,
                'entity_id' => $entity_look_id,
            );
        }
        $status = true;
        $message = '';
        if (count($entity_array) > 0) {
            try {
                TipEntity::insert($entity_array);
            } catch (\Exception $e) {
                $status = false;
                $message = $e->getMessage();
            }
        }

        return array(
            'status' => $status,
            'message' => $message,
        );
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
        $entity_type_look = EntityType::LOOK;
        $entity_type_product = EntityType::PRODUCT;

        $look = function ($query) {
            $query->with('gender', 'status', 'occasion', 'body_type')
                ->select('id', 'name', 'image', 'price', 'gender_id', 'status_id', 'occasion_id', 'body_type_id');
        };

        $product = function ($query) {
            $query->with('gender', 'primary_color', 'category', 'brand')
                ->select('id', 'name', 'upload_image', 'product_link', 'product_type', 'price', 'gender_id', 'primary_color_id',
                    'category_id', 'brand_id');
        };

        $tip = Tip::with('status')
            ->with([('product_entities') => function ($query) use ($product, $entity_type_product) {
                $query->with(['product' => $product])
                    ->where('entity_type_id', $entity_type_product);
            }])
            ->with([('look_entities') => function ($query) use ($look, $entity_type_look) {
                $query->with(['look' => $look])
                    ->where('entity_type_id', $entity_type_look);
            }])
            ->with($this->with_array)
            ->select($this->fields)
            ->where('id', $id)
            ->first();
        return $tip;

    }

    public function getPopupProperties(Request $request)
    {
        $view_properties = array();
        $view_properties['popup_entity_type_ids'] = array(
            EntityType::LOOK,
            EntityType::PRODUCT,
        );

        $view_properties['entity_type_names'] = array(
            EntityTypeName::LOOK,
            EntityTypeName::PRODUCT,
        );
        $view_properties['nav_tab_index'] = '0';
        $view_properties['add_entity'] = true;

        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');

        $view_properties['app_sections'] = AppSections::all();
        $view_properties['recommendation_type_id'] = RecommendationType::MANUAL;
        $view_properties['show_price_filters'] = 'YES';

        return $view_properties;
    }

}