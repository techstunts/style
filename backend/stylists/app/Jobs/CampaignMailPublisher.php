<?php

namespace App\Jobs;


use App\CampaignMailerRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mockery\CountValidator\Exception;
use App\Campaign;
use App\MailerMasterRepository;
use Mail;
use Carbon\Carbon;
use Event;
use App\Events\CampaignEmailSentEvent;
use App\Campaign\Entities\Enums\Placeholder;
use App\Campaign\Utils\CampaignUtils;

class CampaignMailPublisher extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    const PUBLISH_QUEUE = 'campaign-publisher';
    const MAIL_QUEUE = 'campaign-mails';
    const DEFAULT_CUSTOMER_NAME = "Customer";

    private $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function handle()
    {
        try {
            $this->campaign->queuing();
            $this->process();
            $this->campaign->queued();
        } catch(Exception $e){
            throw $e;
        }

    }

    private function process()
    {
        $totalUser = MailerMasterRepository::getUsersCount();
        echo "\n\$totalUser : $totalUser";
        if($totalUser > 0){
            $counter = 0;
            do {
                echo "\nLoop started with \$counter :$counter ";
                $users = MailerMasterRepository::getUsers($counter);
                foreach($users as $user){
                    echo "\nIn Loop \$counter : $counter ";
                    $counter++;
                    $userName =  $this->getUserName($user->name);
                    $placeHolderValues = $this->preparedPlaceHolderValues($user->email, $userName);
                    $this->addToCampaignMailerRepository($user->email, $userName);
                    $this->pushToMailQueue( $userName,
                                            $user->email,
                                            $this->prepareMailContent($placeHolderValues),
                                            $this->prepareMailSubject($placeHolderValues),
                                            $this->getMailSendDelay());
                }
            } while($counter < $totalUser);
        }
    }

    private function addToCampaignMailerRepository($email, $name)
    {
        $campaignMailerRepository = new CampaignMailerRepository(
                                            [
                                                "email" => $email,
                                                "name" => $name,
                                                "campaign_id" => $this->campaign->id
                                            ]
                                        );
        $campaignMailerRepository->save();

    }

    private function pushToMailQueue($receiverName, $receiverEmail, $content, $subject, $delay)
    {
        $campaignId = $this->campaign->id;
        $senderName = $this->campaign->sender_name;
        $senderEmail = $this->campaign->sender_email;

        Mail::laterOn(self::MAIL_QUEUE, $delay, 'campaign.email', ['email_content' => $content], function ($message)
               use($senderName, $senderEmail, $receiverName, $receiverEmail, $subject, $campaignId) {
                    $message->from(trim($senderEmail), $senderName)
                                ->to(trim($receiverEmail), $receiverName)
                                ->subject($subject);
                Event::fire(new CampaignEmailSentEvent($campaignId, $receiverEmail));
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

    private function preparedPlaceHolderValues($userName, $email ){
        return [
            Placeholder::USER_NAME => $userName,
            Placeholder::EMAIL => $email,
            Placeholder::OPEN_TRACKER_VAR => CampaignUtils::getOpenTrackerVariableValue($email, $this->campaign->id),
            Placeholder::UNSUBSCRIBE_LINK => CampaignUtils::getUnsubscribeLinkWithTracker($email, $this->campaign->id)

        ];
    }

    private function getMailSendDelay()
    {
        $publishedOn = new Carbon($this->campaign->published_on);
        $diffInSecond = $publishedOn->diffInSeconds(Carbon::now());
        return ($diffInSecond > 0)? $diffInSecond : 0;
    }

    private function getUserName($userName){
        return (!empty($userName)?$userName : self::DEFAULT_CUSTOMER_NAME);
    }
}
