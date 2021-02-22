<?php

namespace App\Entity;

use App\Repository\ShipmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShipmentRepository::class)
 */
class Shipment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cityFrom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cityTo;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currency;


    public function getCityFrom(): ?string
    {
        return $this->cityFrom;
    }

    public function setCityFrom(?string $cityFrom): self
    {
        $this->cityFrom = $cityFrom;

        return $this;
    }

    public function getCityTo(): ?string
    {
        return $this->cityTo;
    }

    public function setCityTo(?string $cityTo): self
    {
        $this->cityTo = $cityTo;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
