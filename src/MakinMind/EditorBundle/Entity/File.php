<?php

namespace MakinMind\EditorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MakinMind\EditorBundle\Entity\File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\EditorBundle\Entity\FileRepository")
 */
class File
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var smallint $privileges
     *
     * @ORM\Column(name="privileges", type="smallint")
     */
    private $privileges;

    /**
     * @var datetime $time
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="MakinMind\ProjectBundle\Entity\Project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="MakinMind\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


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
     * Set privileges
     *
     * @param smallint $privileges
     */
    public function setPrivileges($privileges)
    {
        $this->privileges = $privileges;
    }

    /**
     * Get privileges
     *
     * @return smallint 
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * Set time
     *
     * @param datetime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return datetime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set project
     *
     * @param MakinMind\ProjectBundle\Entity\Project $project
     */
    public function setProject(\MakinMind\ProjectBundle\Entity\Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return MakinMind\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set author
     *
     * @param MakinMind\UserBundle\Entity\User $author
     */
    public function setAuthor(\MakinMind\UserBundle\Entity\User $author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return MakinMind\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

}