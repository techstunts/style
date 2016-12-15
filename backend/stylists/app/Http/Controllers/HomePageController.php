<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\HomePage;
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

class HomePageController extends Controller
{
    protected $filter_ids = ['status_id'];
    protected $filters = ['statuses'];

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
        $this->base_table = 'home_page';
//        $this->initWhereConditions($request);
//        $this->initFilters();

        $view_properties = array(
//            'statuses' => $this->statuses,
        );

        foreach ($this->filter_ids as $filter) {
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);
        $products = function ($query) {
            $query->select(['id', 'name', 'product_link', 'image_name', 'descriptionsty', '', '', ]);
        };
        $data = HomePage::with(['products' => $products, ])
            ->where(['entity_type_id' => EntityType::PRODUCT])
            ->get();
//            Product::with('category', 'primary_color', 'secondary_color', 'product_tags.tag')
//                ->where($this->where_conditions)
//                ->whereRaw($this->where_raw)
//                ->orderBy('created_at', 'desc')
//                ->simplePaginate($this->records_per_page)
//                ->appends($paginate_qs);

//        $product_mapper = new ProductMapper();
//        foreach ($products as $product) {
//            $product->omg_product_link = $product_mapper->getDeepLink($product->merchant_id, $product->product_link);
//        }
//
        dd($data);
        $view_properties['products'] = $products;
        return view('homepage.list', $view_properties);
    }

}
