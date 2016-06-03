<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Validator;

class UpdateController extends Controller
{
    protected $bulk_update_fields = ['category_id', 'gender_id', 'primary_color_id'];

    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if ($id) {
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getSelectedProducts(Request $request)
    {
        $bulk_update_fields = $this->bulk_update_fields;

        if (!Auth::user()->hasRole('admin')) {
            return Redirect::back()
                ->withErrors(['You do not have permission to do bulk update'])
                ->withInput();
        }
        if (is_null($request->product_id)) {
            return Redirect::back()
                ->withErrors(['Please select at least one item to be updated'])
                ->withInput();
        }

        $this->base_table = !empty($request->input('table')) ? $request->input('table') : '';
        if (empty($this->base_table)) {
            return Redirect::back()
                ->withErrors(['Product type not identified'])
                ->withInput();
        }

        $valdation_clauses = [
            'merchant_id' => 'integer',
            'stylist_id' => 'integer',
            'brand_id' => 'integer',
            'gender_id' => 'integer',
            'primary_color_id' => 'integer',
            'category_id' => 'integer',
            'search' => 'regex:/[\w]+/',
        ];

        $update_clauses = [];

        foreach ($bulk_update_fields as $filter) {
            if ($request->input($filter) != "") {
                $valdation_clauses[$filter] = 'required|integer|min:1';

                unset($this->where_conditions[$this->base_table . '.' . $filter]);

                $update_clauses[$filter] = $request->input($filter);
            }

            if ($request->input('old_' . $filter) != "") {
                $this->where_conditions[$this->base_table . '.' . $filter] = $request->input('old_' . $filter);
            }
        }

        if (count($update_clauses) == 0) {
            return Redirect::back()
                ->withErrors(['Please specify at least 1 field to update'])
                ->withInput();
        }

        $validator = Validator::make($request->all(), $valdation_clauses);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $k => $v) {
                echo $v[0] . "<br/>";
            }

            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table($this->base_table)
            ->where($this->where_conditions)
            ->whereRaw($this->where_raw)
            ->whereIn('id', $request->input('product_id'))
            ->update($update_clauses);

        return Redirect::back()
            ->withErrors(['Records updated'])
            ->withInput();
    }

    public function postBulkUpdate(Request $request)
    {
        $bulk_update_fields = $this->bulk_update_fields;

        if (!Auth::user()->hasRole('admin')) {
            return Redirect::back()
                ->withErrors(['You do not have permission to do bulk update'])
                ->withInput();
        }

        $this->base_table = !empty($request->input('table')) ? $request->input('table') : '';;
        if (empty($this->base_table)) {
            return Redirect::back()
                ->withErrors(['Product type not identified'])
                ->withInput();
        }

        $product_ids = !empty($request->input('product_id')) ? explode(',', $request->input('product_id')) : '';;
        if (empty($product_ids)) {
            return Redirect::back()
                ->withErrors(['Product list empty'])
                ->withInput();
        }

        $valdation_clauses = [
            'merchant_id' => 'integer',
            'stylist_id' => 'integer',
            'brand_id' => 'integer',
            'gender_id' => 'integer',
            'primary_color_id' => 'integer',
            'category_id' => 'integer',
            'search' => 'regex:/[\w]+/',
        ];

        $update_clauses = [];

        foreach ($bulk_update_fields as $filter) {
            if ($request->input($filter) != "") {
                $valdation_clauses[$filter] = 'required|integer|min:1';

                unset($this->where_conditions[$this->base_table.'.' . $filter]);

                $update_clauses[$filter] = $request->input($filter);
            }

            if ($request->input('old_' . $filter) != "") {
                $this->where_conditions[$this->base_table.'.' . $filter] = $request->input('old_' . $filter);
            }
        }

        if (count($update_clauses) == 0) {
            return Redirect::back()
                ->withErrors(['Please specify at least 1 field to bulk update'])
                ->withInput();
        }

        $validator = Validator::make($request->all(), $valdation_clauses);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $k => $v) {
                echo $v[0] . "<br/>";
            }

            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table($this->base_table)
            ->where($this->where_conditions)
            ->whereRaw($this->where_raw)
            ->whereIn('id', $product_ids)
            ->update($update_clauses);

        return Redirect::back()
            ->withErrors(['Records updated'])
            ->withInput();
    }
}
