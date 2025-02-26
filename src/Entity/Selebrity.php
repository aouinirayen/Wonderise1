<?php

namespace App\Entity;

use App\Repository\SelebrityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SelebrityRepository::class)]
class Selebrity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['celebrity:read', 'country:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['celebrity:read', 'country:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['celebrity:read', 'country:read'])]
    private ?string $work = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['celebrity:read', 'country:read'])]
    private ?string $img = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['celebrity:read', 'country:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['celebrity:read', 'country:read'])]
    private ?string $job = null;

    #[ORM\ManyToOne(inversedBy: 'celebrities')]
    #[Groups(['celebrity:read'])]
    private ?Country $country = null;

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

    public function getWork(): ?string
    {
        return $this->work;
    }

    public function setWork(?string $work): static
    {
        $this->work = $work;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
