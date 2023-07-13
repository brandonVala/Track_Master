<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// Importaciones adicionales
use App\Entity\User;
use App\Entity\Admin;

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
     *     notInRangeMessage="The latitude must be between {{ min }} and {{ max }}.",
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
     *     notInRangeMessage="The longitude must be between {{ min }} and {{ max }}.",
     * )
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="locations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $admin;

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

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }
}
