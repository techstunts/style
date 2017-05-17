<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\AppSections;
use App\StyleRequests;
use Illuminate\Http\Request;

class StyleRequestMapper extends Controller
{

    public function popupProperties(Request $request)
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
        $view_properties['request_tab'] = true;

        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');
        $view_properties['in_stock'] = $request->input('in_stock');

        $view_properties['app_sections'] = AppSections::all();
        $view_properties['recommendation_type_id'] = RecommendationType::STYLE_REQUEST;
        $view_properties['show_price_filters'] = 'YES';

        return $view_properties;
    }

    public function requestById($id, $status = null)
    {
        $whereCondition = array('id' => $id);
        $request = StyleRequests::where($whereCondition)->first();
        return $request;
    }

    public function updateStatus($request, $status_id)
    {
        try{
            $request->status_id = $status_id;
            $request->save();
            $status = true;
            $message = 'Updated successfully';
        } catch (\Exception $e) {
            $status = false;
            $message = 'Exception occured : '. $e->getMessage();
        }
        return array(
            'status' => $status,
            'message' => $message,
        );
    }
}