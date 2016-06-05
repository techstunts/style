<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 05/06/16
 * Time: 4:24 PM
 */

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CampaignMailerTracker;

class CampaignTrackerController extends  Controller{

    protected $records_per_page = 50;

    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id) $this->resource_id = $id;
        return $this->$method($request);
    }

    public function getList(Request $request){
        $paginateQuery = $request->query(); unset($paginateQuery['page']);
        $trackers = CampaignMailerTracker::where('campaign_id', $this->resource_id)
                    ->simplePaginate($this->records_per_page)->appends($paginateQuery);
        return view('campaign.tracker.list', ['trackers' => $trackers]);
    }

} 