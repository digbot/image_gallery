<?php


namespace Digger\Icard\GalleryBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 */
abstract class Image
{
    const THUMB_DIR = 'thumbnail';
    const UPLOAD_DIR = 'uploads';
    
    protected $file;
        
    public function __toString()
    {
        return $this->getTitle();
    }
    
    public function getUploadDir()
    {
        return self::UPLOAD_DIR;
    }
    
    public function getUploadThumbnailDir()
    {
        return  self::UPLOAD_DIR. '/'.self::THUMB_DIR;
    }
}