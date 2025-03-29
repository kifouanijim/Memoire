<?php

// src/Entity/Reviews.php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
#[ORM\HasLifecycleCallbacks] // Active les événements Doctrine
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $IMEI = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::JSON)]
    private array $niveau = []; // Correction du nom

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIMEI(): ?int
    {
        return $this->IMEI;
    }

    public function setIMEI(int $IMEI): static
    {
        $this->IMEI = $IMEI;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getNiveau(): array
    {
        return $this->niveau;
    }

    public function setNiveau($niveau): static
    {
        // Vérification si niveau est un tableau et le convertir si nécessaire
        if (!is_array($niveau)) {
            $niveau = [$niveau];
        }

        $this->niveau = $niveau;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist] // Automatiquement défini avant insertion
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
