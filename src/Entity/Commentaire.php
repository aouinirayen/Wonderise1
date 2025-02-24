<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[ORM\Table(name: "commentaire")]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le commentaire ne peut pas être vide")]
    #[Assert\Length(
        min: 5,
        max: 1000,
        minMessage: "Le commentaire doit faire au moins 5 caractères",
        maxMessage: "Le commentaire ne peut pas dépasser 1000 caractères"
    )]
    private ?string $contenu = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'auteur ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom doit faire au moins 2 caractères",
        maxMessage: "Le nom ne peut pas dépasser 50 caractères"
    )]
    private ?string $auteur = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $date = null;

    #[ORM\Column(name: 'date_creation', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModification = null;

    #[ORM\ManyToOne(targetEntity: Experience::class, inversedBy: "commentaires")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Experience $experience = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $sentiment = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $sentimentScore = null;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->dateCreation = new \DateTime();
        $this->dateModification = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;
        return $this;
    }

    public function getSentiment(): ?string
    {
        return $this->sentiment;
    }

    public function setSentiment(?string $sentiment): self
    {
        $this->sentiment = $sentiment;
        return $this;
    }

    public function getSentimentScore(): ?float
    {
        return $this->sentimentScore;
    }

    public function setSentimentScore(?float $score): self
    {
        $this->sentimentScore = $score;
        return $this;
    }
}
