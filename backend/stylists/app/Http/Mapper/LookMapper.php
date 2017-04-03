<?php
namespace App\Http\Mapper;

use App\Category;
use App\Http\Controllers\Controller;
use App\Look;
use App\Models\Enums\Currency;
use App\Models\Enums\Category as CategoryEnum;
use App\Models\Enums\PriceType as PriceTypeEnum;
use App\Models\Looks\LookPrice;
use App\Models\Lookups\PriceType;
use App\Product;
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
use App\LookProduct;
use App\Recommendation;

class LookMapper extends Controller
{
    protected $fields = ['id', 'name', 'description', 'image', 'stylist_id',
        'status_id', 'body_type_id', 'occasion_id', 'gender_id', 'budget_id', 'age_group_id', 'created_at'];

    protected $with_array = ['body_type', 'occasion', 'gender', 'budget', 'age_group', 'status', 'look_products.product', 'prices'];

    protected $dropdown_fields = ['body_type_id', 'occasion_id', 'gender_id', 'budget_id', 'age_group_id', 'status_id', 'category_id', 'occasion_id'];
    protected $input_fields = ['name', 'description', 'image', 'video_url', 'image_url', 'external_url'];

    public function getDropDowns()
    {
        $lookup = new Lookup();
        $dropDowns = array(
            'genders' => $lookup->type('gender')->get(),
            'body_types' => $lookup->type('body_type')->get(),
            'age_groups' => $lookup->type('age_group')->get(),
            'budgets' => $lookup->type('budget')->get(),
            'occasions' => $lookup->type('occasion')->get(),
            'statuses' => $lookup->type('status')->get(),
            'categories' => Category::whereIn('id', [CategoryEnum::Men, CategoryEnum::Women, CategoryEnum::House])->get(),
        );
        if (env('IS_NICOBAR'))
            $dropDowns['occasions'] = $this->categoryWiseOccasion($dropDowns['occasions']);
        return $dropDowns;
    }

    public function getViewProperties($old_values, $look = null)
    {
        $values_array = array();

        if ($look) {
            foreach ($this->dropdown_fields as $dropdown_field) {
                $values_array[$dropdown_field] = isset($old_values[$dropdown_field]) && $old_values[$dropdown_field] != '' ? intval($old_values[$dropdown_field]) : intval($look->$dropdown_field);
            }
            $values_array['is_recommended'] = Recommendation::checkRecommended($look);
        } else {
            foreach ($this->dropdown_fields as $dropdown_field) {
                $values_array[$dropdown_field] = isset($old_values[$dropdown_field]) && $old_values[$dropdown_field] != '' ? intval($old_values[$dropdown_field]) : '';
            }
            foreach ($this->input_fields as $input_field) {
                $values_array[$input_field] = isset($old_values[$input_field]) && $old_values[$input_field] != '' ? $old_values[$input_field] : '';
            }
        }
        $values_array['is_admin'] = Auth::user()->hasRole('admin');
        $values_array['entity_type_id'] = isset($old_values['entity_type_id']) && $old_values['entity_type_id'] != '' ? $old_values['entity_type_id'] : EntityType::LOOK;

        return $values_array;
    }

    public function setObjectProperties($look, $request)
    {
        $look->name = isset($request->name) && $request->name != '' ? strtoupper(substr($request->name, 0, 1)) . substr($request->name, 1) : '';
        $look->description = strtoupper(substr($request->description, 0, 1)) . substr($request->description, 1);

        foreach ($this->dropdown_fields as $dropdown_field) {
            $look->$dropdown_field = isset($request->$dropdown_field) && $request->$dropdown_field != '' ? $request->$dropdown_field : 1;
        }
        $look->status_id = isset($request->status_id) && $request->status_id != '' ? $request->status_id : Status::Submitted;
        $look->is_collage=false;
        return $look;
    }

    public function saveProducts($look_id, $products)
    {
        $new_product_ids = $products ? explode(',', $products) : [];

        $look_products = $this->getExistingProducts($look_id);

        $existing_product_ids = [];
        if (count($look_products) > 0) {
            foreach ($look_products as $look_product) {
                array_push($existing_product_ids, $look_product->product_id);
            }
        }

        $products_to_delete = array_diff($existing_product_ids, $new_product_ids);
        $products_to_add = array_diff($new_product_ids, $existing_product_ids);

        $insert_products = [];
        $index = 0;
        foreach ($products_to_add as $product_id) {
            $insert_products[$index++] = array(
                'look_id' => $look_id,
                'product_id' => $product_id,
            );
        }

        $status = true;
        $message = '';

        $delete_query = '';
        foreach ($products_to_delete as $item) {
            $delete_query = $delete_query . " OR (look_id = '{$look_id}' AND product_id = '{$item}')";
        }

        try {
            if (count($insert_products) > 0) {
                LookProduct::insert($insert_products);
            }
            if (!empty($delete_query)) {
                LookProduct::whereRaw(substr($delete_query, 4))->delete();
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
            'gender_id' => 'in:1,2',
        ]);
    }

    public function getLookById($id)
    {
        $entity_type_id = EntityType::LOOK;
        $look = Look::with([('stylist') => function ($query) {
            $query->select('id', 'name', 'image');
        }])
            ->with(['recommendation' => function($query) use ($entity_type_id) {
                $query->where('entity_type_id', $entity_type_id);
                $query->first();
            }])
            ->with($this->with_array)
            ->select($this->fields)
            ->where('id', $id)
            ->first();
        return $look;
    }

    public function getPopupProperties(Request $request)
    {
        $view_properties = array();
        $view_properties['popup_entity_type_ids'] = array(
            EntityType::PRODUCT,
        );

        $view_properties['entity_type_names'] = array(
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

    public function getExistingProducts($look_id)
    {
        $product_prices = function ($query) {
            $query->where(['price_type_id' => PriceTypeEnum::RETAIL, 'currency_id' => Currency::INR]);
        };
        $look_products = LookProduct::with(['product_prices' => $product_prices])->where('look_id', $look_id)->get();
        return $look_products;
    }

    public function saveLookDetails($look, $request, $uploadMapperObj = null)
    {
        if ($look->status_id !== Status::Active && !empty($request->status_id) && $request->status_id == Status::Active && empty($look->image)) {
            return array(
                'status' => false,
                'message' => 'Upload image to make status Active',
            );
        }

        $look = $this->setObjectProperties($look, $request);
        $logged_in_stylist = $request->user()->id != '' ? $request->user()->id : '';

        if (!$look->exists) {
            $look->stylist_id = $logged_in_stylist;
        }

        DB::beginTransaction();
        try {
            if ($uploadMapperObj) {
                $look->image = $uploadMapperObj->moveImageInFolder($request);
            }
            $look->save();
            $result = $this->saveProducts($look->id, $request->input('product_ids'));
            if ($result['status'] == false) {
                DB::rollback();
                return $result;
            }
            $response = $this->evaluatePrice($look->id);
            if ($response['priceExists']) {
                LookPrice::where(['look_id' => $look->id])->delete();
            }
            LookPrice::insert($response['data']);
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

    public function getProducts($look_id)
    {
        $look_products = $this->getExistingProducts($look_id);
        $products_ids = array();
        foreach ($look_products as $look_product) {
            array_push($products_ids, $look_product->product_id);
        }

        $products = Product::with(['product_prices'])
            ->whereIn('id', $products_ids)
            ->select('id')
            ->get();
        return $products;
    }

    public function evaluatePrice($look_id)
    {
        $priceArr = array();
        $currencies = \App\Models\Lookups\Currency::get();
        $currencies_arr = array();

        $products = $this->getProducts($look_id);
        $priceTypes = PriceType::get();

        foreach ($currencies as $currency) {
            $currencies_arr[$currency->id] = $currency->id;
        }

        foreach ($products as $product) {
            foreach ($product->product_prices as $product_price) {
                foreach ($priceTypes as $priceType) {
                    if ($priceType->id == $product_price->price_type_id) {
                        $priceArr[$currencies_arr[$product_price->currency_id]][$priceType->id] =
                            isset($priceArr[$currencies_arr[$product_price->currency_id]][$priceType->id]) ?
                                $priceArr[$currencies_arr[$product_price->currency_id]][$priceType->id] + $product_price->value :
                                $product_price->value;
                    }
                }
            }
        }

        $priceData = array();
        foreach ($priceArr as $currency => $prices) {
            foreach ($prices as $priceType => $price) {
                $priceData[] = array(
                    'look_id' => $look_id,
                    'price_type_id' => $priceType,
                    'currency_id' => $currency,
                    'value' => $price,
                );
            }
        }

        $lookPriceExists = LookPrice::select('look_id', 'price_type_id', 'currency_id', 'value')
            ->where(['look_id' => $look_id]);
        foreach ($priceData as $data) {
            $lookPriceExists = $lookPriceExists->orWhere($data);
        }
        $lookPriceExists = $lookPriceExists->get();

        if ($lookPriceExists) {
            $priceExisis = true;
        } else {
            $priceExisis = false;
        }
        return ['priceExists' => $priceExisis, 'data' => $priceData];
    }

    public function updateStatus($look_id, $status_id)
    {
        try{
            Look::where(['id' => $look_id])->update(['status_id' => $status_id]);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function getPrice($prices) {
        $lookPrice = 0;
        foreach ($prices as $price) {
            if ($price->currency_id == Currency::INR and $price->price_type_id == PriceTypeEnum::RETAIL) {
                $lookPrice = $price->value;
            }
        }
        return $lookPrice;
    }

    public function categoryWiseOccasion($occasions)
    {
        $occasionsArr = array();
        foreach ($occasions as $occasion) {
            if (!isset($occasionsArr[$occasion->category_id]))
                $occasionsArr[$occasion->category_id] = array();
            $occasionsArr[$occasion->category_id][] = $occasion;
        }
        return $occasionsArr;
    }
}