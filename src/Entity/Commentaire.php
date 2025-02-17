<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private string $texte;

    #[ORM\ManyToOne(targetEntity: Experience::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private Experience $experience;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTexte(): string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;
        return $this;
    }

    // Getter et Setter pour experience
    public function getExperience(): Experience
    {
        return $this->experience;
    }

    public function setExperience(Experience $experience): self
    {
        $this->experience = $experience;
        return $this;
    }
}
