<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['country:read', 'monument:read', 'food:read', 'art:read', 'celebrity:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['country:read', 'monument:read', 'food:read', 'art:read', 'celebrity:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['country:read'])]
    private ?string $img = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['country:read'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Monument::class, cascade: ['persist', 'remove'])]
    #[Groups(['country:read'])]
    private Collection $monuments;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: TraditionalFood::class, cascade: ['persist', 'remove'])]
    #[Groups(['country:read'])]
    private Collection $traditionalFoods;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Art::class, cascade: ['persist', 'remove'])]
    #[Groups(['country:read'])]
    private Collection $arts;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Selebrity::class, cascade: ['persist', 'remove'])]
    #[Groups(['country:read'])]
    private Collection $celebrities;

    public function __construct()
    {
        $this->monuments = new ArrayCollection();
        $this->traditionalFoods = new ArrayCollection();
        $this->arts = new ArrayCollection();
        $this->celebrities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, Monument>
     */
    public function getMonuments(): Collection
    {
        return $this->monuments;
    }

    public function addMonument(Monument $monument): static
    {
        if (!$this->monuments->contains($monument)) {
            $this->monuments->add($monument);
            $monument->setCountry($this);
        }
        return $this;
    }

    public function removeMonument(Monument $monument): static
    {
        if ($this->monuments->removeElement($monument)) {
            if ($monument->getCountry() === $this) {
                $monument->setCountry(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, TraditionalFood>
     */
    public function getTraditionalFoods(): Collection
    {
        return $this->traditionalFoods;
    }

    public function addTraditionalFood(TraditionalFood $traditionalFood): static
    {
        if (!$this->traditionalFoods->contains($traditionalFood)) {
            $this->traditionalFoods->add($traditionalFood);
            $traditionalFood->setCountry($this);
        }
        return $this;
    }

    public function removeTraditionalFood(TraditionalFood $traditionalFood): static
    {
        if ($this->traditionalFoods->removeElement($traditionalFood)) {
            if ($traditionalFood->getCountry() === $this) {
                $traditionalFood->setCountry(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Art>
     */
    public function getArts(): Collection
    {
        return $this->arts;
    }

    public function addArt(Art $art): static
    {
        if (!$this->arts->contains($art)) {
            $this->arts->add($art);
            $art->setCountry($this);
        }
        return $this;
    }

    public function removeArt(Art $art): static
    {
        if ($this->arts->removeElement($art)) {
            if ($art->getCountry() === $this) {
                $art->setCountry(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Selebrity>
     */
    public function getCelebrities(): Collection
    {
        return $this->celebrities;
    }

    public function addCelebrity(Selebrity $celebrity): static
    {
        if (!$this->celebrities->contains($celebrity)) {
            $this->celebrities->add($celebrity);
            $celebrity->setCountry($this);
        }
        return $this;
    }

    public function removeCelebrity(Selebrity $celebrity): static
    {
        if ($this->celebrities->removeElement($celebrity)) {
            if ($celebrity->getCountry() === $this) {
                $celebrity->setCountry(null);
            }
        }
        return $this;
    }
}
