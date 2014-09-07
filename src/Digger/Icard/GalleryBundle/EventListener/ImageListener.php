<?php

namespace Digger\Icard\GalleryBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Digger\Icard\GalleryBundle\Entity\Image;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

class ImageListener
{ 
    protected $container;
    
    protected $entity;
       
    protected $request;
       
    public function __construct(ContainerInterface $container, RequestStack $requestStack)
    {
        $this->container = $container;
         
        $this->request = $requestStack->getCurrentRequest();
    }
    
    public function getRequest() 
    {
        return $this->request;
    }
    
    public function postFlush(PostFlushEventArgs  $args) 
    {
        if ($this->entity instanceof Image) {
            if ($this->needsFlush) {
                $this->needsFlush = false;
                $args->getEntityManager()->flush();
            }
        }
    }
    
    public function postPersist(LifecycleEventArgs  $args) 
    {
        $this->process($args);
    }
    
    public function postUpdate(LifecycleEventArgs  $args) 
    {
        $this->process($args);
    }
    
    public function preRemove(LifecycleEventArgs $args) 
    {
        $entity     = $args->getEntity();
        $this->temp = $entity->getPath();
    }
    
    public function postRemove(LifecycleEventArgs $args) 
    {
        $entity = $args->getEntity();
        if (isset($this->temp)) {
            // delete the old image
            @unlink($entity->getUploadThumbnailDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
    }
    
    private function process(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
            
        if ($entity instanceof Image) {
            $this->entity = $entity;
            $this->needsFlush = true;
            
            $imagine = new \Imagine\Gd\Imagine();
            $size    = new \Imagine\Image\Box(150, 150);
            $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
            
            $imagine->open($entity->getAbsolutePath())
                ->thumbnail($size, $mode)
                ->save($entity->getUploadThumbnailRootDir() .'/'. $entity->getPath())
            ;
            
           $this->entity->setThumbnail('thumbnail' .'/'. $entity->getPath());
           
        }
    }
}