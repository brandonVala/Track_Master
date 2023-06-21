<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="This email is already in use.")
 */
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
     * @Assert\NotBlank(message="The name cannot be blank.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="The email cannot be blank.")
     * @Assert\Email(message="The email is not valid.")
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
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Geofence", mappedBy="user", cascade={"persist", "remove"})
    * @Assert\Valid()
    */
    private $geofences;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user", cascade={"persist", "remove"})
    * @Assert\Valid()
    */
    private $notifications;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="user", cascade={"persist", "remove"})
    * @Assert\Valid()
    */
    private $locations;


    public function __construct()
    {
        $this->registeredAt = new \DateTime();
        $this->emailVerified = false;
        $this->isAdmin = false;
        $this->geofences = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    public function setPassword(string $password, UserPasswordEncoderInterface $encoder): self
    {
        $encodedPassword = $encoder->encodePassword($this, $password);
        $this->password = $encodedPassword;

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

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // Ensure that every user has the "ROLE_USER" role by default
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Geofence[]
     */
    public function getGeofences(): Collection
    {
        return $this->geofences;
    }

    public function addGeofence(Geofence $geofence): self
    {
        if (!$this->geofences->contains($geofence)) {
            $this->geofences[] = $geofence;
            $geofence->setUser($this);
        }

        return $this;
    }

    public function removeGeofence(Geofence $geofence): self
    {
        if ($this->geofences->contains($geofence)) {
            $this->geofences->removeElement($geofence);
            // set the owning side to null (unless already changed)
            if ($geofence->getUser() === $this) {
                $geofence->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $location === null ? null : $this;
        if ($newUser !== $location->getUser()) {
            $location->setUser($newUser);
        }

        return $this;
    }

    // Methods required for implementing UserInterface

    public function getSalt()
    {
        // No salt is needed as passwords are hashed using a secure algorithm.
        return null;
    }

    public function getUsername()
    {
        // In this example, the username is the email.
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        // No action is needed here as we don't store plain-text credentials.
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}