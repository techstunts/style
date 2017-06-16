<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Http\Mapper\Mapper;
use App\Models\Enums\Currency;
use App\Models\Enums\ImageType;
use App\Models\Enums\InStock;
use App\Models\Enums\PriceType;
use App\Models\Looks\LookTag;
use App\Models\Lookups\Gender;
use App\Models\Lookups\Color;
use App\Models\Lookups\Tag;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\ProductSize as ProductSizeEnum;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\AppSections;
use App\Merchant;
use App\Models\Lookups\Lookup;
use App\Models\Products\ProductColorGroup;
use App\Models\Products\ProductGroup;
use App\Models\Products\ProductPrice;
use App\Models\Products\ProductSize;
use App\Product;
use App\ProductTag;
use App\UploadImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Http\Mapper\ProductMapper;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProductController extends Controller
{
    protected $filter_ids = ['stylist_id', 'merchant_id', 'brand_id', 'gender_id', 'primary_color_id', 'rating_id', 'approved_by'];
    protected $filters = ['stylists', 'merchants', 'brands', 'genders', 'colors', 'ratings', 'approvedBy'];

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

    public function getAllCategoryLevels()
    {
        $cat_arr = array();

        $categories = Category::with(['subcategory.subcategory'])->whereIn('id', [1,8,9,38])->orderBy('name', 'ASC')->get();
        foreach ($categories as $category) {
            if (!isset($cat_arr[$category->id])){
                $cat_arr[$category->id] = array('id' => $category->id, 'name' => $category->name, 'subcategory' => array());
            }
            foreach ($category->subcategory as $subcategory){
                if (!isset($cat_arr[$category->id]['subcategory'][$subcategory->id])){
                    $cat_arr[$category->id]['subcategory'][$subcategory->id] = array('id' => $subcategory->id, 'name' => $subcategory->name, 'subcategory' => array());
                }
                foreach ($subcategory->subcategory as $subsubcategory){
                    if (!isset($cat_arr[$category->id]['subcategory'][$subcategory->id]['subcategory'][$subsubcategory->id])){
                        $cat_arr[$category->id]['subcategory'][$subcategory->id]['subcategory'][$subsubcategory->id] =
                            array('id' => $subsubcategory->id, 'name' => $subsubcategory->name);
                    }
                }
            }
        }
        return $cat_arr;
    }

    public function getList(Request $request)
    {
        $this->base_table = 'products';
        $this->initWhereConditions($request);
        $this->initFilters();

        $lookup = new Lookup();
        $category_obj = new Category();

        $categories = array();
        foreach ($this->categories as $category){
            $categories[$category->id] = $category->name;
        }
//        $category = Category::with(['subcategory.subcategory'])->whereIn('id', [1,8,9,38])->orderBy('name', 'ASC')->get();

        $view_properties = array(
            'stylists' => $this->stylists,
            'merchants' => $this->merchants,
            'brands' => $this->brands,
            'categories' => $categories,
//            'par_categories' => $category,
            'genders' => $this->genders,
            'colors' => $this->colors,
            'ratings' => $this->ratings,
            'approvedBy' => $this->approvedBy,
//            'category_tree' => $category_obj->getCategoryTree(),
            'gender_list' => $lookup->type('gender')->get(),
            'color_list' => $lookup->type('color')->get(),
            'ratings_list' => $lookup->type('rating')->where('status_id', true)->get(),
            'tags_list' => $lookup->type('tags')->get(),
        );
        if (!env('IS_NICOBAR')) {
            $view_properties['category_tree'] = $category_obj->getCategoryTree();
        }

        foreach ($this->filter_ids as $filter) {
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }
        $otherInputs = array('tag_id', 'leaf_category_id', 'parent', 'category_id', 'min_price', 'max_discount', 'min_price', 'max_price');
        foreach ($otherInputs as $filter) {
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }
        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');
        $in_stock = $request->input('in_stock');
        $view_properties['in_stock'] = $in_stock;

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');

        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $min_discount = $request->input('min_discount');
        $max_discount = $request->input('max_discount');

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
        if (!empty($request->input('leaf_category_id'))) {
            $category_ids = $this->subCategoryIds($request->input('leaf_category_id'));
        } elseif (!empty($request->input('category_id'))) {
            $category_ids = $this->subCategoryIds($request->input('category_id'));
        } elseif (!empty($request->input('parent'))){
            $category_ids = $this->subCategoryIds($request->input('parent'));
        } else {
            $category_ids = [];
        }
        $mapperObj = new Mapper();
        $product_prices = $mapperObj->getPriceClosure($min_price, $max_price, $min_discount, $max_discount);
        $in_stock_closure = $this->getInStockClosure($in_stock);
        $products =
            Product::with(['category', 'product_prices' => $product_prices, 'in_stock', 'primary_color', 'tags.tag'])
                ->where($this->where_conditions)
                ->where('account_id', $request->user()->account_id)
                ->whereRaw($this->where_raw)
                ->whereHas('product_prices', $product_prices);
        if ($in_stock != null && $in_stock !== '') {
            $products = $products->whereHas('in_stock', $in_stock_closure);
        }
        if (!empty($category_ids)) {
            $products = $products->whereIn('category_id', $category_ids);
        }
        $products = $products->orderBy('created_at', 'desc')
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
        $view_properties['entity'] = 'product';
        return view('product.list', $view_properties);
    }

    public function initWhereConditions(Request $request)
    {
        parent::initWhereConditions($request);
    }

    public function getInStockClosure($in_stock)
    {
        return function ($query) use($in_stock) {
            if ($in_stock == InStock::Yes) {
                $query->where('stock_quantity', '>=', 1);
            } elseif ($in_stock == InStock::No) {
                $query->select('product_id')
                    ->groupBy('product_id')
                    ->havingRaw('SUM(stock_quantity) = 0');
            }
        };
    }

    public function getCreateProduct(Request $request){
        $category_obj = new Category();
        $lookup = new Lookup();
        $view_properties = array(
            'category_tree' => $category_obj->getCategoryTree(),
            'genders' => $lookup->type('gender')->get(),
            'colors' => $lookup->type('color')->get(),
            'merchants' => Merchant::get(),
            'image_types' => $lookup->type('image_type')->where('entity_type_id', EntityType::PRODUCT)->get(),
            'category_id' => $request->old('category_id'),
            'gender_id' => $request->old('gender_id'),
            'primary_color_id' => $request->old('primary_color_id'),
            'brand_id' => $request->old('brand_id'),
            'merchant_id' => $request->old('merchant_id'),
            'entity_type_id' => EntityType::PRODUCT,
            'image0' => $request->old('image0'),
            'imageId' => $request->old('imageId'),

        );
        return view('product.create', $view_properties);
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
            'merchant' => 'required_without:merchant_id|min:4',
            'brand' => 'required_without:brand_id|min:2',
            'category' => 'required_without:category_id|min:2',
            'gender' => 'required_without:gender_id|min:4',
            'color1' => 'required_without:primary_color_id|min:3',
            'color2' => 'min:3',
        ]);
        $not_from_ext = $request->has('not_from_ext') && $request->input('not_from_ext') == true ? true : false;

        $error_messages = "";
        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $v) {
                $error_messages .= $v[0] . "<br/>";
            }
            if ($not_from_ext) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput($request->all());
            } else {
                echo json_encode([false, $error_messages]);
                return;
            }
        }

        if ($request->has('merchant_id')){
            if ($request->input('merchant_id') != '') {
                $merchant_id = $request->input('merchant_id');
            } else {
                $error_messages .= 'Empty merchant id ';
            }
        } else {
            $merchant = Merchant::where('name', $request->input('merchant'))->first();
            $merchant_id = $merchant ? $merchant->id : '';
        }

        if ($request->has('brand_id')){
            if ($request->input('brand_id') != '') {
                $brand_id = $request->input('brand_id');
            } else {
                $error_messages .= 'Empty brand id ';
            }
        } else {
            $brand_name = preg_replace('/[^a-zA-Z0-9_&-@\' \']/', null, $request->input('brand'));
            $brand = Brand::firstOrCreate(['name' => trim($brand_name)]);
            $brand_id = $brand ? $brand->id : '';
        }

        if ($request->has('category_id')){
            if ($request->input('category_id') != '') {
                $category_id = $request->input('category_id');
            } else {
                $error_messages .= 'Empty category id ';
            }
        } else {
            $category = Category::where(['name' => $request->input('category')])->first();
            $category_id = $category ? $category->id : '';
        }

        if ($request->has('primary_color_id')){
            if ($request->input('primary_color_id') != '') {
                $primary_color_id = $request->input('primary_color_id');
            } else {
                $error_messages .= 'Empty Color id ';
            }
        } else {
            $primary_color = Color::where(['name' => $request->input('color1')])->first();
            $primary_color_id = $primary_color ? $primary_color->id : '';
        }

        if ($request->has('gender_id')){
            if ($request->input('gender_id') != '') {
                $gender_id = $request->input('gender_id');
            } else {
                $error_messages .= 'Empty gender id ';
            }
        } else {
            $gender = Gender::where(['name' => $request->input('gender')])->first();
            $gender_id = $gender ? $gender->id : '';
        }
        $secondary_color = Color::where(['name' => $request->input('color2')])->first();

        $required_values = array('merchant_id', 'category_id', 'gender_id', 'primary_color_id');
        foreach ($required_values as $v) {
            if (!isset($$v)) {
                $error_messages .= strtoupper(substr($v, 0, 1)) . substr($v, 1) . " not found. Please contact admin.<br/>";
            }
        }
        if ($error_messages != "") {
            if ($not_from_ext) {
                return Redirect::back()
                    ->withErrors($error_messages)
                    ->withInput($request->all());
            } else {
                echo json_encode([false, $error_messages]);
                return;
            }
        }

        if ($merchant_id && $request->input('name') && $category_id && $gender_id && $primary_color_id) {
            $sku_id = !empty($request->input('sku_id')) ? $request->input('sku_id') : 'isy_' . (intval(time()) + rand(0, 10000));
            $product = Product::firstOrCreate(['sku_id' => $sku_id, 'merchant_id' => $merchant_id]);
            DB::beginTransaction();
            $product_group_id = $this->getProductGroupId();

            $product->merchant_id = $merchant_id;
            $product->sku_id = $sku_id;
            $product->group_id = $product_group_id;
            $product->name = htmlentities($request->input('name'));
            $product->description = htmlentities($request->input('desc'));
            $product->product_link = $not_from_ext ? 'http://'.$request->getHost().'/product/view/'.$product->id : $request->input('url');
            $product->image_name = $request->input('image0');
            $product->brand_id = $brand_id;
            $product->category_id = $category_id;
            $product->gender_id = $gender_id;
            $product->primary_color_id = $primary_color_id;
            $product->secondary_color_id = $secondary_color ? $secondary_color->id : "";
            $product->stylist_id = $request->user()->id;
            $product->approved_by = $product->stylist_id;
            $product->account_id = $request->user()->account_id;

            try {
                ProductColorGroup::insert(['group_id' => $product_group_id, 'sku_id' => $sku_id]);
                if ($product->save()) {
                    ProductPrice::insert(['product_id' => $product->id, 'price_type_id' => PriceType::RETAIL, 'currency_id' => Currency::INR, 'value' => $request->input('price')]);
                    $product_url = url('product/view/' . $product->id);
                    if ($not_from_ext) {
                        $image_id = $request->input('imageId');
                        UploadImages::where('id', $image_id)->update(['uploaded_by_entity_id' => $product->id]);
                    }
                    DB::commit();
                    if ($not_from_ext) {
                        return Redirect::to($product_url);
                    } else {
                        echo json_encode([true, $product_url]);
                    }
                } else {
                    if ($not_from_ext) {
                        return Redirect::back()
                            ->withErrors($error_messages)
                            ->withInput($request->all());
                    } else {
                        echo json_encode([false, "Product save failed. Please contact admin."]);
                    }
                }
                ProductSize::insert(['size_id' => ProductSizeEnum::NO_ANY, 'sku_id' => $sku_id, 'product_id' => $product->id, 'stock_quantity' => 1]);
            } catch (\Exception $e) {
                DB::rollback();

                if ($not_from_ext) {
                    return Redirect::back()
                        ->withErrors($error_messages)
                        ->withInput($request->all());
                } else {
                    echo json_encode([false, "Exception : " . $e->getMessage()]);
                }
            }
        } else {

            if ($not_from_ext) {
                return Redirect::back()
                    ->withErrors($error_messages)
                    ->withInput($request->all());
            } else {
                echo json_encode([false, "Product required info missing. Please contact admin."]);
            }
        }
    }

    public function getProductGroupId()
    {
        $productGroupObj = new ProductGroup();
        $productGroupObj->save();
        return $productGroupObj->id;
    }

    public function getView()
    {
        $product_prices = function ($query) {
            $query->with(['type', 'currency']);
            $query->where(['price_type_id' => PriceType::RETAIL, 'currency_id' => Currency::INR]);
        };

        $product = Product::where('id', $this->resource_id)->with(['product_prices' => $product_prices, 'tags.tag'])->first();
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
    public function getEdit(Request $request)
    {
        $mapperObj = new Mapper();
        $product_prices = $mapperObj->getPriceClosure();
        $product = Product::with(['product_prices' => $product_prices])->where(['id', $this->resource_id, 'account_id' => $request->user()->account_id])->first();
        if (!$product) {
            return redirect('product/list')
                ->withErrors(['error' => 'No permission to edit'])
                ->withInput();
        }

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
            $view_properties['image_types'] = $lookup->type('image_type')->where('entity_type_id', EntityType::PRODUCT)->get();
            $view_properties['entity_type_id'] = EntityType::PRODUCT;
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
                ->withInput($request->all());
        }
        $product = Product::where(['id', $this->resource_id, 'account_id' => $request->user()->account_id])->first();
        if (!$product) {
            return redirect('product/list')
                ->withErrors(['error' => 'No permission to edit'])
                ->withInput();
        }
        try {
            DB::begintransaction();
            $product->name = isset($request->name) && $request->name != '' ? $request->name : '';
            $product->description = isset($request->description) && $request->description != '' ? $request->description : '';
            $product->gender_id = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
            $product->category_id = isset($request->category_id) && $request->category_id != '' ? $request->category_id : '';
            $product->primary_color_id = isset($request->primary_color_id) && $request->primary_color_id != '' ? $request->primary_color_id : '';
            $product->image_name = isset($request->image0) && $request->image0 != '' ? $request->image0 : $product->image_name;
            $product->save();
            ProductPrice::where(['product_id' => $product->id, 'price_type_id' => PriceType::RETAIL, 'currency_id' => Currency::INR])->delete();
            ProductPrice::insert(['product_id' => $product->id, 'price_type_id' => PriceType::RETAIL, 'currency_id' => Currency::INR, 'value' => $request->input('price')]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect('product/edit/' . $this->resource_id)
                ->withErrors(['error' => 'Exception : '.$e->getMessage()])
                ->withInput($request->all());
        }

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
        $entity_id = $request->input('entity_id');
        $entity_type_id = $request->input('entity_type_id');
        $tagMessage = 'Already tagged';
        if ($entity_type_id == EntityType::PRODUCT)
            $entityTagExists = ProductTag::where(['product_id' => $entity_id, 'tag_id' => $tagObj->id])->exists();
        elseif ($entity_type_id == EntityType::LOOK)
            $entityTagExists = LookTag::where(['look_id' => $entity_id, 'tag_id' => $tagObj->id])->exists();
        else {
            $entityTagExists = true;
            $tagMessage = 'Undefined entity type';
        }

        if ($entityTagExists) {
            return array('status' => false, 'message' => $tagMessage);
        }

        try {
            if ($entity_type_id == EntityType::PRODUCT)
                ProductTag::insert(['product_id' => $entity_id, 'tag_id' => $tagObj->id]);
            elseif ($entity_type_id == EntityType::LOOK)
                LookTag::insert(['look_id' => $entity_id, 'tag_id' => $tagObj->id]);
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

        $entity_id = $request->input('entity_id');
        $entity_type_id = $request->input('entity_type_id');
        $tagMessage = 'Tag does not exist for this product';
        if ($entity_type_id == EntityType::PRODUCT)
            $entityTagExists = ProductTag::where(['product_id' => $entity_id, 'tag_id' => $tagObj->id])->first();
        elseif ($entity_type_id == EntityType::LOOK)
            $entityTagExists = LookTag::where(['look_id' => $entity_id, 'tag_id' => $tagObj->id])->first();
        else {
            $entityTagExists = null;
            $tagMessage = 'Undefined entity type';
        }

        if (!$entityTagExists) {
            return array('status' => false, 'message' => $tagMessage);
        }

        try {
            if ($entity_type_id == EntityType::PRODUCT)
                ProductTag::where(['product_id' => $entity_id, 'tag_id' => $entityTagExists->tag_id])->delete();
            elseif ($entity_type_id == EntityType::LOOK)
                LookTag::where(['look_id' => $entity_id, 'tag_id' => $entityTagExists->tag_id])->delete();
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
            'entity_id' => 'required|numeric',
            'entity_type_id' => 'required|numeric',
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

    public function subCategoryIds ($category_id) {
        $categories = Category::where(['parent_category_id' => $category_id])->get();
        $category_ids = array(intval($category_id));
        foreach ($categories as $category) {
            $category_ids[] = $category->id;
        }
        return $category_ids;
    }

    public function postSyncProducts() {
        if (file_exists(env('JSONLINE_FILE_BASE_PATH').'lock_file.txt')){
            return ['status' => false, 'message' => 'Process is already running by other user'];
        } else {
            if(!$lockFile = fopen(env('JSONLINE_FILE_BASE_PATH').'lock_file.txt', 'w')){
                return ['status' => false, 'message' => 'Error creating lock file'];
            }

            $scraperController = new ScraperController();
            if ($scraperController->getFetchNicobar()) {
                if (!$scraperController->getImport()) {
                    return ['status' => false, 'message' => 'Sync import error'];
                }
            } else {
                return ['status' => false, 'message' => 'Sync fetch error'];
            }
            fclose($lockFile);
            if (!unlink(env('JSONLINE_FILE_BASE_PATH').'lock_file.txt')) {
                return ['status' => true, 'message' => 'Error deleting lock file'];
            }
            return ['status' => true, 'message' => 'Prodcuts Sync successful'];
        }
    }
}
