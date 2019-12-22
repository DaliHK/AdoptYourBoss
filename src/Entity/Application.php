<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\JobOffer;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table(name="application", indexes={@ORM\Index(name="IDX_A45BDDC1A76ED397", columns={"user_id"}), @ORM\Index(name="IDX_A45BDDC1A76ED395", columns={"job_offer_id"})})
 * @ORM\Entity
 */
class Application
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */

    private $id;

    /**
     * @var \JobOffer
     *
     * @ORM\ManyToOne(targetEntity="JobOffer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job_offer_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $jobOffers;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * })
     */

    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobOffers()
    {
        return $this->jobOffers;
    }

    public function setJobOffer(JobOffer $jobOffers): self
    {
        $this->jobOffers = $jobOffers;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setJobOffers(?JobOffer $jobOffers): self
    {
        $this->jobOffers = $jobOffers;

        return $this;
    }
}
