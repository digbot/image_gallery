<?php

namespace Digger\Icard\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
    public function indexAction(Request $request)
    {
        $title  = 'Gallery index';
        $em     = $this->getDoctrine()->getManager();
        $qb     = $em->createQueryBuilder();
		$qb
		    ->select(array('a'))
			->from('DiggerIcardGalleryBundle:Image', 'a')
			->getQuery()
        ;
	     
        if ($request->isMethod('POST')) {
            $search = trim($request->get('search'));
            $qb->where('a.title LIKE ?1')
			->setParameter(1, '%' . $search .'%');
        }
       
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $this->get('request')->query->get('page', 1)/*page number*/,
            3/*limit per page*/
        );
    
        return array(
            'title' => $title,
            'pagination' => $pagination
        );
    }
    
    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction()
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
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
     * @Route("/view/{id}", name="view")
     * @ParamConverter("image", class="DiggerIcardGalleryBundle:Image")
     * @Template()
     */
    public function viewAction($image)
    {
        $title = 'Preview Image';
        
        return array(
            'properties' => get_object_vars ($image),
            'image' => $image,
            'title' => $title
        );
    }
    
    /**
     * @Route("/remove/{id}", name="remove")
     * @Template()
     */
    public function removeAction($id =0 )
    {
        $em    = $this->getDoctrine()->getManager();
        $image = $em->getRepository('DiggerIcardGalleryBundle:Image')->find($id);
        
        if (!$image instanceof Image) {
            $this->get('session')->getFlashBag()->add('error',  "This image do not exists!");
 
            return $this->redirect($this->generateUrl('home'));
        }
            
        $name = $image->getPath();
        $em->remove($image);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', "Successfully remove the image with name: $name ");
         
        return $this->redirect($this->generateUrl('home'));
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
