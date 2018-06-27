<?php

namespace BL\PlatformBundle\Controller;

use BL\PlatformBundle\Entity\Advert;
use BL\PlatformBundle\Entity\Application;
use BL\PlatformBundle\Entity\Image;
use BL\PlatformBundle\Form\AdvertType;
use BL\PlatformBundle\Form\AdvertEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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

        $form = $this->get('form.factory')->create(AdvertType::class,$advert);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée');

                return $this->redirectToRoute('bl_platform_view', array('id' => $advert->getId()));
            }
        }

        return $this->render('BLPlatformBundle:Advert:add.html.twig', array(
           'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em
            ->getRepository('BLPlatformBundle:Advert')
            ->find($id)
        ;

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $formBuilder = $this->get('form.factory')->create(AdvertEditType::class, $advert);

        $listCategories = $em->getRepository('BLPlatformBundle:Category')->findAll();
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        $listContracts = $em->getRepository('BLPlatformBundle:Contract')->findAll();
        foreach ($listContracts as $contract) {
            $advert->addContract($contract);
        }

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
            return $this->redirectToRoute('bl_platform_view', array('id' => $id));
        }

        return $this->render('BLPlatformBundle:Advert:edit.html.twig', array(
            'form' => $formBuilder->createView(),
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