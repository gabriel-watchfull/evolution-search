<?php
namespace BL\PlatformBundle\Antispam;

class BLAntispam
{
    private $mailer;
    private $locale;
    private $minLength;

    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->mailer    = $mailer;
        $this->locale    = $locale;
        $this->minLength = (int) $minLength;
    }

    /*
        * @param string $text
        * @return bool
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}