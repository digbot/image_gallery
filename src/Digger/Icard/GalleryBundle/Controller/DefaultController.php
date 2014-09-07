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
        $qb  = $em->getRepository('DiggerIcardGalleryBundle:Image')->getBaseQueryBuilder();
        $search = trim($request->get('search'));        ///$request->isMethod('POST'))
        if ($search) {
            $qb = $this->search($qb, $request);
        }
       
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $this->get('request')->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );
        
        if ($request->get('sort')) {
            $this->get('session')->getFlashBag()->add('info', sprintf("Sorting by %s by %s.", $request->get('sort'),$request->get('direction')));
        }
         
        return array(
            'title' => $title,
            'pagination' => $pagination
        );
    }
 
    private function search(\Doctrine\ORM\QueryBuilder $qb, Request $request)
    {
        $search = trim($request->get('search'));
        $target = $request->get('target');
        $message = "Searching for '".$search."'";

        if ($search) { 
            switch($target)
            {
                case 'Note':
                    $qb->where('a.note LIKE ?1')
                    ->setParameter(1, '%' . $search . '%');
                    break;
                case 'Title + Note': 
                    $qb->where('a.note LIKE ?1 OR a.title LIKE ?1')
                    ->setParameter(1, '%' . $search . '%');
                    break;
                case 'Title':
                default:
                    $qb->where('a.title LIKE ?1')
                    ->setParameter(1, '%' . $search . '%');
                    break;
            }

            if ($target) {
                   $message .= ", searching in $target.";
            }
            $this->get('session')->getFlashBag()->add('info', $message);
        }

        return $qb;
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
     * @Route("/upload/{id}", name="upload_edit")
     * @Template()
     */
    public function uploadAction(Request $request)
    {
        $id = $request->get('id'); 
        $em = $this->getDoctrine()->getManager();
        
        if ($id) {
            $title = 'Image edit';
            $image = $em->getRepository('DiggerIcardGalleryBundle:Image')->find($id);
        } else {
            $title = 'Image upload';
            $image = new Image();
            $image->setTitle('Default title');
        }
        
        $form = $this->createForm(new ImageType(), $image, array(
            'csrf_protection' => false,
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
           
            $em->persist($image);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success',  "Successfully $title.");
            
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
        $title = $image->getTitle() . ' Preview';
        
        return array(
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
