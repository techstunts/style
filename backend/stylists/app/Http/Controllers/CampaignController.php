<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 31/05/16
 * Time: 12:58 PM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Campaign;
use Illuminate\Support\Facades\Input;
use Validator;

class CampaignController extends Controller
{

    public function index(Request $request, $action, $id = null, $actionId = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        if($actionId){
            $this->action_resource_id = $actionId;
        }

        return $this->$method($request);
    }

    public function getList(Request $request){
        $this->base_table = 'campaign';
        $paginateQuery = $request->query();
        unset($paginateQuery['page']);
        $campaigns =
            Campaign::orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginateQuery);
        $viewProperties['campaigns'] = $campaigns;
        return view('campaign.list', $viewProperties);
    }

    public function getCreate(Request $request)
    {
        return view('campaign.create');
    }

    public function getEdit(Request $request){
        $campaign = Campaign::find($this->resource_id);

        if($campaign && $campaign->isEditable ())
            return view('campaign.edit', ['campaign'=>$campaign]);
        else if($campaign && !$campaign->isEditable ())
            return view('404', array('title' => 'Campaign is not in editable state.'));
        else
            return view('404', array('title' => 'Campaign not found.'));

    }

    public function postSave(Request $request)
    {

        if(!empty($this->resource_id)){
            $campaign = Campaign::find($this->resource_id);
            if(!$campaign)
                return view('404', array('title' => 'Campaign not found.'));
            else if($campaign && !$campaign->isEditable ())
                return view('404', array('title' => 'Campaign is not in editable state.'));
        } else {
            $campaign = new Campaign();
        }

        $validator = $this->validator($request->all());
        if($validator->fails())
            return redirect(!empty($campaign)?'campaign/edit/'.$this->resource_id: 'campaign/create/')
                                    ->withErrors($validator)
                                    ->withInput();

        $campaign->campaign_name = $request->campaign_name;
        $campaign->sender_email = $request->sender_email;
        $campaign->sender_name =  $request->sender_name;
        $campaign->mail_subject = $request->mail_subject;
        $campaign->message = $request->message;
        $campaign->status = Campaign::CREATED_STATE;

        $campaign->save();
        return redirect('/campaign/list');
    }

    public function getView(Request $request){
        $campaign = Campaign::find($this->resource_id);
        if($campaign)
            return view('campaign.view', ['campaign'=>$campaign]);
        else
            return view('404', array('title' => 'Campaign not found.'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'campaign_name' => 'required|min:5|max:100',
            'sender_email' => 'required|email|min:4|max:50',
            'sender_name' => 'required|min:4|max:50',
            'mail_subject' => 'required|min:10|max:512',
            'message' => 'required|min:50'
        ]);
    }



} 