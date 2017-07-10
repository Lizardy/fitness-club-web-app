<?php

namespace AppBundle\Service;

use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Request;

class SmsNotificationSender extends NotificationSender
{
    private $smsMockSender;
    private $sendNotificationProducer;

    public function __construct(SmsMockSender $smsMockSender, $sendNotificationProducer){
        parent::__construct();
        echo "sms_notification_sender created\n";
        $this->smsMockSender = $smsMockSender;
        $this->sendNotificationProducer = $sendNotificationProducer;
    }

    public function execute(AMQPMessage $msg)
    {
        echo "sms start execute\n";
        //get data from the message which was fetched from queue
        $messageFromQueue = unserialize($msg->body);
        $text = $messageFromQueue['text'];
        $sendTo = $messageFromQueue['send_to'];
        echo sprintf ("received message: text: '%s' send to: '%s' \n", $text,$sendTo);

        //try to send sms via the mock sender
        $response = $this->smsMockSender->send(
            new Request(array(
                'phone' => $sendTo,
                'message' => $text
            ))
        );
        echo $response; echo "\n";
        if ($response->getStatusCode() === 200) {
            //acknowledge ok
            return parent::MSG_ACK;
        } else {
            //not able to send now, publish the same message to retry later
            $msg = array('text' => $text, 'send_to' => $sendTo);
            $this->sendNotificationProducer->setDeliveryMode(1);
            $this->sendNotificationProducer->publish(serialize($msg), 'rk.notification.sms');
            echo "published sms to be resent\n";
            return parent::MSG_ACK;
        }
    }
}
