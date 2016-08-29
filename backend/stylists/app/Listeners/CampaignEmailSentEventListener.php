<?php

namespace App\Listeners;

use App\CampaignMailerRepository;
use App\Events\CampaignEmailSentEvent;
use Illuminate\Queue\InteractsWithQueue;
use DB;
use Carbon\Carbon;
class CampaignEmailSentEventListener
{

    public function handle(CampaignEmailSentEvent $event)
    {
        if(empty($event->emailId) || empty($event->campaignId))
            return;

        DB::table(CampaignMailerRepository::TABLE_NAME)
            ->where('email', $event->emailId)
            ->where('campaign_id', $event->campaignId)
            ->take(1)
            ->update(['is_sent' => 1, 'sent_at' =>Carbon::now()]);
    }
}
