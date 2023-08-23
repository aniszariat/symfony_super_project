<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    use CreatedAtTrait;
    use SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categories = null;


    #[ORM\OneToMany(mappedBy: 'products', targetEntity: Images::class, orphanRemoval: true)]
    private $images;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: OdersDetails::class)]
    private Collection $odersDetails;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->odersDetails = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }
    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProducts($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProducts() === $this) {
                $image->setProducts(null);
            }
        }

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
            $odersDetail->setProducts($this);
        }

        return $this;
    }

    public function removeOdersDetail(OdersDetails $odersDetail): static
    {
        if ($this->odersDetails->removeElement($odersDetail)) {
            // set the owning side to null (unless already changed)
            if ($odersDetail->getProducts() === $this) {
                $odersDetail->setProducts(null);
            }
        }

        return $this;
    }
}
