<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * JobOffer
 * @ORM\Entity
 */

class JobOffer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=false)
     */
    private $description;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="publication_date", type="datetime", nullable=false)
     */
    private $publicationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;


    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_start", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="contract", type="string", length=255, nullable=false)
     */
    private $contract;

    /**
     *@ORM\ManyToOne(targetEntity="Recruiter")
     *  @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="recruiter_id", referencedColumnName="id")
     *  })
     */
    private $recruiter;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OfferSkill", mappedBy="joboffer")
     */
    private $offerSkills;

    public function __construct()
    {
        $this->offerSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = $contract;

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

    /**
     * @return Collection|OfferSkill[]
     */
    public function getOfferSkills(): Collection
    {
        return $this->offerSkills;
    }

    public function addOfferSkill(OfferSkill $offerSkill): self
    {
        if (!$this->offerSkills->contains($offerSkill)) {
            $this->offerSkills[] = $offerSkill;
            $offerSkill->setJoboffer($this);
        }

        return $this;
    }

    public function removeOfferSkill(OfferSkill $offerSkill): self
    {
        if ($this->offerSkills->contains($offerSkill)) {
            $this->offerSkills->removeElement($offerSkill);
            // set the owning side to null (unless already changed)
            if ($offerSkill->getJoboffer() === $this) {
                $offerSkill->setJoboffer(null);
            }
        }

        return $this;
    }

}
