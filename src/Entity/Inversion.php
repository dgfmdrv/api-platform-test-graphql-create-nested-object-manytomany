<?php

namespace App\Entity;


use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use DH\Auditor\Provider\Doctrine\Auditing\Annotation as Audit;

/**
 * @ORM\Entity
 * @ORM\Table(name="inversion")
 * @ApiResource(
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"inversion:write"}},
 *      itemOperations={
 *          "get"
 *      },
 *      collectionOperations={
 *      },
 *      graphql={
 *          "item_query",
 *          "collection_query",
 *          "create",
 *      },
 *      attributes={
 *          "pagination_enabled"=false,
 *      }
 * )
 */
class Inversion
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"read", "inversion:write", "activo:write"})
     * @Assert\NotBlank()
     */
    private $fechaAdquisicion;

    /**
    * @ORM\Column(type="float", nullable=false)
    * @Groups({"read", "inversion:write", "activo:write"})
    */
    private $coste;

    /**
     * @ORM\Column(type="string", unique=false, length=40, nullable=false)
     * @Groups({"read", "inversion:write", "activo:write"})
     * @Assert\NotBlank()
     * @Assert\Length(max=40)
     */
    private $ubicacion;

    /**
     * @ORM\Column(type="string", unique=false, length=40, nullable=false)
     * @Groups({"read", "inversion:write", "activo:write"})
     * @Assert\NotBlank()
     * @Assert\Length(max=40)
     */
    private $perfil;    

    /**
     * @ORM\ManyToOne(targetEntity=Activo::class, inversedBy="inversion")
     * @ORM\JoinColumn(name="activo_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Groups({"read"})
     * @Assert\NotBlank()
     */
    private $activo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaAdquisicion(): ?\DateTimeInterface
    {
        return $this->fechaAdquisicion;
    }

    public function setFechaAdquisicion(\DateTimeInterface $fechaAdquisicion): self
    {
        $this->fechaAdquisicion = $fechaAdquisicion;

        return $this;
    }

    public function getCoste(): ?float
    {
        return $this->coste;
    }

    public function setCoste(float $coste): self
    {
        $this->coste = $coste;

        return $this;
    }

    public function getValorNetoContable(): ?float
    {
        return $this->valorNetoContable;
    }

    public function setValorNetoContable(float $valorNetoContable): self
    {
        $this->valorNetoContable = $valorNetoContable;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

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

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(?string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(string $ubicacion): self
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    public function getPerfil(): ?string
    {
        return $this->perfil;
    }

    public function setPerfil(string $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }

    public function getActivo(): ?Activo
    {
        return $this->activo;
    }

    public function setActivo(?Activo $activo): self
    {
        $this->activo = $activo;

        return $this;
    }



}
