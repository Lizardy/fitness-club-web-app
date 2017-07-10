<?php

namespace AppBundle\Service;

use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Request;

class SmsNotificationResender extends NotificationSender
{
    public function __construct(){
        parent::__construct();
        echo "sms_notification_REsender created\n";
    }

    // there's nothing to do in this callback,
    // it just provides the ability to declare a queue "without" consumers
    // where messages are living their TTL until retry to be sent
    public function execute(AMQPMessage $msg){}
}
