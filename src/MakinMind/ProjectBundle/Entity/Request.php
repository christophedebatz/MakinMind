<?php

namespace MakinMind\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MakinMind\ProjectBundle\Entity\Request
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\ProjectBundle\Entity\RequestRepository")
 */
class Request
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
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var text $motivations
     *
     * @ORM\Column(name="motivations", type="text")
     */
    private $motivations;

    /**
     * @ORM\ManyToOne(targetEntity="MakinMind\ProjectBundle\Entity\Project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="MakinMind\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set motivation
     *
     * @param text $motivation
     */
    public function setMotivations($motivations)
    {
        $this->motivations = $motivations;
    }

    /**
     * Get motivation
     *
     * @return text 
     */
    public function getMotivations()
    {
        return $this->motivations;
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
     * Set user
     *
     * @param MakinMind\UserBundle\Entity\User $user
     */
    public function setUser(\MakinMind\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return MakinMind\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}