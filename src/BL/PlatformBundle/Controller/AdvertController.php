<?php

namespace BL\PlatformBundle\Controller;

use BL\PlatformBundle\Entity\Advert;
use BL\PlatformBundle\Entity\Application;
use BL\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if (!$page || $page < 1) {
            $page = 1;
        }

        $nbPerPage = 5;
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('BLPlatformBundle:Advert');

        $listAdverts = $repository->getAdverts($page, $nbPerPage);

        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        return $this->render('BLPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts->getQuery()->getResult(),
            'nbPages'     => $nbPages,
            'page'        => $page,
        ));
    }

    public function viewAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('BLPlatformBundle:Advert');

        $advert = $repository->find($id);

        if(null == $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listApplications = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('BLPlatformBundle:Application')
            ->findBy(array('advert' => $advert));

        return $this->render('BLPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplications' => $listApplications
        ));
    }

    public function addAction(Request $request)
    {

        $advert = new Advert();
        $advert->setTitle('Recherche développeur Syfony.');
        $advert->setAuthor('Gabriel');
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");

        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('job de rêve');

        $advert->setImage($image);

        $application1 = new Application();
        $application1->setAuthor('Pierre');
        $application1->setContent('Je suis très motivé');

        $application2 = new Application();
        $application2->setAuthor('Marine');
        $application2->setContent('J\'ai toute les qualités requises');

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $em = $this->getDoctrine()->getManager();

        $em->persist($advert);



        $em->flush();

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirectToRoute('bl_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('BLPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
            return $this->redirectToRoute('bl_platform_view', array('id' => 5));
        }

        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('BLPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listCategories = $em->getRepository('BLPlatformBundle:Category')->findAll();
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        $listContracts = $em->getRepository('BLPlatformBundle:Contract')->findAll();
        foreach ($listContracts as $contract) {
            $advert->addContract($contract);
        }

        $em->flush();

        return $this->render('BLPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }

    public function deleteAction($id)
    {
        return $this->render('BLPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('BLPlatformBundle:Advert');

        $listAdverts = $repository->findAll();

        return $this->render('BLPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => array_slice($listAdverts, 0 , $limit)
        ));
    }
}