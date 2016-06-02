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

class CampaignMailPublisher extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    const PUBLISH_QUEUE = 'campaign-publisher';
    const MAIL_QUEUE = 'campaign-mails';

    private $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
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
                                            $this->campaign->mail_subject);
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

    private function pushToMailQueue($senderName, $senderEmail, $receiverName, $receiverEmail, $content, $subject)
    {
        Mail::laterOn(self::MAIL_QUEUE, 5, 'campaign.email', ['email_content' => $content], function ($message)
               use($senderName, $senderEmail, $receiverName, $receiverEmail, $subject) {
                    $message->from(trim($senderEmail), $senderName)
                                ->to(trim($receiverEmail), $receiverName)
                                ->subject($subject);
        });
    }

    private function prepareMailContent($message)
    {
        return $message;
    }

}
