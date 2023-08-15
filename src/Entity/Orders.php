<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait ;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    use CreatedAtTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $reference = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Coupons $coupons = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;


    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OdersDetails::class, orphanRemoval: true)]
    private Collection $odersDetails;

    public function __construct()
    {
        $this->odersDetails = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCoupons(): ?Coupons
    {
        return $this->coupons;
    }

    public function setCoupons(?Coupons $coupons): static
    {
        $this->coupons = $coupons;

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
     * @return Collection<int, OdersDetails>
     */
    public function getOdersDetails(): Collection
    {
        return $this->odersDetails;
    }

    public function addOdersDetail(OdersDetails $odersDetail): static
    {
        if (!$this->odersDetails->contains($odersDetail)) {
            $this->odersDetails->add($odersDetail);
            $odersDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOdersDetail(OdersDetails $odersDetail): static
    {
        if ($this->odersDetails->removeElement($odersDetail)) {
            // set the owning side to null (unless already changed)
            if ($odersDetail->getOrders() === $this) {
                $odersDetail->setOrders(null);
            }
        }

        return $this;
    }
}
