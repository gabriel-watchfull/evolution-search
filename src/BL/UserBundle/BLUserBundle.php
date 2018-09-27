<?php

namespace BL\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BLUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
