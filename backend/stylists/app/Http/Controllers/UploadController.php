<?php

namespace App\Http\Controllers;

use App\Models\Lookups\Lookup;
use App\Models\Lookups\StylistStatus;
use App\Stylist;
use App\Http\Mapper\UploadMapper;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Validator;

class UploadController extends Controller
{
    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if ($id) {
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function postImage(Request $request)
    {
        if (empty($this->resource_id) || $this->resource_id == '') {
            return Redirect::back()->withError('Id Not Found');
        }

        $uploadMapperObj = new UploadMapper();

        $validator = $uploadMapperObj->inputValidator($request);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $entity_type_id = $request->input('entity_type_id');
        $entity_type_name = $uploadMapperObj->getEntityTypeName($entity_type_id);

        if (!($entity_obj = $uploadMapperObj->entityExists($this->resource_id, $entity_type_id))) {
            return Redirect::back()->withErrors($entity_type_name . 'not found');
        }

        $response = $uploadMapperObj->saveImage($request, $entity_obj, $entity_type_name);
        if ($response['status'] == false) {
            return Redirect::back()->withErrors($response['message']);
        }

        return  Redirect::back()->withSuccess('Image upload successful');
    }
}
