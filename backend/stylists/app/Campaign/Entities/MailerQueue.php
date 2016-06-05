<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 05/06/16
 * Time: 6:14 PM
 */

namespace App\Campaign\Entities;


class MailerQueue {

    private $message;
    private $campaignId;
    private $delay;
    private $isRaiseSendEvent;

    function __construct($campaignId, $delay, $isRaiseSendEvent, Message $message)
    {
        $this->campaignId = $campaignId;
        $this->delay = $delay;
        $this->isRaiseSendEvent = $isRaiseSendEvent;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * @return mixed
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @return mixed
     */
    public function getIsRaiseSendEvent()
    {
        return $this->isRaiseSendEvent;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }



} 