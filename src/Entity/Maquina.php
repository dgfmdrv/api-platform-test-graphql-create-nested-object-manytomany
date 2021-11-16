<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="maquina")
 * @ApiResource(
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"write"}},
 *      itemOperations={
 *          "get"
 *      },
 *      collectionOperations={
 *      },
 *      graphql={
 *          "item_query",
 *          "collection_query",
 *      },
 *      attributes={
 *          "pagination_enabled"=false,
 *          "order"={"nombre": "ASC"},
 *      }
 * )
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *         "nombre": "partial",
 *     }
 * )
 * @ApiFilter(
 *     ExistsFilter::class,
 *     properties={
 *         "activo",
 *     }
 * )
 * @ApiFilter(
 *     NumericFilter::class,
 *     properties={
 *         "id"
 *     }
 * )
 */
class Maquina
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=60, nullable=false)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     * @Assert\Length(max=60)
     */
    private $nombre;
     

    /**
     * @ORM\ManyToMany(targetEntity=Activo::class, mappedBy="maquina")
     * @Groups({"read", "write"})
     */
    private $activo;    

    public function __construct()
    {
        $this->activo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Activo[]
     */
    public function getActivo(): Collection
    {
        return $this->activo;
    }

    public function addActivo(Activo $activo): self
    {
        if (!$this->activo->contains($activo)) {
            $this->activo[] = $activo;
            $activo->addMaquina($this);
        }

        return $this;
    }

    public function removeActivo(Activo $activo): self
    {
        if ($this->activo->removeElement($activo)) {
            $activo->removeMaquina($this);
        }

        return $this;
    }
}