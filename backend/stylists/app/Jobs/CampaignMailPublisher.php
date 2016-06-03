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

class CampaignMailPublisher extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    const PUBLISH_QUEUE = 'campaign-publisher';
    const MAIL_QUEUE = 'campaign-mails';

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
        if($totalUser > 0){
            $counter = 0;
            do {
                $users = MailerMasterRepository::getUsers($counter);
                foreach($users as $user){
                    $counter++;
                    $this->addToCampaignMailerRepository($user->email, $user->name);
                    $this->pushToMailQueue( $this->campaign->sender_name, $this->campaign->sender_email,
                                            $user->name, $user->email,
                                            $this->prepareMailContent($this->campaign->prepared_message),
                                            $this->campaign->mail_subject, $this->getMailSendDelay());
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

    private function pushToMailQueue($senderName, $senderEmail, $receiverName, $receiverEmail, $content, $subject, $delay)
    {
        $campaignId = $this->campaign->id;
        Mail::laterOn(self::MAIL_QUEUE, $delay, 'campaign.email', ['email_content' => $content], function ($message)
               use($senderName, $senderEmail, $receiverName, $receiverEmail, $subject, $campaignId) {
                    $message->from(trim($senderEmail), $senderName)
                                ->to(trim($receiverEmail), $receiverName)
                                ->subject($subject);
            Event::fire(new CampaignEmailSentEvent($campaignId, $senderEmail));
        });
    }

    private function prepareMailContent($message)
    {
        return $message;
    }

    private function getMailSendDelay()
    {
        $publishedOn = new Carbon($this->campaign->published_on);
        $diffInSecond = $publishedOn->diffInSeconds(Carbon::now());
        return ($diffInSecond > 0)? $diffInSecond : 0;
    }

}
