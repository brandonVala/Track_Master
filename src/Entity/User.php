<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// Importaciones adicionales
use App\Entity\Admin;
use App\Entity\Location;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="This email is already in use.")
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emailVerified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="user", cascade={"persist", "remove"})
     */
    private $locations;

    public function __construct()
    {
        $this->registeredAt = new \DateTime();
        $this->emailVerified = false;
        $this->locations = new ArrayCollection();
    }

    // Getters and Setters

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): self
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

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


    /**
     * @return Collection|Location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setUser($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getUser() === $this) {
                $location->setUser(null);
            }
        }

        return $this;
    }

    // Métodos requeridos para implementar UserInterface

    public function getSalt()
    {
        // No es necesario un salt ya que las contraseñas se hashean utilizando un algoritmo seguro.
        return null;
    }

    public function getUsername()
    {
        // En este ejemplo, el nombre de usuario es el email.
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        // No es necesario realizar ninguna acción aquí, ya que no almacenamos las credenciales en texto plano.
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getRoles(): array
    {
        // En este ejemplo, el usuario solo tiene un rol "ROLE_USER".
        return ['ROLE_USER'];
    }
}
