<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\StatusEnum;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'objet est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'objet ne peut pas dépasser 255 caractères."
    )]
    private ?string $Objet = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La description ne peut pas dépasser 255 caractères."
    )]
    private ?string $Description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "La date est obligatoire.")]
    private ?\DateTimeInterface $Date = null;
    #[ORM\Column(enumType: StatusEnum::class)]
    #[Assert\NotNull(message: "Le statut est obligatoire.")]
    private StatusEnum $status = StatusEnum::Envoyee; // État par défaut
    

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reclamations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Reponse>
     */
    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'reclamation')]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->Date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->Objet;
    }

    public function setObjet(string $Objet): static
    {
        $this->Objet = $Objet;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;
        return $this;
    }


    public function getStatus(): string
    {
        return $this->status->value;  // 🔥 Convertit l'Enum en string
    }
    
    public function setStatus(StatusEnum|string $status): self
    {
        if (is_string($status)) {
            $statusEnum = StatusEnum::tryFrom($status);
            if (!$statusEnum) {
                throw new \InvalidArgumentException("Statut invalide : $status");
            }
            $status = $statusEnum;
        }
        $this->status = $status;
        return $this;
    }
    


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setReclamation($this);
        }
        return $this;
    }

    public function removeReponse(Reponse $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }
        return $this;
    }

}