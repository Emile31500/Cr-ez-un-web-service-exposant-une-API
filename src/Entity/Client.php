<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['project'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['project'])]
    #[Length(min: 1, max: 180, minMessage: "Le nom d'utilisateur doit contenir {{ limit }} caractères minimum", maxMessage: "Le nom d'utilisateur ne doit pas contenir plus de {{ limit }}")] 
    #[NotBlank(message: "L'email est obligatoire")]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['project'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project'])]
    #[Assert\NotBlank(message: "Le nom d'utilisateur est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom d'utilisateur doit contenir {{ limit }} caractères minimum", maxMessage: "Le nom d'utilisateur ne doit pas contenir plus de {{ limit }}")] 
    private ?string $clientName = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['project'])]
    private ?string $phoneNumber = null;

    #[ORM\ManyToOne(inversedBy: 'Projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['project'])]
    private ?Project $project = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this Client.
     *
     * @see ClientInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @see ClientInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every Client at least has ROLE_Client
        $roles[] = 'ROLE_Client';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedClientInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see ClientInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the Client, clear it here
        // $this->plainPassword = null;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
