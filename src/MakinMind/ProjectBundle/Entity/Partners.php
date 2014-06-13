<?php

namespace MakinMind\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MakinMind\ProjectBundle\Entity\Partners
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MakinMind\ProjectBundle\Entity\PartnersRepository")
 */
class Partners
{
    /**
     * @var smallint $role
     *
     * @ORM\Column(name="role", type="smallint")
     */
    private $role;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="MakinMind\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $partner;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="MakinMind\ProjectBundle\Entity\Project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * Set role
     *
     * @param smallint $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return smallint 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set partner
     *
     * @param MakinMind\UserBundle\Entity\User $partner
     */
    public function setPartner(\MakinMind\UserBundle\Entity\User $partner)
    {
        $this->partner = $partner;
    }

    /**
     * Get partner
     *
     * @return MakinMind\UserBundle\Entity\User 
     */
    public function getPartner()
    {
        return $this->partner;
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
}