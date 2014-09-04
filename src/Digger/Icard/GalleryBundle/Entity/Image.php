<?php

namespace Digger\Icard\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 * @ORM\Entity(repositoryClass="Digger\Icard\GalleryBundle\Entity\ImageRepository")
 * @ORM\Table(name="icard_image")
 * @ORM\HasLifecycleCallbacks
 */
class Image extends Base\Image
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * 
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var boolean
       @ORM\Column(name="enabled", type="boolean", length=255)
     */
    private $enabled = 1;

    /**
     * @var integer
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Image
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     * @return Image
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Image
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Image
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Image
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Image
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Image
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Image
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function preImageUpload()
    {
        $this->created_at = new \DateTime();

        $this->updated_at = new \DateTime();

        if (null !== $this->imageTemp)
        {
            $this->image = sha1(uniqid(mt_rand(), true)) . '.' . $this->imageTemp->guessExtension();
        }
    }
    /**
     * @ORM\PrePersist
     */
    public function preThumbnailUpload()
    {
        if (null !== $this->thumbnailTemp)
        {
            $this->thumbnail = sha1(uniqid(mt_rand(), true)) . '.' . $this->thumbnailTemp->guessExtension();
        }
    }

    /**
     * @ORM\PostRemove
     */
    public function postRemove()
    {
        if ($file = $this->getAbsolutePath($this->image))
        {
            @unlink($file);
        }
        if ($file = $this->getAbsolutePath($this->thumbnail))
        {
            @unlink($file);
        }
    }

    /**
     * @ORM\PostPersist
     */
    public function postImageUpload()
    {
        if ($this->imageTemp instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
        {
            $this->imageTemp->move($this->getUploadRootDir(), $this->image);

            unset($this->imageTemp);
        }
    }
    /**
     * @ORM\PostPersist
     */
    public function postThumbnailUpload()
    {
        if ($this->thumbnailTemp instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
        {
            $this->thumbnailTemp->move($this->getUploadRootDir(), $this->thumbnail);

            unset($this->thumbnailTemp);
        }
    }
    
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->image = $this->getFile()->getClientOriginalName();
        // clean up the file property as you won't need it anymore
        
        $this->file = null;
    }
}