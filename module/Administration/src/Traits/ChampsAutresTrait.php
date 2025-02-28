<?php

namespace Administration\Traits;

trait ChampsAutresTrait
{

    protected ?string $autre1 = null;
    protected ?string $autre2 = null;
    protected ?string $autre3 = null;
    protected ?string $autre4 = null;
    protected ?string $autre5 = null;



    public function getAutre(int $numero): ?string
    {
        $property = 'autre' . $numero;
        return $this->$property;
    }



    public function setAutre(int $numero, ?string $autre): self
    {
        $property = 'autre' . $numero;
        $this->$property = $autre;
        return $this;
    }



    public function getAutre1(): ?string
    {
        return $this->autre1;
    }



    public function setAutre1(?string $autre1): self
    {
        $this->autre1 = $autre1;
        return $this;
    }



    public function getAutre2(): ?string
    {
        return $this->autre2;
    }



    public function setAutre2(?string $autre2): self
    {
        $this->autre2 = $autre2;
        return $this;
    }



    public function getAutre3(): ?string
    {
        return $this->autre3;
    }



    public function setAutre3(?string $autre3): self
    {
        $this->autre3 = $autre3;
        return $this;
    }



    public function getAutre4(): ?string
    {
        return $this->autre4;
    }



    public function setAutre4(?string $autre4): self
    {
        $this->autre4 = $autre4;
        return $this;
    }



    public function getAutre5(): ?string
    {
        return $this->autre5;
    }



    public function setAutre5(?string $autre5): self
    {
        $this->autre5 = $autre5;
        return $this;
    }


}