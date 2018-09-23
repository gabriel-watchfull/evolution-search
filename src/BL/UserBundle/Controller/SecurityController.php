<?php

namespace BL\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller {

    public function loginAction(Request $request) {

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('bl_platform_home');
        }

        $authentificationUtils = $this->get('security.authentication_utils');

        return $this->render('BLUserBundle:Security:login.html.twig', array(
            'last_user_name' => $authentificationUtils->getLastUsername(),
            'error' => $authentificationUtils->getLastAuthenticationError()
        ));
    }
}