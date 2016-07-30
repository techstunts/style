<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 04/06/16
 * Time: 11:38 PM
 */

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Campaign\Utils\CampaignUtils;
use App\CampaignMailerTracker;
use App\CampaignMailerRepository;
use Carbon\Carbon;
use DB;
use Redirect;

class CampaignRedirectController extends Controller{

    const UNSUBSCRIBE_LINK = "unsubscribe_link";

    public function index(Request $request){
        $url = base64_decode($request->u);
        $url = CampaignUtils::removeNonASCICharacter($url);

        $campaignId = $request->c; $email = $request->e;
        if(!empty($campaignId) && !empty($email)){
            $trackUrl = (CampaignUtils::isUnsubcribeLink($url))? self::UNSUBSCRIBE_LINK: $url;
            $this->saveToTracker($campaignId, $email, $trackUrl);
            $this->updateCampaignMailerRepository($campaignId, $email);
        }

        return Redirect::to($url, 301);
    }

    public function saveToTracker($campaignId, $emailId, $url){
        CampaignMailerTracker::create([ 'campaign_id' => $campaignId, 'email' => $emailId,
                                        'url' => $url, 'event' => CampaignMailerTracker::CLICK_EVENT]);
    }

    public function updateCampaignMailerRepository($campaignId, $emailId){
        DB::table(CampaignMailerRepository::TABLE_NAME)
            ->where('email', $emailId)->where('campaign_id', $campaignId)
            ->take(1)
            ->update(['is_clicked' => 1, 'clicked_at' =>Carbon::now()]);
    }
} 