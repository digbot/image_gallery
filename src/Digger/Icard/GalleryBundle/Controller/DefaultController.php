<?php

namespace Digger\Icard\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Digger\Icard\GalleryBundle\Entity\Image;
use Digger\Icard\GalleryBundle\Form\ImageType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $title  = 'Gallery index';
        $em     = $this->getDoctrine()->getManager();
        $items  = $em->getRepository('DiggerIcardGalleryBundle:Image')->findAll();

        return array(
            'title' => $title,
            'items' => $items
        );
    }
    
    /**
     * @Route("/upload", name="upload")
     * @Template()
     */
    public function uploadAction(Request $request)
    {
        $title = 'Upload image';
        $image = new Image();
        
        $form = $this->createForm(new ImageType(), $image, array(
          'csrf_protection' => false,
        //  'method'          => 'PUT'
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image->upload();
            $em->persist($image);
            $em->flush();
         
            $this->get('session')->getFlashBag()->add('success',  "Successfully image upload.");
         
            return $this->redirect($this->generateUrl('home'));
        }

        return array(
            'form' => $form->createView(),
            'title' => $title
        );
    }
    
    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        $title = 'About';
        
        return array('title' => $title);
    }
}
