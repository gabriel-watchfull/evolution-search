<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 28/10/18
 * Time: 19:45
 */

namespace BL\PlatformBundle\BigBrother;

use Symfony\Component\Security\Core\User\UserInterface;

class MessageNotificator
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyByEmail($message, UserInterface $user) {
        $message = \Swift_Message::newInstance()
            ->setSubject("Nouveau message d'un utilisateur surveillé")
            ->setFrom("admin@votresite.com")
            ->setTo("admin@votresite.com")
            ->setBody("L'utilisateur surveillé '". $user->getUsername()." a posté le message suivant :".$message);

        $this->mailer->send($message);
    }
}