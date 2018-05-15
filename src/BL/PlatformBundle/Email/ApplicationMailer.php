<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 13/05/18
 * Time: 13:51
 */

namespace BL\PlatformBundle\Email;

use BL\PlatformBundle\Entity\Application;

class ApplicationMailer
{

    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewNotification(Application $application) {
        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Nous avez reçu une nouvelle candidature.'
        );

        $message
            ->addTo($application->getAdvert()->getAuthor())
            ->addFrom('admin@evolutionsearch.com');

        $this->mailer->send($message);
    }

}