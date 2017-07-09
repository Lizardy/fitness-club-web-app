<?php

namespace AppBundle\Service;

use PhpAmqpLib\Message\AMQPMessage;

class EmailNotificationSender extends NotificationSender
{
    private $mailer;
    private $mailFrom;

    public function __construct(\Swift_Mailer $mailer, $mailFromAddress, $mailFromName)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->mailFrom = array($mailFromAddress => $mailFromName);
        echo "email_notification_sender created\n";
    }

    public function execute(AMQPMessage $msg)
    {
        echo "start execute\n";
        $messageFromQueue = unserialize($msg->body);
        $text = $messageFromQueue['text'];
        $sendTo = $messageFromQueue['send_to'];
        echo sprintf ("received message: text: '%s' sendto: '%s' \n",$text,$sendTo);
        $message = $this->mailer->createMessage()
            ->setSubject('Notification from Fitness club')
            ->setFrom($this->mailFrom)
            ->setTo($sendTo)
            ->setBody($text);
        $res = $this->mailer->send($message);
        echo "email sent: " . $res . "\n";
        return $res;
    }
}
