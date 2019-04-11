<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 */
class Vehicle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $stock_no;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $vin;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $new_used;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $veh_year;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $veh_make;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $veh_class;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $veh_model;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $veh_trim;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $trans_type;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $wheelbase;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ext_color;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $int_color;

    /**
     * @ORM\Column(type="integer")
     */
    private $miles;

    /**
     * @ORM\Column(type="integer")
     */
    private $msrp_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $list_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $days;

    /**
     * @ORM\Column(type="integer")
     */
    private $cpo_flag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStockNo(): ?string
    {
        return $this->stock_no;
    }

    public function setStockNo(string $stock_no): self
    {
        $this->stock_no = $stock_no;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(string $vin): self
    {
        $this->vin = $vin;

        return $this;
    }

    public function getNewUsed(): ?string
    {
        return $this->new_used;
    }

    public function setNewUsed(string $new_used): self
    {
        $this->new_used = $new_used;

        return $this;
    }

    public function getVehYear(): ?string
    {
        return $this->veh_year;
    }

    public function setVehYear(string $veh_year): self
    {
        $this->veh_year = $veh_year;

        return $this;
    }

    public function getVehMake(): ?string
    {
        return $this->veh_make;
    }

    public function setVehMake(string $veh_make): self
    {
        $this->veh_make = $veh_make;

        return $this;
    }

    public function getVehClass(): ?string
    {
        return $this->veh_class;
    }

    public function setVehClass(?string $veh_class): self
    {
        $this->veh_class = $veh_class;

        return $this;
    }

    public function getVehModel(): ?string
    {
        return $this->veh_model;
    }

    public function setVehModel(?string $veh_model): self
    {
        $this->veh_model = $veh_model;

        return $this;
    }

    public function getVehTrim(): ?string
    {
        return $this->veh_trim;
    }

    public function setVehTrim(?string $veh_trim): self
    {
        $this->veh_trim = $veh_trim;

        return $this;
    }

    public function getTransType(): ?string
    {
        return $this->trans_type;
    }

    public function setTransType(?string $trans_type): self
    {
        $this->trans_type = $trans_type;

        return $this;
    }

    public function getWheelbase(): ?string
    {
        return $this->wheelbase;
    }

    public function setWheelbase(?string $wheelbase): self
    {
        $this->wheelbase = $wheelbase;

        return $this;
    }

    public function getExtColor(): ?string
    {
        return $this->ext_color;
    }

    public function setExtColor(?string $ext_color): self
    {
        $this->ext_color = $ext_color;

        return $this;
    }

    public function getIntColor(): ?string
    {
        return $this->int_color;
    }

    public function setIntColor(?string $int_color): self
    {
        $this->int_color = $int_color;

        return $this;
    }

    public function getMiles(): ?int
    {
        return $this->miles;
    }

    public function setMiles(int $miles): self
    {
        $this->miles = $miles;

        return $this;
    }

    public function getMsrpPrice(): ?int
    {
        return $this->msrp_price;
    }

    public function setMsrpPrice(int $msrp_price): self
    {
        $this->msrp_price = $msrp_price;

        return $this;
    }

    public function getListPrice(): ?int
    {
        return $this->list_price;
    }

    public function setListPrice(int $list_price): self
    {
        $this->list_price = $list_price;

        return $this;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getCpoFlag(): ?int
    {
        return $this->cpo_flag;
    }

    public function setCpoFlag(int $cpo_flag): self
    {
        $this->cpo_flag = $cpo_flag;

        return $this;
    }
}
