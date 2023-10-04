<?php

namespace Lieu\Entity;


interface AdresseInterface
{

    /**
     * @return string|null
     */
    public function getAdresse(bool $withIdentite = true): ?string;



    /**
     * @return string|null
     */
    public function getAdresseIdentite(): ?string;

}