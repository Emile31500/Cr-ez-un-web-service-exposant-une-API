<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['project'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project'])]
    private ?string $Name = null;

    #[ORM\Column(length: 14)]
    #[Groups(['project'])]
    private ?string $SIREN = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'Client', targetEntity: Client::class)]
    private Collection $client;

    public function __construct()
    {
        $this->client = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getSIREN(): ?string
    {
        return $this->SIREN;
    }

    public function setSIREN(string $SIREN): self
    {
        $this->SIREN = $SIREN;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setClient($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getClient() === $this) {
                $client->setClient(null);
            }
        }

        return $this;
    }
}
