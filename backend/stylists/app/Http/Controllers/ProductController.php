<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Models\Lookups\Gender;
use App\Models\Lookups\Color;
use App\Models\Lookups\Tag;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\AppSections;
use App\Merchant;
use App\Models\Lookups\Lookup;
use App\Product;
use App\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Mapper\ProductMapper;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProductController extends Controller
{
    protected $filter_ids = ['stylist_id', 'merchant_id', 'brand_id', 'category_id', 'gender_id', 'primary_color_id', 'rating_id', 'approved_by'];
    protected $filters = ['stylists', 'merchants', 'brands', 'categories', 'genders', 'colors', 'ratings', 'approvedBy'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if ($id) {
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getList(Request $request)
    {
        $this->base_table = 'products';
        $this->initWhereConditions($request);
        if ($request->input('in_stock') != "") {
            $this->setInStockCondition($request->input('in_stock'));
        }
        $this->initFilters();

        $lookup = new Lookup();
        $category_obj = new Category();

        $view_properties = array(
            'stylists' => $this->stylists,
            'merchants' => $this->merchants,
            'brands' => $this->brands,
            'categories' => $this->categories,
            'genders' => $this->genders,
            'colors' => $this->colors,
            'ratings' => $this->ratings,
            'approvedBy' => $this->approvedBy,
            'category_tree' => $category_obj->getCategoryTree(),
            'gender_list' => $lookup->type('gender')->get(),
            'color_list' => $lookup->type('color')->get(),
            'ratings_list' => $lookup->type('rating')->where('status_id', true)->get(),
            'tags_list' => $lookup->type('tags')->get(),
        );

        foreach ($this->filter_ids as $filter) {
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }
        $view_properties['tag_id'] = $request->has('tag_id') && $request->input('tag_id') !== "" ? intval($request->input('tag_id')) : "";
        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');
        $view_properties['in_stock'] = $request->input('in_stock');

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');

        $view_properties['min_price'] = $request->input('min_price');
        $view_properties['max_price'] = $request->input('max_price');
        $view_properties['min_discount'] = $request->input('min_discount');
        $view_properties['max_discount'] = $request->input('max_discount');

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $entity_nav_tabs = array(
            EntityType::CLIENT
        );

        $view_properties['entity_type_names'] = array(
            EntityTypeName::CLIENT
        );
        $view_properties['nav_tab_index'] = '0';
        $view_properties['url'] = 'product/';

        $genders_list = Gender::all()->keyBy('id');
        $genders_list[0] = new Gender();

        $products =
            Product::with('category', 'primary_color', 'secondary_color', 'product_tags.tag')
                ->where($this->where_conditions)
                ->whereRaw($this->where_raw)
                ->orderBy('created_at', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $product_mapper = new ProductMapper();
        foreach ($products as $product) {
            $product->omg_product_link = $product_mapper->getDeepLink($product->merchant_id, $product->product_link);
        }

        $view_properties['products'] = $products;
        $view_properties['genders_list'] = $genders_list;
        $view_properties['logged_in_stylist_id'] = Auth::user()->id;
        $view_properties['app_sections'] = AppSections::all();
        $view_properties['popup_entity_type_ids'] = $entity_nav_tabs;
        $view_properties['entity_type_to_send'] = EntityType::PRODUCT;
        $view_properties['recommendation_type_id'] = RecommendationType::MANUAL;
        $view_properties['is_owner_or_admin'] = Auth::user()->hasRole('admin');
        $view_properties['autosuggest_type'] = 'category';

        return view('product.list', $view_properties);
    }

    public function initWhereConditions(Request $request)
    {
        parent::initWhereConditions($request);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'price' => 'required|numeric',
            'desc' => 'required|min:5',
            'merchant' => 'required|min:4',
            'brand' => 'required|min:2',
            'category' => 'required|min:2',
            'gender' => 'required|min:4',
            'color1' => 'required|min:3',
            'color2' => 'min:3',
        ]);

        $error_messages = "";
        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $v) {
                $error_messages .= $v[0] . "<br/>";
            }
            echo json_encode([false, $error_messages]);
            return;
        }

        $brand_name = preg_replace('/[^a-zA-Z0-9_&-@\' \']/', null, $request->input('brand'));
        $brand_name = trim($brand_name);

        $merchant = Merchant::where('name', $request->input('merchant'))->first();
        $brand = Brand::firstOrCreate(['name' => $brand_name]);
        $category = Category::where(['name' => $request->input('category')])->first();
        $gender = Gender::where(['name' => $request->input('gender')])->first();
        $primary_color = Color::where(['name' => $request->input('color1')])->first();
        $secondary_color = Color::where(['name' => $request->input('color2')])->first();

        $required_values = array('merchant', 'category', 'gender', 'primary_color');
        $error_messages = "";
        foreach ($required_values as $v) {
            if (!isset($$v) || !$$v->id) {
                $error_messages .= strtoupper(substr($v, 0, 1)) . substr($v, 1) . " not found. Please contact admin.<br/>";
            }
        }
        if ($error_messages != "") {
            echo json_encode([false, $error_messages]);
            return;
        }

        if ($merchant && $request->input('name') && $category && $gender && $primary_color) {
            $sku_id = !empty($request->input('sku_id')) ? $request->input('sku_id') : 'isy_' . (intval(time()) + rand(0, 10000));
            $product = Product::firstOrCreate(['sku_id' => $sku_id, 'merchant_id' => $merchant->id]);

            $product->merchant_id = $merchant->id;
            $product->sku_id = $sku_id;
            $product->name = htmlentities($request->input('name'));
            $product->description = htmlentities($request->input('desc'));
            $product->price = str_replace(array(",", " "), "", $request->input('price'));
            $product->product_link = $request->input('url');
            $product->upload_image = $request->input('image0');
            $product->image_name = $request->input('image0');
            $product->brand_id = $brand->id;
            $product->category_id = $category->id;
            $product->gender_id = $gender->id;
            $product->primary_color_id = $primary_color->id;
            $product->secondary_color_id = $secondary_color ? $secondary_color->id : "";
            $product->stylist_id = $request->user()->id;

            if ($product->save()) {
                $product_url = url('product/view/' . $product->id);
                echo json_encode([true, $product_url]);
            } else {
                echo json_encode([false, "Product save failed. Please contact admin."]);
            }
        } else {
            echo json_encode([false, "Product required info missing. Please contact admin."]);
        }
    }

    public function getView()
    {
        $product = Product::find($this->resource_id);
        $view_properties = null;
        if ($product) {
            $category = $product->category;
            $merchant = $product->merchant;
            $brand = $product->brand;
            $gender = $product->gender;
            $looks = $product->looks;
            $stylist = $product->stylist;

            $view_properties = array('product' => $product, 'looks' => $looks, 'merchant' => $merchant,
                'category' => $category, 'gender' => $gender, 'brand' => $brand, 'primary_color' => $product->primary_color, 'secondary_color' =>
                    $product->secondary_color, 'stylist' => $stylist);
        } else {
            return view('404', array('title' => 'Product not found'));
        }

        return view('product.view', $view_properties);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
        $product = Product::find($this->resource_id);
        $view_properties = null;
        if ($product) {
            $lookup = new Lookup();
            $category_obj = new Category();

            $view_properties['product'] = $product;
            $view_properties['gender_id'] = intval($product->gender_id);
            $view_properties['genders'] = $lookup->type('gender')->get();
            $view_properties['category_id'] = intval($product->category_id);
            $view_properties['category_tree'] = $category_obj->getCategoryTree();
            $view_properties['primary_color_id'] = intval($product->primary_color_id);
            $view_properties['colors'] = $lookup->type('color')->get();
            $view_properties['price'] = $product->price;
        } else {
            return view('404', array('title' => 'Product not found'));
        }

        return view('product.edit', $view_properties);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:256|min:5',
            'description' => 'required|min:25',
            'gender_id' => 'required',
            'category_id' => 'required',
            'primary_color_id' => 'required',
            'price' => 'required|min:2',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect('product/edit/' . $this->resource_id)
                ->withErrors($validator)
                ->withInput();
        }

        $product = Product::find($this->resource_id);
        $product->name = isset($request->name) && $request->name != '' ? $request->name : '';
        $product->description = isset($request->description) && $request->description != '' ? $request->description : '';
        $product->price = isset($request->price) && $request->price != '' ? $request->price : '';
        $product->gender_id = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
        $product->category_id = isset($request->category_id) && $request->category_id != '' ? $request->category_id : '';
        $product->primary_color_id = isset($request->primary_color_id) && $request->primary_color_id != '' ? $request->primary_color_id : '';
        $product->save();

        return redirect('product/view/' . $this->resource_id);
    }

    public function postBulkUpdate(Request $request)
    {

        if (!Auth::user()->hasRole('admin')) {
            return Redirect::back()
                ->withErrors(['You do not have permission to do bulk update'])
                ->withInput();
        }
        if (is_null($request->input('product_id')) || empty($request->input('product_id'))) {
            return Redirect::back()
                ->withErrors(['Please select at least one item to be updated'])
                ->withInput();
        }
        $productMapperObj = new ProductMapper();

        $valdation_clauses = $productMapperObj->validationRules();
        $validator = Validator::make($request->all(), $valdation_clauses);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $k => $v) {
                echo $v[0] . "<br/>";
            }

            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        $this->base_table = 'products';

        $product_ids = explode(',', $request->input('product_id'));

        $update_clauses = $productMapperObj->getUpdateClauses($request);
        if (count($update_clauses) == 0 and empty($request->input('tag_id'))) {
            return Redirect::back()
                ->withErrors(['Please specify at least 1 field to update'])
                ->withInput();
        }

        DB::beginTransaction();
        $addTagStatus = true;
        if (!empty($request->input('tag_id'))) {
            if (!$productMapperObj->addTagToProducts($product_ids, $request->input('tag_id'))) {
                $addTagStatus = false;
            }
        }
        $update_clauses_status = true;
        if (!empty($update_clauses) && !$productMapperObj->updateData($this->base_table, $this->where_conditions, $this->where_raw, $product_ids, $update_clauses)) {
            $update_clauses_status = false;
        }

        if ((!empty($request->input('tag_id')) && !$addTagStatus) || !$update_clauses_status) {
            DB::rollback();
            return Redirect::back()
                ->withErrors(['Error updating data'])
                ->withInput();
        } else {
            DB::commit();
            return Redirect::back()
                ->withErrors(['Records updated'])
                ->withInput();
        }
    }

    public function getTags()
    {
        $lookup = new Lookup();
        $tags = $lookup->type('tags')->get();
        return $tags;
    }

    public function postAddTag(Request $request)
    {
        $responseValidate = $this->validateInput($request);
        if (!$responseValidate['status']) {
            return array('status' => false, 'message' => $responseValidate['message']);
        }

        $tagName = $request->input('tag');
        $lookup = new Lookup();
        $tagObj = $lookup->type('tags')->where(['name' => $tagName])->first();

        if (!$tagObj) {
            return array('status' => false, 'message' => 'Undefined tag');
        }
        $product_id = $request->input('product_id');
        $productTagExists = ProductTag::where(['product_id' => $product_id, 'tag_id' => $tagObj->id])->exists();

        if ($productTagExists) {
            return array('status' => false, 'message' => 'Already tagged');
        }

        try {
            $newProductTag = new ProductTag();
            $newProductTag->product_id = $product_id;
            $newProductTag->tag_id = $tagObj->id;
            $newProductTag->save();
            $status = true;
            $message = 'Tagged successfully';
        } catch (\Exception $e) {
            $status = false;
            $message = 'Tagging error' . $e->getMessage();
        }
        return array('status' => $status, 'message' => $message);
    }

    public function postRemoveTag(Request $request)
    {
        $responseValidate = $this->validateInput($request);
        if (!$responseValidate['status']) {
            return array('status' => false, 'message' => $responseValidate['message']);
        }

        $tagName = trim($request->input('tag'));
        $lookup = new Lookup();
        $tagObj = $lookup->type('tags')->where(['name' => $tagName])->first();

        if (!$tagObj) {
            return array('status' => false, 'message' => 'Undefined tag');
        }

        $product_id = $request->input('product_id');
        $productTagExists = ProductTag::where(['product_id' => $product_id, 'tag_id' => $tagObj->id])->first();

        if (!$productTagExists) {
            return array('status' => false, 'message' => 'Tag does not exist for this product');
        }

        try {
            ProductTag::where(['product_id' => $product_id, 'tag_id' => $productTagExists->tag_id])->delete();
            $status = true;
            $message = 'Tag removed successfully';
        } catch (\Exception $e) {
            $status = false;
            $message = 'Error removing tag' . $e->getMessage();
        }
        return array('status' => $status, 'message' => $message);
    }

    public function validateInput($request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'tag' => 'required|min:2',
        ]);

        $validator_err_msg = '';
        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $k => $v) {
                $validator_err_msg .= $v[0] . PHP_EOL;
            }
            return array('status' => false, 'message' => $validator_err_msg);
        }
        return array('status' => true, 'message' => '');
    }
    public function postCreateTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tags_name' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $k => $v) {
                echo $v[0] . "<br/>";
            }
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        $tagNameStr = $request->input('tags_name');
        $tagNameArr = explode(',', $tagNameStr);
        $createTagsArr = array();
        $lookup = new Lookup();
        $tagsLookUp = $lookup->type('tags');
        foreach ($tagNameArr as $item) {
            $tagName = trim($item);
            $tagExists = $tagsLookUp->where(['name' => $tagName])->exists();
            if (!$tagExists) {
                $createTagsArr[] = ['name' => $tagName];
            }
        }
        if (count($createTagsArr) > 0) {
            try{
                Tag::insert($createTagsArr);
                $message = 'Tags created successfully';
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = 'No new tags to create';
        }

        return Redirect::back()
            ->withErrors([$message])
            ->withInput();
    }
}
