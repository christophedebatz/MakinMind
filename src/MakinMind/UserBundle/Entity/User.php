<?php

namespace MakinMind\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * MakinMind\UserBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=40)
     */
    private $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=50)
     */
    private $lastname;

    /**
     * @var date $birthdate
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;


    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=10, nullable=true)
     */
    private $phone;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string $zipcode
     *
     * @ORM\Column(name="zipcode", type="string", length=5, nullable=true)
     */
    private $zipcode;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=40, nullable=true)
     */
    private $city;

    /**
     * @var string $country
     *
     * @ORM\Column(name="country", type="string", length=3, nullable=true)
     */
    private $country;

    /**
     * @var string $job
     *
     * @ORM\Column(name="job", type="string", length=50, nullable=true)
     */
    private $job;

    /**
     * @var string $company
     *
     * @ORM\Column(name="company", type="string", length=30, nullable=true)
     */
    private $company;

    /**
     * @var smallint $rank
     *
     * @ORM\Column(name="rank", type="smallint", nullable=true)
     */
    private $rank = 0;

    /**
     * @var integer $nbContracts
     *
     * @ORM\Column(name="nbContracts", type="integer", nullable=true)
     */
    private $nbContracts = 0;

    /**
     * @var date $suscribeDate
     *
     * @ORM\Column(name="suscribeDate", type="date", nullable=true)
     */
    private $suscribeDate;

    /**
     * @var date $description
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string $workSocialURL
     *
     * @ORM\Column(name="workSocialURL", type="string", length=255, nullable=true)
     */
    private $workSocialURL = NULL;

    /**
     * @var string $facebookURL
     *
     * @ORM\Column(name="facebookURL", type="string", length=255, nullable=true)
     */
    private $facebookURL = NULL;

    /**
     * @var string $twitterURL
     *
     * @ORM\Column(name="twitterURL", type="string", length=255, nullable=true)
     */
    private $twitterURL = NULL;

    /**
     * @var boolean $twitterShowTweets
     *
     * @ORM\Column(name="twitterShowTweets", type="boolean", nullable=true)
     */
    private $twitterShowTweets = false;


    /**
     * @ORM\OneToOne(targetEntity="MakinMind\ResourceBundle\Entity\Resource", cascade={"persist"})
     */
    private $resume;

    /**
     * @ORM\OneToOne(targetEntity="MakinMind\ResourceBundle\Entity\Resource", cascade={"persist"})
     */
    private $picture;


    public function __construct()
    {
        parent::__construct();
        
        $suscribeDate = new \Datetime();
        $birthdate = new \Datetime();
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
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }


    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return ucfirst($this->firstname) . " " . ucfirst($this->lastname);
    }


    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set birthdate
     *
     * @param date $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * Get birthdate
     *
     * @return date 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set job
     *
     * @param string $job
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * Get job
     *
     * @return string 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set company
     *
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set rank
     *
     * @param smallint $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * Get rank
     *
     * @return smallint 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set nbContracts
     *
     * @param integer $nbContracts
     */
    public function setNbContracts($nbContracts)
    {
        $this->nbContracts = $nbContracts;
    }

    /**
     * Get nbContracts
     *
     * @return integer 
     */
    public function getNbContracts()
    {
        return $this->nbContracts;
    }

    /**
     * Set suscribeDate
     *
     * @param date $suscribeDate
     */
    public function setSuscribeDate($suscribeDate)
    {
        $this->suscribeDate = $suscribeDate;
    }

    /**
     * Get suscribeDate
     *
     * @return date 
     */
    public function getSuscribeDate()
    {
        return $this->suscribeDate;
    }

    /**
     * Set workSocialURL
     *
     * @param string $workSocialURL
     */
    public function setWorkSocialURL($workSocialURL)
    {
        $this->workSocialURL = $workSocialURL;
    }

    /**
     * Get workSocialURL
     *
     * @return string 
     */
    public function getWorkSocialURL()
    {
        return $this->workSocialURL;
    }

    /**
     * Set facebookURL
     *
     * @param string $facebookURL
     */
    public function setFacebookURL($facebookURL)
    {
        $this->facebookURL = $facebookURL;
    }

    /**
     * Get facebookURL
     *
     * @return string 
     */
    public function getFacebookURL()
    {
        return $this->facebookURL;
    }

    /**
     * Set twitterURL
     *
     * @param string $twitterURL
     */
    public function setTwitterURL($twitterURL)
    {
        $this->twitterURL = $twitterURL;
    }

    /**
     * Get twitterURL
     *
     * @return string 
     */
    public function getTwitterURL()
    {
        return $this->twitterURL;
    }

    /**
     * Set twitterShowTweets
     *
     * @param boolean $twitterShowTweets
     */
    public function setTwitterShowTweets($twitterShowTweets)
    {
        $this->twitterShowTweets = $twitterShowTweets;
    }

    /**
     * Get twitterShowTweets
     *
     * @return boolean 
     */
    public function getTwitterShowTweets()
    {
        return $this->twitterShowTweets;
    }

    /**
     * Set resume
     *
     * @param MakinMind\ResourceBundle\Entity\Resource $resume
     */
    public function setResume(\MakinMind\ResourceBundle\Entity\Resource $resume)
    {
        $this->resume = $resume;
    }

    /**
     * Get resume
     *
     * @return MakinMind\ResourceBundle\Entity\Resource 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set picture
     *
     * @param MakinMind\ResourceBundle\Entity\Resource $picture
     */
    public function setPicture(\MakinMind\ResourceBundle\Entity\Resource $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get picture
     *
     * @return MakinMind\ResourceBundle\Entity\Resource 
     */
    public function getPicture()
    {
        return $this->picture;
    }
}