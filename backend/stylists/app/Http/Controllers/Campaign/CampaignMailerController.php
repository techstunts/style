<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 05/06/16
 * Time: 3:51 PM
 */

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CampaignMailerRepository;

class CampaignMailerController extends Controller {

    protected $records_per_page = 50;

    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id) $this->resource_id = $id;
        return $this->$method($request);
    }

    public function getList(Request $request){
        $paginateQuery = $request->query(); unset($paginateQuery['page']);

        $mailers = CampaignMailerRepository::where('campaign_id', $this->resource_id)
                        ->simplePaginate($this->records_per_page)->appends($paginateQuery);

        return view('campaign.mailer.list', ['mailers' => $mailers]);
    }
} 