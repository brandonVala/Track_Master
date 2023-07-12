<?php
namespace App\Entity;

use App\Repository\GeofenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GeofenceRepository")
 */
class Geofence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="The name cannot be blank.")
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="The latitude cannot be blank.")
     * @Assert\Type(type="float", message="The latitude must be a valid float.")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="The longitude cannot be blank.")
     * @Assert\Type(type="float", message="The longitude must be a valid float.")
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="geofences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="geofences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $admin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
