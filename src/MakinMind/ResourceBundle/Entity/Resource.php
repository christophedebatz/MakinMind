<?php

namespace MakinMind\ResourceBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MakinMind\UserBundle\Entity\User as User;

/**
 * MakinMind\ResourceBundle\Entity\Resource
 *
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\ResourceBundle\Entity\ResourceRepository")
 */
class Resource
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string $licence
     *
     * @ORM\Column(name="licence", type="string", length=25, nullable=true)
     */
    private $licence;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @var string $mimeType
     *
     * @ORM\Column(name="mimeType", type="string", length=30, nullable=true)
     */
    private $mimeType;


    /**
     * @ORM\ManyToOne(targetEntity="MakinMind\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set licence
     *
     * @param string $licence
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;
    }

    /**
     * Get licence
     *
     * @return string 
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * Set file
     *
     * @param File $licence
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set owner
     *
     * @param User $owner
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get Owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }


    public function getAbsoluteUrl()
    {
        return null === $this->url ? null : $this->getUploadRootDir().'/'.$this->url;
    }

    public function getWebUrl()
    {
        return null === $this->url ? null : $this->getUploadDir().'/'.$this->url;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads';
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->url = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
            $this->setMimeType($this->file->getMimeType());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file)
            return;
        $this->file->move($this->getUploadRootDir(), $this->url);
        unset($this->file);
    }


    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsoluteUrl()) {
            unlink($file);
        }
    }
}