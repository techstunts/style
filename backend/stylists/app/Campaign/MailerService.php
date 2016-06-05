<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 04/06/16
 * Time: 11:28 PM
 */

namespace App\Campaign;

use App\Campaign\Entities\MailerQueue;
use App\Campaign\Utils\CampaignUtils;
use App\Campaign;
use App\Campaign\Entities\Receiver;
use App\Campaign\Entities\Sender;
use App\Campaign\Entities\Message;
use App\Campaign\Entities\Enums\Placeholder;
use Mail;

class MailerService
{
    const MAIL_QUEUE = 'campaign-mails';
    private $campaign;
    private $sender;


    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->sender = new Sender($campaign->sender_email, $campaign->sender_name);
    }

    public function sendMail(Receiver $receiver, $isRaiseSendEvent = false, $sendNow = false)
    {
        $placeholderValues = $this->preparedPlaceHolderValues($receiver);

        $content = $this->prepareMailContent($placeholderValues);
        $subject = $this->prepareMailSubject($placeholderValues);
        $message = $this->createMessage($receiver, $content, $subject);
        $delay = $sendNow ? 0 : $this->getMailSendDelay();
        $mailerQueue = $this->createMailerQueueObject($message, $delay, $isRaiseSendEvent);

        $this->pushToMailQueue($mailerQueue);
    }

    private function createMessage(Receiver $receiver, $content, $subject)
    {
        return new Message($receiver, $this->sender, $subject, $content);
    }

    private function createMailerQueueObject(Message $message, $delay, $isRaiseSendEvent)
    {
        return new MailerQueue($this->campaign->id, $delay, $isRaiseSendEvent, $message);
    }

    private  function pushToMailQueue(MailerQueue $mailerQueue)
    {
        $campaignId = $mailerQueue->getCampaignId();
        $senderName = $mailerQueue->getMessage()->getSender()->getName();
        $senderEmail = $mailerQueue->getMessage()->getSender()->getEmail();
        $receiverName =  $mailerQueue->getMessage()->getReceiver()->getName();
        $receiverEmail = $mailerQueue->getMessage()->getReceiver()->getEmail();
        $content = $mailerQueue->getMessage()->getContent();
        $subject = $mailerQueue->getMessage()->getSubject();
        $delay = $mailerQueue->getDelay();
        $isRaiseSendEvent = $mailerQueue->getIsRaiseSendEvent();

        Mail::laterOn(self::MAIL_QUEUE, $delay, 'campaign.campaign.email', ['email_content' => $content], function ($message)
        use($senderName, $senderEmail, $receiverName, $receiverEmail, $subject, $campaignId, $isRaiseSendEvent) {
            $message->from(trim($senderEmail), $senderName)
                ->to(trim($receiverEmail), $receiverName)
                ->subject($subject);

            if($isRaiseSendEvent) Event::fire(new CampaignEmailSentEvent($campaignId, $receiverEmail));
        });
    }

    private function prepareMailContent(array $placeHolderValues)
    {
        return CampaignUtils::replacePlaceholders($this->campaign->prepared_message, $placeHolderValues);
    }

    private function prepareMailSubject(array $placeHolderValues)
    {
        return CampaignUtils::replacePlaceholders($this->campaign->mail_subject, $placeHolderValues);
    }

    private function preparedPlaceHolderValues(Receiver $receiver)
    {
        return [
            Placeholder::USER_NAME => $receiver->getName(),
            Placeholder::EMAIL => $receiver->getEmail(),
            Placeholder::OPEN_TRACKER_VAR => CampaignUtils::getOpenTrackerVariableValue($receiver->getEmail(), $this->campaign->id),
            Placeholder::UNSUBSCRIBE_LINK => CampaignUtils::getUnsubscribeLinkWithTracker($receiver->getEmail(), $this->campaign->id)
        ];
    }

    private function getMailSendDelay()
    {
        $publishedOn = new Carbon($this->campaign->published_on);
        $diffInSecond = $publishedOn->diffInSeconds(Carbon::now());
        return ($diffInSecond > 0) ? $diffInSecond : 0;
    }
} 