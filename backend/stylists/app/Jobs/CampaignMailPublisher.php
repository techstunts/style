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
use Event;
use Illuminate\Database\QueryException ;
use App\Campaign\MailerService;
use App\Campaign\Entities\Receiver;

class CampaignMailPublisher extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    const PUBLISH_QUEUE = 'campaign-publisher';
    const DUPLICATE_ENTRY_EXCEPTION_CODE = 1062;

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
            $mailerService =  new MailerService($this->campaign);
            do {
                $users = MailerMasterRepository::getUsers($counter);
                foreach($users as $user){
                    $counter++;
                    try {
                        $receiver = new Receiver($user->email, $user->name);
                        $this->addToCampaignMailerRepository($user->email, $receiver->getName());
                        $mailerService ->sendMail($receiver, true);
                    }catch (QueryException $exception){
                        /** In case of duplicate entry, don't throw exception. **/
                        if(!isset($exception->errorInfo[1]) || $exception->errorInfo[1] !== self::DUPLICATE_ENTRY_EXCEPTION_CODE )
                            throw $exception;
                    }
                }
            } while($counter < $totalUser);
        }
    }


    private function addToCampaignMailerRepository($email, $name)
    {
        CampaignMailerRepository::create([ "email" => $email, "name" => $name, "campaign_id" => $this->campaign->id]);
    }
}
