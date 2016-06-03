<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class CampaignEmailSentEvent extends Event
{
    use SerializesModels;

    public $campaignId;
    public $emailId;


    public function __construct($campaignId, $emailId)
    {
        $this->campaignId = $campaignId;
        $this->emailId = $emailId;
    }

    public function broadcastOn()
    {
        return [];
    }
}
