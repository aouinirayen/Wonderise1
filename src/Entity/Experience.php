<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Oops ! Le titre est requis pour partager votre expérience')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le titre est trop court ! Il doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le titre est trop long ! Il ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]+$/',
        message: 'Le titre ne peut contenir que des lettres, des chiffres et des espaces'
    )]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Partagez votre histoire ! La description ne peut pas être vide')]
    #[Assert\Length(
        min: 10,
        max: 500,
        minMessage: 'Votre description est un peu courte ! Ajoutez au moins {{ limit }} caractères',
        maxMessage: 'Votre description est trop longue ! Elle ne doit pas dépasser {{ limit }} caractères'
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: ' L\'URL de l\'image est requise')]
    #[Assert\Url(message: 'Hmm... Cette URL ne semble pas valide. Assurez-vous qu\'elle commence par http:// ou https://')]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le lieu est obligatoire !')]

    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom du lieu est trop long !'
    )]
    private ?string $lieu = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom de la catégorie doit etre remplie')]

    #[Assert\Length(
        max: 50,
        maxMessage: 'Le nom de la catégorie est obligatoire ! '
    )]
    private ?string $categorie = null;

    #[ORM\Column(nullable: true)]

    #[Assert\NotBlank(message: 'id est un champs obligatoire')]
    private ?string $id_client = null;

    #[ORM\Column(type: 'datetime')]

    #[Assert\NotBlank(message: 'Spécifier la date de votre expérience !')]
    #[Assert\Type(
        type: "\DateTimeInterface",
        message: 'La date n\'est pas dans un format valide'
    )]
    private ?\DateTimeInterface $date = null;
    
    #[Assert\NotBlank(message: 'N\'oubliez pas de spécifier la date de votre expérience !')]

    #[ORM\Column(name: 'date_creation', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;


    #[ORM\OneToMany(mappedBy: 'experience', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getIdClient(): ?string
    {
        return $this->id_client;
    }

    public function setIdClient(string $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setExperience($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            if ($commentaire->getExperience() === $this) {
                $commentaire->setExperience(null);
            }
        }

        return $this;
    }
}
