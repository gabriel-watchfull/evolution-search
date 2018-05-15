<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 13/05/18
 * Time: 15:44
 */

namespace BL\PlatformBundle\DoctrineListener;


use BL\PlatformBundle\Email\ApplicationMailer;
use BL\PlatformBundle\Entity\Application;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ApplicationCreationListener
{
    private $applicationMailer;

    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer = $applicationMailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof  Application) {
            return;
        }

        $this->applicationMailer->sendNewNotification($entity);
    }
}