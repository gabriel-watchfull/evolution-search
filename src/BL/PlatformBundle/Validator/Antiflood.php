<?php

namespace BL\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 *
 * @Annotation
 *
 */

class Antiflood extends Constraint {
    public $message = "Vous avez déjà posté un message, il y a 15 secondes; merci de patienter";
}