<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 05/06/16
 * Time: 6:12 PM
 */

namespace App\Campaign\Entities;


class Message {

    private $receiver;
    private $sender;
    private $content;
    private $subject;

    function __construct(Receiver $receiver, Sender $sender, $subject, $content)
    {
        $this->content = $content;
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return Receiver
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @return Sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

} 