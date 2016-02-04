<?php

namespace App\Jobs;

use App\Client;
use App\EmailQueue;
use App\Jobs\Job;
use App\Models\Enums\EmailQueueStatus;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $email_queue;
    protected $keep_same_status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EmailQueue $email_queue)
    {
        $this->email_queue = $email_queue;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->email_queue->delivery_attempts++;

            if($this->max_attempts < $this->email_queue->delivery_attempts){
                $this->keep_same_status = true;
                throw new Exception('Max ' . $this->max_attempts . ' attempts allowed.');
            }
            else if($this->email_queue->status_id == EmailQueueStatus::Delivered){
                $this->keep_same_status = true;
                throw new Exception('Mail already delivered.');
            }

            $user_id = '';

            $email_queue_parameters = $this->email_queue->parameters;

            foreach($email_queue_parameters as $param){
                if($param->name == 'user_id'){
                    $user_id = $param->value;
                    break;
                }
            }

            if($user_id == ''){
                $msg = 'User_id missing in email_queue_parameters for email_queue_id=' . $this->email_queue->id;
                throw new Exception($msg);
            }

            $email_data = $this->prepare($this->email_queue->emailTemplate, $user_id);

            Mail::send('emails.users', ['email_content' => $email_data['content']], function ($message) use ($email_data) {
                $message->from($email_data['from_email_address']);
                $message->to($email_data['to_email_address']);
                $message->bcc('amitk@istyleyou.in');
                $message->replyTo($email_data['reply_to_email_address']);
                $message->subject($email_data['subject']);
            });

            if(!$this->keep_same_status) {
                $this->email_queue->status_id = EmailQueueStatus::Delivered;
            }
            $this->email_queue->delivery_comments = "Mail delivered.";
            $this->email_queue->save();
        }
        catch(Exception $e){
            $msg = 'Exception occured. Message : ' . $e->getMessage() . "\n" . $e->getTraceAsString();

            if(!$this->keep_same_status){
                $this->email_queue->status_id = EmailQueueStatus::DeliveryFailed;
            }
            $this->email_queue->delivery_comments = $msg;
            $this->email_queue->save();

            throw $e;
        }

    }

    protected function prepare($email_template, $user_id)
    {
        $email_data['subject'] = $email_template->subject;
        $email_data['content'] = $email_template->content;
        $email_data['from_email_address'] = $email_template->from_email_address;
        $email_data['to_email_address'] = $email_template->to_email_address;
        $email_data['reply_to_email_address'] = $email_template->reply_to_email_address;

        $user_data = $this->getUserEmailData($user_id);

        foreach ($email_data as &$item) {
            $item = $this->replaceTags($item, $user_data);
        }
        
        return $email_data;
    }

    protected function getUserEmailData($user_id)
    {
        $user = Client::find($user_id);
        if($user && $user->user_id){
            $stylist = $user->stylist;
            $data['user_email'] = $user->user_email;
            $data['username'] = $user->username;
            $data['stylist_name'] = $stylist->name;
            $data['stylist_email'] = $stylist->email;
            $data['stylist_image'] = $stylist->image;
            $data['stylist_designation'] = $stylist->designation->name;
        }
        else{
            throw new Exception('Client not found for user_id=' . $user_id);
        }

        return $data;
    }

    protected function replaceTags($subject, $replacements){
        $mapping['user_email_address'] = 'user_email';
        $mapping['user_first_name'] = 'username';
        $mapping['stylist_name'] = 'stylist_name';
        $mapping['stylist_email_address'] = 'stylist_email';

        foreach($mapping as $key => $value){
            if(isset($replacements[$value])){
                $subject = str_replace('{{' . $key . '}}', $replacements[$value], $subject);
            }
        }
        return $subject;
    }
}
