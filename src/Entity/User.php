<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Behavior\Blamable;
use App\Behavior\Timestampable;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Blamable;
    use Timestampable;
    
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_COORDINATOR ='ROLE_COORDINATOR';
    const ROLE_TUTOR ='ROLE_TUTOR';
    const ROLE_STUDENT= 'ROLE_STUDENT';

    const ROLES = [
        self::ROLE_ADMIN   => 'Administrator',
        self::ROLE_MANAGER => 'Manager',
        self::ROLE_USER    => 'User',
        self::ROLE_COORDINATOR => 'Coordinator',
        self::ROLE_TUTOR => 'Tutor',
        self::ROLE_STUDENT => 'Student'
    ];

    const ROLES_COORDINATOR = [
        self::ROLE_USER    => 'User',
        self::ROLE_COORDINATOR => 'Coordinator',
        self::ROLE_TUTOR => 'Tutor',      
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(min=8, groups={"registration"})
     */
    private ?string $plainPassword = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $forgottenPasswordCode;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $forgottenPasswordTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $impersonateCode;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     */
    private $company;


    public function getId (): ?int
    {
        return $this->id;
    }


    public function getEmail (): ?string
    {
        return $this->email;
    }


    public function setEmail (string $email): self
    {
        $this->email = $email;

        return $this;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier (): string
    {
        return (string)$this->email;
    }


    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername (): string
    {
        return (string)$this->email;
    }


    /**
     * @see UserInterface
     */
    public function getRoles (): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    public function setRoles (array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword (): string
    {
        return $this->password;
    }


    public function setPassword (string $password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt (): ?string
    {
        return null;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials ()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getPlainPassword ()
    {
        return $this->plainPassword;
    }


    public function setPlainPassword ($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }


    public function addRole (string $role): self
    {
        $this->roles[] = $role;
        $this->roles   = array_unique($this->roles);

        return $this;
    }


    public function hasRole ($role)
    {
        return in_array($role, $this->getRoles());
    }


    public function isAdmin ()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }


    public function isManager ()
    {
        return $this->hasRole(self::ROLE_MANAGER);
    }


    public function isUser ()
    {
        return $this->hasRole(self::ROLE_USER);
    }


    public function getFirstName (): ?string
    {
        return $this->firstName;
    }


    public function setFirstName (?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }


    public function getLastName (): ?string
    {
        return $this->lastName;
    }


    public function setLastName (?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    public function getForgottenPasswordCode (): ?string
    {
        return $this->forgottenPasswordCode;
    }


    public function setForgottenPasswordCode (?string $forgottenPasswordCode): self
    {
        $this->forgottenPasswordCode = $forgottenPasswordCode;

        return $this;
    }


    public function getForgottenPasswordTime (): ?\DateTimeInterface
    {
        return $this->forgottenPasswordTime;
    }


    public function setForgottenPasswordTime (?\DateTimeInterface $forgottenPasswordTime): self
    {
        $this->forgottenPasswordTime = $forgottenPasswordTime;

        return $this;
    }


    public function isForgottenPasswordTimedOut ($timedOutSeconds = 3600)
    {
        if (!$this->forgottenPasswordTime) return true;

        $elapsedTime = time() - $this->forgottenPasswordTime->getTimestamp();
        return $elapsedTime > $timedOutSeconds;
    }

    public function getImpersonateCode(): ?string
    {
        return $this->impersonateCode;
    }

    public function setImpersonateCode(?string $impersonateCode): self
    {
        $this->impersonateCode = $impersonateCode;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
   
}
