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
use Validator;
use Carbon\Carbon;
use App\Campaign\Utils\CampaignUtils;

class CampaignController extends Controller
{
    const USER_INPUT_DATE_FORMAT = "M-j-Y h:i a";
    const DB_DATE_FORMAT = "Y-m-d H:i:s";

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

    public function postPublish(Request $request){
        $campaign = Campaign::find($this->resource_id);
        if(!$campaign)
            return view('404', array('title' => 'Campaign not found.'));
        else if(!$campaign->isPublishable())
            return view('404', array('title' => 'Campaign is not in publishable state.'));

        $validator = $this->publishValidator($request->all());

        if($validator->fails())
            return redirect('campaign/view/'.$this->resource_id)
                    ->withErrors($validator)
                    ->withInput();

        $campaign->status = Campaign::PUBLISHED_STATE;
        $campaign->published_on =  Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,
                                            $request->publish_dt)->format(self::DB_DATE_FORMAT);
        $campaign->prepared_message = CampaignUtils::prepareMessage($campaign->message, $campaign->id);
        $campaign->save();
        return redirect('campaign/view/'.$this->resource_id);
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

    public function publishValidator(array $data){
        $validator = Validator::make($data, [
            'publish_dt' => 'required|date_format:'.self::USER_INPUT_DATE_FORMAT

        ], [
            'publish_dt' => 'Publish datetime is invalid.'
        ]);

        $validator->after(function($validator) use($data) {
            $publishDate = strtotime($data['publish_dt']);
            if($publishDate <= strtotime(date(self::USER_INPUT_DATE_FORMAT)))
                $validator->getMessageBag()->add('publish_dt', 'Publish datetime must be future datetime.');
        });
        return $validator;
    }
} 