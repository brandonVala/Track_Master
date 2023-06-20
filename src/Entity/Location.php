<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="The latitude cannot be blank.")
     * @Assert\Type(type="numeric", message="The latitude must be a numeric value.")
     * @Assert\Range(
     *     min=-90,
     *     max=90,
     *     minMessage="The latitude must be at least {{ limit }}.",
     *     maxMessage="The latitude cannot be greater than {{ limit }}."
     * )
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="The longitude cannot be blank.")
     * @Assert\Type(type="numeric", message="The longitude must be a numeric value.")
     * @Assert\Range(
     *     min=-180,
     *     max=180,
     *     minMessage="The longitude must be at least {{ limit }}.",
     *     maxMessage="The longitude cannot be greater than {{ limit }}."
     * )
     */
    private $longitude;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="location")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
