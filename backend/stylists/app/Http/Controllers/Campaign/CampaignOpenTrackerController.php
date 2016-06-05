<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 05/06/16
 * Time: 1:02 AM
 */

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Campaign\Utils\CampaignUtils;
use Illuminate\Http\Request;
use App\CampaignMailerTracker;
use App\CampaignMailerRepository;
use Carbon\Carbon;
use DB;
use Redirect;

class CampaignOpenTrackerController extends Controller
{

    public function index(Request $request, $trackerId){
        $trackerString = base64_decode($trackerId);
        $trackerData = CampaignUtils::getOpenTrackerData($trackerString);

        if(!empty($trackerData['email']) && !empty($trackerData['campaignId'])){
            $this->saveToTracker($trackerData['campaignId'], $trackerData['email']);
            $this->updateCampaignMailerRepository($trackerData['campaignId'], $trackerData['email']);
        }

        return response(readfile(__DIR__."/../../Campaign/spacer.gif"))
            ->header('Content-Type', 'image/gif');
    }

    public function saveToTracker($campaignId, $emailId){
        $tracker = new CampaignMailerTracker(
            [   'campaign_id' => $campaignId,
                'email' => $emailId,
                'url' => 'OPEN_MAIL_IMAGE_TRACKER',
                'event' => CampaignMailerTracker::OPEN_EVENT
            ]
        );
        $tracker->save();

    }

    public function updateCampaignMailerRepository($campaignId, $emailId){
        DB::table(CampaignMailerRepository::TABLE_NAME)
            ->where('email', $emailId)
            ->where('campaign_id', $campaignId)
            ->take(1)
            ->update(['is_opened' => 1, 'opened_at' =>Carbon::now()]);
    }



} 