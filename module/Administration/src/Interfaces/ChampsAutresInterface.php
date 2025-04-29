<?php

namespace Administration\Interfaces;

interface ChampsAutresInterface
{
    public function getAutre(int $numero): ?string;



    public function setAutre(int $numero, ?string $autre): self;



    public function getAutre1(): ?string;



    public function setAutre1(?string $autre1): self;



    public function getAutre2(): ?string;



    public function setAutre2(?string $autre2): self;



    public function getAutre3(): ?string;



    public function setAutre3(?string $autre3): self;



    public function getAutre4(): ?string;



    public function setAutre4(?string $autre4): self;



    public function getAutre5(): ?string;



    public function setAutre5(?string $autre5): self;
}