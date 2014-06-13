<?php

namespace MakinMind\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MakinMind\ProjectBundle\Entity\Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\ProjectBundle\Entity\ProjectRepository")
 */
class Project
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string $shortDescription
     *
     * @ORM\Column(name="shortDescription", type="string", length=200)
     */
    private $shortDescription;

    /**
     * @var text $longDescription
     *
     * @ORM\Column(name="longDescription", type="text")
     */
    private $longDescription;

    /**
     * @var date $beginDate
     *
     * @ORM\Column(name="beginDate", type="date")
     */
    private $beginDate;

    /**
     * @var date $endingDate
     *
     * @ORM\Column(name="endingDate", type="date")
     */
    private $endingDate;

    /**
     * @var boolean $recruitment
     *
     * @ORM\Column(name="recruitment", type="boolean")
     */
    private $recruitment;


    /**
     * @ORM\OneToOne(targetEntity="MakinMind\ResourceBundle\Entity\Resource", cascade={"persist"})
     */
    private $coverPic;


    /**
     * @ORM\OneToOne(targetEntity="MakinMind\ResourceBundle\Entity\Resource", cascade={"persist"})
     */
    private $logo;


    /**
     * @ORM\ManyToOne(targetEntity="MakinMind\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;


    /**
     * @ORM\ManyToMany(targetEntity="MakinMind\ProjectBundle\Entity\Domains", cascade={"persist"})
     */
    private $domains;


    /**
     * @ORM\OneToOne(targetEntity="MakinMind\ProjectBundle\Entity\ApplyTerms", cascade={"persist"})
     */
    private $applyTerms;

    /**
     * get url
     *
     * @return string
     */

    public function getUrl() {
        $url = $this->slugify($this->name);
        return $url;
    }

    private function slugify($text) {
        if (empty($text)) 
            return '';

        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        return $text;
    }

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param text $longDescription
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;
    }

    /**
     * Get longDescription
     *
     * @return text 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set beginDate
     *
     * @param date $beginDate
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;
    }

    /**
     * Get beginDate
     *
     * @return date 
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Set endingDate
     *
     * @param date $endingDate
     */
    public function setEndingDate($endingDate)
    {
        $this->endingDate = $endingDate;
    }

    /**
     * Get endingDate
     *
     * @return date 
     */
    public function getEndingDate()
    {
        return $this->endingDate;
    }

    /**
     * Set recrutment
     *
     * @param boolean $recrutment
     */
    public function setRecruitment($recruitment)
    {
        $this->recruitment = $recruitment;
    }

    /**
     * Get recrutment
     *
     * @return boolean 
     */
    public function getRecrutment()
    {
        return $this->recrutment;
    }
    public function __construct()
    {
        $this->domains = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set coverPic
     *
     * @param MakinMind\ResourceBundle\Entity\Resource $coverPic
     */
    public function setCoverPic(\MakinMind\ResourceBundle\Entity\Resource $coverPic)
    {
        $this->coverPic = $coverPic;
    }

    /**
     * Get coverPic
     *
     * @return MakinMind\ResourceBundle\Entity\Resource 
     */
    public function getCoverPic()
    {
        return $this->coverPic;
    }

    /**
     * Set logo
     *
     * @param MakinMind\ResourceBundle\Entity\Resource $logo
     */
    public function setLogo(\MakinMind\ResourceBundle\Entity\Resource $logo)
    {
        $this->logo = $logo;
    }

    /**
     * Get logo
     *
     * @return MakinMind\ResourceBundle\Entity\Resource 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set owner
     *
     * @param MakinMind\UserBundle\Entity\User $owner
     */
    public function setOwner(\MakinMind\UserBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return MakinMind\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add domains
     *
     * @param MakinMind\ProjectBundle\Entity\Domains $domains
     */
    public function addDomain(\MakinMind\ProjectBundle\Entity\Domains $domain)
    {
        $this->domains[] = $domains;
    }

    /**
     * Get domains
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * Get recruitment
     *
     * @return boolean 
     */
    public function getRecruitment()
    {
        return $this->recruitment;
    }

    /**
     * Set applyTerms
     *
     * @param MakinMind\ProjectBundle\Entity\ApplyTerms $applyTerms
     */
    public function setApplyTerms(\MakinMind\ProjectBundle\Entity\ApplyTerms $applyTerms)
    {
        $this->applyTerms = $applyTerms;
    }

    /**
     * Get applyTerms
     *
     * @return MakinMind\ProjectBundle\Entity\ApplyTerms 
     */
    public function getApplyTerms()
    {
        return $this->applyTerms;
    }

    /**
     * Add domains
     *
     * @param MakinMind\ProjectBundle\Entity\Domains $domains
     */
    public function addDomains(\MakinMind\ProjectBundle\Entity\Domains $domains)
    {
        $this->domains[] = $domains;
    }

    public function hisProject(\MakinMind\UserBundle\Entity\User $user)
    {
        if ($this->owner == $user)
            return true;
        else
            return false;
    }

    public function __toString()
    {
        return strval($this->id);
    }
}