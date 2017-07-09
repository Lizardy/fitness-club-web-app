<?php

namespace AppBundle\Service;

use PhpAmqpLib\Message\AMQPMessage;

class SmsNotificationSender extends NotificationSender
{
    public function __construct(){}

    public function execute(AMQPMessage $msg)
    {
        $message = unserialize($msg->body);
        $userid = $message['userid'];
    }
}
