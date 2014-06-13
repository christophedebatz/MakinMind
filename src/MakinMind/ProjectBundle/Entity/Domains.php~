<?php

namespace MakinMind\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MakinMind\ProjectBundle\Entity\Domains
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\ProjectBundle\Entity\DomainsRepository")
 */
class Domains
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
     * @var smallint $level
     *
     * @ORM\Column(name="level", type="smallint")
     */
    private $level;


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
     * Set level
     *
     * @param smallint $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Get level
     *
     * @return smallint 
     */
    public function getLevel()
    {
        return $this->level;
    }
}