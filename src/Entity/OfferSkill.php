<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferSkillRepository")
 */
class OfferSkill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $skill;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JobOffer", inversedBy="offerSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joboffer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recruiter", inversedBy="offerSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recruiter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?string
    {
        return $this->skill;
    }

    public function setSkill(string $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getJoboffer(): ?JobOffer
    {
        return $this->joboffer;
    }

    public function setJoboffer(?JobOffer $joboffer): self
    {
        $this->joboffer = $joboffer;

        return $this;
    }

    public function getRecruiter(): ?Recruiter
    {
        return $this->recruiter;
    }

    public function setRecruiter(?Recruiter $recruiter): self
    {
        $this->recruiter = $recruiter;

        return $this;
    }
}
