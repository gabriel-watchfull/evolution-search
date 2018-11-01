<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 30/10/18
 * Time: 06:24
 */

namespace BL\PlatformBundle\BigBrother;

use BL\PlatformBundle\Event\MessagePostEvent;

class MessageListener
{
    protected $notificator;
    protected $listUsers = array();

    public function __construct($notificator, $listUsers)
    {
        $this->notificator = $notificator;
        $this->notificator = $notificator;
    }

    public function processMessage(MessagePostEvent $event) {
        if(in_array($event->getUser()->getUsername(), $this->listUsers)) {
            $this->notificator->notifyByEmail($event->getMessage(), $event->getUser());
        }
    }
}