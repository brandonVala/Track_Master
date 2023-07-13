<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @UniqueEntity(fields={"email"}, message="This email is already in use.")
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Admin implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="The phone number cannot be blank.")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilePicture;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginAt;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $notificationSettings = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $accessPermissions = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $actionHistory = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $statistics = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $integrations = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $areaOfResponsibility;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="admin")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Geofence::class, mappedBy="admin", cascade={"persist"})
     */
    private $geofences;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="admin", cascade={"persist"})
     */
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="admin", cascade={"persist"})
     */
    private $notifications;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    public function __construct()
    {
        $this->registeredAt = new \DateTime();
        $this->emailVerified = false;
        $this->users = new ArrayCollection();
        $this->geofences = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->isAdmin = true;
    }

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

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (!in_array('ROLE_ADMIN', $roles, true)) {
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getNotificationSettings(): ?array
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(?array $notificationSettings): self
    {
        $this->notificationSettings = $notificationSettings;

        return $this;
    }

    public function getAccessPermissions(): ?array
    {
        return $this->accessPermissions;
    }

    public function setAccessPermissions(?array $accessPermissions): self
    {
        $this->accessPermissions = $accessPermissions;

        return $this;
    }

    public function getActionHistory(): ?array
    {
        return $this->actionHistory;
    }

    public function setActionHistory(?array $actionHistory): self
    {
        $this->actionHistory = $actionHistory;

        return $this;
    }

    public function getStatistics(): ?array
    {
        return $this->statistics;
    }

    public function setStatistics(?array $statistics): self
    {
        $this->statistics = $statistics;

        return $this;
    }

    public function getIntegrations(): ?array
    {
        return $this->integrations;
    }

    public function setIntegrations(?array $integrations): self
    {
        $this->integrations = $integrations;

        return $this;
    }

    public function getAreaOfResponsibility(): ?string
    {
        return $this->areaOfResponsibility;
    }

    public function setAreaOfResponsibility(?string $areaOfResponsibility): self
    {
        $this->areaOfResponsibility = $areaOfResponsibility;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUsers(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAdmin($this);
        }

        return $this;
    }

    public function removeUsers(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            if ($user->getAdmin() === $this) {
                $user->setAdmin(null);
            }
        }

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
            $geofence->setAdmin($this);
        }

        return $this;
    }

    public function removeGeofence(Geofence $geofence): self
    {
        if ($this->geofences->contains($geofence)) {
            $this->geofences->removeElement($geofence);
            if ($geofence->getAdmin() === $this) {
                $geofence->setAdmin(null);
            }
        }

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
            $location->setAdmin($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            if ($location->getAdmin() === $this) {
                $location->setAdmin(null);
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
            $notification->setAdmin($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            if ($notification->getAdmin() === $this) {
                $notification->setAdmin(null);
            }
        }

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
