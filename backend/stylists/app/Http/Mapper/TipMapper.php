<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Lookups\Lookup;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\Status;
use App\Models\Lookups\AppSections;
use App\Models\Enums\RecommendationType;
use Validator;
use App\Tip;
use App\TipEntity;

class TipMapper extends Controller
{
    protected $fields = ['id', 'name', 'description', 'image', 'created_by', 'video_url', 'image_url', 'external_url',
        'status_id', 'body_type_id', 'occasion_id', 'gender_id', 'budget_id', 'age_group_id', 'created_at', 'status_id'];

    protected $with_array = ['body_type', 'occasion', 'gender', 'budget', 'age_group', 'status'];

    protected $dropdown_fields = ['body_type_id', 'occasion_id', 'gender_id', 'budget_id', 'age_group_id', 'status_id'];
    protected $input_fields = ['name', 'description', 'image', 'video_url', 'image_url', 'external_url'];

    public function getDropDowns()
    {
        $lookup = new Lookup();
        return array(
            'genders' => $lookup->type('gender')->get(),
            'body_types' => $lookup->type('body_type')->get(),
            'age_groups' => $lookup->type('age_group')->get(),
            'budgets' => $lookup->type('budget')->get(),
            'occasions' => $lookup->type('occasion')->get(),
            'statuses' => $lookup->type('status')->get(),
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
        $values_array['is_admin'] = Auth::user()->hasRole('admin');
        $values_array['entity_type_id'] = isset($old_values['entity_type_id']) && $old_values['entity_type_id'] != '' ? $old_values['entity_type_id'] : EntityType::TIP;

        return $values_array;
    }

    public function setObjectProperties($tip, $request)
    {
        $tip->name = isset($request->name) && $request->name != '' ? strtoupper(substr($request->name, 0, 1)) . substr($request->name, 1) : '';
        $tip->description = strtoupper(substr($request->description, 0, 1)) . substr($request->description, 1);
        $tip->image_url = isset($request->image_url) && $request->image_url != '' ? $request->image_url : '';
        $tip->video_url = isset($request->video_url) && $request->video_url != '' ? $request->video_url : '';
        $tip->external_url = isset($request->external_url) && $request->external_url != '' ? $request->external_url : '';

        foreach ($this->dropdown_fields as $dropdown_field) {
            $tip->$dropdown_field = isset($request->$dropdown_field) && $request->$dropdown_field != '' ? $request->$dropdown_field : '';
        }
        $tip->status_id = isset($request->status_id) && $request->status_id != '' ? $request->status_id : Status::Submitted;
        return $tip;
    }

    public function saveEntities($tip_id, $products, $looks)
    {
        $new_product_ids = $products ? explode(',', $products) : [];
        $new_look_ids = $looks ? explode(',', $looks) : [];

        $tip_entities = $this->getExistingEntities($tip_id);

        $existing_look_ids = [];
        $existing_product_ids = [];

        $entity_type_look = EntityType::LOOK;
        $entity_type_product = EntityType::PRODUCT;

        if (count($tip_entities) > 0) {
            foreach ($tip_entities as $tip_entity) {
                if ($tip_entity->entity_type_id == $entity_type_look) {
                    array_push($existing_look_ids, $tip_entity->entity_id);
                } elseif ($tip_entity->entity_type_id == $entity_type_product) {
                    array_push($existing_product_ids, $tip_entity->entity_id);
                }
            }
        }

        $products_to_delete = array_diff($existing_product_ids, $new_product_ids);
        $looks_to_delete = array_diff($existing_look_ids, $new_look_ids);
        $products_to_add = array_diff($new_product_ids, $existing_product_ids);
        $looks_to_add = array_diff($new_look_ids, $existing_look_ids);

        $insert_entities = [];
        $index = 0;
        foreach ($products_to_add as $entity_product_id) {
            $insert_entities[$index++] = array(
                'tip_id' => $tip_id,
                'entity_type_id' => $entity_type_product,
                'entity_id' => $entity_product_id,
            );
        }

        foreach ($looks_to_add as $entity_look_id) {
            $insert_entities[$index++] = array(
                'tip_id' => $tip_id,
                'entity_type_id' => $entity_type_look,
                'entity_id' => $entity_look_id,
            );
        }

        $status = true;
        $message = '';

        $delete_entities_query = '';
        foreach ($products_to_delete as $item) {
            $delete_entities_query = $delete_entities_query . " OR (tip_id = '{$tip_id}' AND entity_type_id = '{$entity_type_product}' AND entity_id = '{$item}')";
        }
        foreach ($looks_to_delete as $item) {
            $delete_entities_query = $delete_entities_query . " OR (tip_id = '{$tip_id}' AND entity_type_id = '{$entity_type_look}' AND entity_id = '{$item}')";
        }

        try {
            if (count($insert_entities) > 0) {
                TipEntity::insert($insert_entities);
            }
            if (!empty($delete_entities_query)) {
                TipEntity::whereRaw(substr($delete_entities_query, 4))->delete();
            }
        } catch (\Exception $e) {
            $status = false;
            $message = $e->getMessage();
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
            'video_url' => 'url',
            'image_url' => 'url',
            'external_url' => 'url',
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
            ->with([('createdBy') => function ($query) {
                $query->select('id', 'name', 'image');
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

    public function getExistingEntities($tip_id)
    {
        $tip_entities = TipEntity::where('tip_id', $tip_id)->get();
        return $tip_entities;
    }

    public function saveTipDetails($tip, $request)
    {
        if ($tip->status_id !== Status::Active && !empty($request->status_id) && $request->status_id == Status::Active && empty($tip->image)) {
            return array(
                'status' => false,
                'message' => 'Upload image first for this tip',
            );
        }

        $logged_in_stylist = $request->user()->id != '' ? $request->user()->id : '';
        $tip = $this->setObjectProperties($tip, $request);

        if ($tip->exists) {
            $tip->updated_by = $logged_in_stylist;
        } else {
            $tip->created_by = $logged_in_stylist;
            $tip->created_at = date('Y-m-d H:i:s');
        }

        DB::beginTransaction();
        try {
            $tip->save();
            $result = $this->saveEntities($tip->id, $request->input('product_ids'), $request->input('look_ids'));

            if ($result['status'] == false) {
                DB::rollback();
                return $result;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return array(
                'status' => false,
                'message' => $e->getMessage(),
            );
        }

        return $result;
    }
}