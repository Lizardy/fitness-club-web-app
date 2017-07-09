<?php

namespace AppBundle\Service;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

abstract class NotificationSender implements ConsumerInterface
{
    public function __construct()
    {
        //todo: add logger
    }

    abstract public function execute(AMQPMessage $msg);
}
