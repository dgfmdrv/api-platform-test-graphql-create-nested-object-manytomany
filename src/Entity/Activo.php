<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use DH\Auditor\Provider\Doctrine\Auditing\Annotation as Audit;

/**
 * @ORM\Entity
 * @ORM\Table(name="activo")
 * @ApiResource(
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"activo:write"}},
 *      itemOperations={
 *          "get",
 *      },
 *      collectionOperations={
 *      },
 *      graphql={
 *          "item_query",
 *          "collection_query",
 *          "create",
 *          "update",
 *      },
 *      attributes={
 *          "pagination_enabled"=false,
 *          "order"={"nombre": "ASC"},
 *      },
 * )
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *         "nombre": "partial",
 *         "referencia": "partial",
 *     }
 * )
 */
class Activo
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read"})
     */
    private $id;
    
    /**
     * 
     * @ORM\Column(type="string", unique=false, length=80, nullable=false)
     * @Groups({"read", "activo:write"})
     * @Assert\NotBlank()
     * @Assert\Length(max=80)
     */
    private $nombre;

    /**
     * 
     * @ORM\Column(type="string", unique=true, length=40, nullable=false)
     * @Groups({"read", "activo:write"})
     * @Assert\NotBlank()
     * @Assert\Length(max=40)
     */
    private $referencia;  
    
    /**
     * @ORM\OneToMany(targetEntity=Inversion::class, mappedBy="activo", cascade={"persist"})
     * @ORM\OrderBy({"fechaAdquisicion" = "ASC"})
     * @Groups({"read", "activo:write"})
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "Debe especificar al menos una inversión"
     * ) 
     * @Assert\Valid()
     */
    private $inversion;
    
    /**
     * @ORM\ManyToMany(targetEntity=Maquina::class, inversedBy="activo", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinTable(name="activo_maquina",
     *      joinColumns={@ORM\JoinColumn(name="activo_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="maquina_id", referencedColumnName="id", unique=true, onDelete="CASCADE")}
     * )
     * @Groups({"read", "activo:write"})
     * @Assert\Valid()
     */    
    private $maquina;

    public function __construct()
    {
        $this->inversion = new ArrayCollection();
        $this->maquina = new ArrayCollection();
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

    public function getReferencia(): ?string
    {
        return $this->referencia;
    }

    public function setReferencia(string $referencia): self
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * @return Collection|Inversion[]
     */
    public function getInversion(): Collection
    {
        return $this->inversion;
    }

    public function addInversion(Inversion $inversion): self
    {
        if (!$this->inversion->contains($inversion)) {
            $this->inversion[] = $inversion;
            $inversion->setActivo($this);
        }

        return $this;
    }

    public function removeInversion(Inversion $inversion): self
    {
        if ($this->inversion->removeElement($inversion)) {
            // set the owning side to null (unless already changed)
            if ($inversion->getActivo() === $this) {
                $inversion->setActivo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Maquina[]
     */
    public function getMaquina(): Collection
    {
        return $this->maquina;
    }

    public function addMaquina(Maquina $maquina): self
    {
        if (!$this->maquina->contains($maquina)) {
            $this->maquina[] = $maquina;
        }

        return $this;
    }

    public function removeMaquina(Maquina $maquina): self
    {
        $this->maquina->removeElement($maquina);

        return $this;
    }


}
