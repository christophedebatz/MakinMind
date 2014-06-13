<?php

namespace MakinMind\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MakinMind\ProjectBundle\Entity\Step
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\ProjectBundle\Entity\StepRepository")
 */
class Step
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
     * @ORM\Column(name="name", type="string", length=40)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=150)
     */
    private $description;

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
     * @var smallint $progress
     *
     * @ORM\Column(name="progress", type="smallint")
     */
    private $progress;

    /**
     * @ORM\ManyToOne(targetEntity="MakinMind\ProjectBundle\Entity\Project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToMany(targetEntity="MakinMind\UserBundle\Entity\User", cascade={"persist"})
     */
    private $workers;


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
     * Set progress
     *
     * @param smallint $progress
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }

    /**
     * Get progress
     *
     * @return smallint 
     */
    public function getProgress()
    {
        return $this->progress;
    }
    public function __construct()
    {
        $this->workers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add workers
     *
     * @param MakinMind\UserBundle\Entity\User $workers
     */
    public function addWorker(\MakinMind\UserBundle\Entity\User $worker)
    {
        $this->workers[] = $workers;
    }

    /**
     * Get workers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getWorkers()
    {
        return $this->workers;
    }
}