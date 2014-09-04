<?php


namespace Digger\Icard\GalleryBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 */
abstract class Image
{

    protected $imageTemp;
    protected $thumbnailTemp;

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getImageTemp()
    {
        return $this->imageTemp;
    }

    public function setImageTemp($image)
    {
        $this->imageTemp = $image;
    }

    public function getThumbnailTemp()
    {
        return $this->thumbnailTemp;
    }

    public function setThumbnailTemp($thumbnail)
    {
        $this->thumbnailTemp = $thumbnail;
    }

    public function getAbsolutePath($file)
    {
        return null === $file ? null : $this->getUploadRootDir() . '/' . $file;
    }

    public function getWebPath($file)
    {
        return null === $file ? null : $this->getUploadDir() . '/' . $file;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads';
    }

}