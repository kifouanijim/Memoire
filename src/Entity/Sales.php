<?php

namespace App\Entity;

use App\Repository\SalesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalesRepository::class)]
#[ORM\HasLifecycleCallbacks]  // Indique que cette entité a des callbacks Doctrine
class Sales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $product_id = null;

    #[ORM\Column]
    private ?int $nombre = null;  // Quantité de produits vendus

    #[ORM\Column]
    private ?float $prix = null;  // Prix par produit

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]  // Assure que la date ne change pas après création
    private ?\DateTimeImmutable $sale_date = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?string
    {
        return $this->product_id;
    }

    public function setProductId(string $product_id): static
    {
        $this->product_id = $product_id;
        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getSaleDate(): ?\DateTimeImmutable
    {
        return $this->sale_date;
    }

    #[ORM\PrePersist]
    public function setSaleDate(): void
    {
        $this->sale_date = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setTotalPriceFromQuantityAndPrice(): void
    {
        // Calculer le prix total (nombre * prix par produit)
        if ($this->nombre && $this->prix) {
            $this->total_price = $this->nombre * $this->prix;
        }
    }
}
