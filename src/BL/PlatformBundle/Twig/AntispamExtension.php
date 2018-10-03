<?php

namespace BL\PlatformBundle\Twig;

use BL\PlatformBundle\Antispam\BLAntispam;

class AntispamExtension extends \Twig_Extension {

    private $blAntispam;

    public function __construct(BLAntispam $blAntispam)
    {
        $this->blAntispam = $blAntispam;
    }

    public function checkIfArgumentIsSpam($text) {
        return $this->blAntispam->isSpam($text);
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('checkIfSpam', array($this, 'checkIfArgumentIsSpam')),
        );
    }

    public function getName() {
        return 'BLAntispam';
    }
}