<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Lookups\Gender;
use App\MerchantProduct;
use App\MerchantProductRejected;
use App\Product;
use App\Models\Enums\Stylist;
use App\Error;
use App\Success;
use App\Category;
use App\Models\Lookups\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Mapper\ProductMapper;
use Validator;

class ProductController extends Controller
{
    protected $filter_ids = ['merchant_id', 'brand_id', 'category_id', 'gender_id', 'primary_color_id', 'rating_id'];
    protected $filters = ['merchants', 'brands', 'categories', 'genders', 'colors', 'ratings'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        return $this->$method($request);
    }

    public function initWhereConditions(Request $request)
    {
        $this->where_conditions['is_moderated'] = false;
        parent::initWhereConditions($request);
    }

    public function postList(Request $request)
    {
        $this->initWhereConditions($request);

        $selected_products = !empty($request->input('product_ids')) ? explode(',', $request->input('product_ids')) : '';
        $redirect = Redirect::to($request->fullUrl());

        DB::beginTransaction();
        try {
            if (!empty($selected_products)) {
                if ($request->input('approve_all')) {
                    $response = $this->approveAllProducts($selected_products);
                } elseif ($request->input('reject_all')) {
                    $response = $this->rejectAllProducts($selected_products);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $redirect->with('errorMsg', 'Some exception(s) found, please contact admin ' . $e->getMessage());
        }

        if (!empty($response['success'])) {
            DB::commit();
            return $redirect->with('successMsg', $response['success']['message']);
        }
        elseif (!empty($response['error'])) {
            DB::rollback();
            return $redirect->with('errorMsg', $response['error']['message']);
        }
        else {
            return $redirect->with('errorMsg', 'Some error(s) found, please contact admin');
        }
    }

    public function getList(Request $request)
    {
        $this->base_table = 'merchant_products';
        $this->initWhereConditions($request);
        if ($request->input('in_stock') != "") {
            $this->setInStockCondition($request->input('in_stock'));
        }
        $this->initFilters();

        $lookup = new Lookup();
        $category_obj = new Category();

        $view_properties = array(
            'merchants' => $this->merchants,
            'brands' => $this->brands,
            'categories' => $this->categories,
            'colors' => $this->colors,
            'genders' => $this->genders,
            'ratings' => $this->ratings,
            'category_tree' => $category_obj->getCategoryTree(),
            'gender_list' => $lookup->type('gender')->get(),
            'color_list' => $lookup->type('color')->get(),
            'ratings_list' => $lookup->type('rating')->where('status_id', true)->get(),
        );

        foreach ($this->filter_ids as $filter) {
            $view_properties[$filter] = $request->input($filter) ? $request->input($filter) : "";
        }

        $view_properties['stylist_id'] = Auth::user()->id;
        $view_properties['url'] = 'merchant/product/';

        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');
        $view_properties['in_stock'] = $request->input('in_stock');

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $genders_list = Gender::all()->keyBy('id');
        $genders_list[0] = new Gender();

        $merchant_products =
            MerchantProduct::with('brand', 'category', 'color')
                ->where($this->where_conditions)
                ->whereRaw($this->where_raw)
                ->orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page * 2)
                ->appends($paginate_qs);

        $view_properties['merchant_products'] = $merchant_products;
        $view_properties['genders_list'] = $genders_list;

        return view('merchant.product.list', $view_properties);
    }

    public function approveAllProducts($selected_products)
    {
        $merchant_products = MerchantProduct::whereIn('id', $selected_products)->get();
        $query = '';
        foreach ($merchant_products as $merchant_product) {
            $query = $query . " OR (sku_id = '{$merchant_product->m_product_sku}' AND merchant_id = '{$merchant_product->merchant_id}')";
        }
        $product = Product::whereRaw(substr($query, 4))
            ->select('id', 'merchant_id', 'sku_id', 'price', 'discounted_price', 'in_stock')
            ->get();

        $product_sku_ids = array();
        foreach ($product as $item) {
            $product_sku_ids[$item->sku_id] = $item;
        }

        $errorObj = new Error();
        $error_message = '';
        $items_to_be_inserted = array();
        $merchant_product_ids = array();
        foreach ($merchant_products as $m_product) {

            if (in_array($m_product->m_product_sku, array_keys($product_sku_ids))) {
                $productObj = $product_sku_ids[$m_product->m_product_sku];

                $productObj->price = $m_product->m_product_price;
                $productObj->in_stock = $m_product->m_in_stock;
                $productObj->discounted_price = $m_product->m_discounted_price;

                if (!$productObj->save()) {
                    $error_message = $error_message . "Error updating product {$m_product->m_product_name}" . PHP_EOL;
                } else {
                    array_push($merchant_product_ids, $m_product->id);
                }
            } else {
                array_push($merchant_product_ids, $m_product->id);
                $items_to_be_inserted[$m_product->m_product_sku] = $this->createProductArray($m_product);
            }
        }

        if (!empty($items_to_be_inserted)) {
            if (!Product::insert(array_values($items_to_be_inserted))) {
                return $errorObj->error(404, false, $error_message);
            }
        }

        if (!MerchantProduct::whereIn('id', $merchant_product_ids)->delete()) {
            $error_message = $error_message . "Error removing product(s) from merchant_products";
        }
        if (!empty($error_message)) {
            return $errorObj->error(402, false, $error_message);
        }
        $successObj = new Success();
        return $successObj->success(400, false);

    }

    public function createProductArray($m_product)
    {
        $array = array(
            'merchant_id' => $m_product->merchant_id,
            'sku_id' => $m_product->m_product_sku,
            'name' => $m_product->m_product_name,
            'description' => $m_product->m_product_description,
            'price' => $m_product->m_product_price,
            'discounted_price' => $m_product->m_discounted_price,
            'product_link' => $m_product->m_product_url,
            'upload_image' => $m_product->m_product_image_large_url,
            'image_name' => $m_product->m_product_image_large_url,
            'style_tip' => $m_product->m_style_tip,
            'care_information' => $m_product->m_care_information,
            'brand_id' => $m_product->brand_id,
            'category_id' => $m_product->category_id,
            'gender_id' => $m_product->gender_id,
            'primary_color_id' => $m_product->primary_color_id,
            'in_stock' => $m_product->m_in_stock,
            'rating_id' => $m_product->rating_id,
            'stylist_id' => Stylist::Scraper,
            'approved_by' => Auth::user()->id,
        );
        return $array;
    }

    public function rejectAllProducts($selected_products)
    {
        $errorObj = new Error();
        $merchant_products = MerchantProduct::whereIn('id', $selected_products)->get();
        if (empty($merchant_products)) {
            return $errorObj->error(401, false);
        }
        $reject_product = array();
        $count = 0;

        foreach ($merchant_products as $m_product) {
            $reject_product[$count++] = array(
                'merchant_id' => $m_product->merchant_id,
                'm_product_sku' => $m_product->m_product_sku,
                'stylist_id' => Auth::user()->id,
                'created_at' => date("Y-m-d H:i:s"),
            );
        }

        if (!MerchantProductRejected::insert($reject_product)) {
            return $errorObj->error(406, false);
        }

        if (!MerchantProduct::whereIn('id', $selected_products)->delete()) {
            return $errorObj->error(407, false);
        }

        $successObj = new Success();
        return $successObj->success(400, false);
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
        $this->base_table = 'merchant_products';

        $product_ids = explode(',', $request->input('product_id'));

        $update_clauses = $productMapperObj->getUpdateClauses($request);
        if (count($update_clauses) == 0) {
            return Redirect::back()
                ->withErrors(['Please specify at least 1 field to update'])
                ->withInput();
        }

        if ($productMapperObj->updateData($this->base_table, $this->where_conditions, $this->where_raw, $product_ids, $update_clauses)) {
            return Redirect::back()
                ->withErrors(['Records updated'])
                ->withInput();
        } else {
            return Redirect::back()
                ->withErrors(['Error updating data'])
                ->withInput();
        }
    }

}
