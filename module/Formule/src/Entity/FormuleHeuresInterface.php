<?php

namespace Formule\Entity;

interface FormuleHeuresInterface
{
    public function getHeuresServiceFi(): float;



    public function getHeuresServiceFa(): float;



    public function getHeuresServiceFc(): float;



    public function getHeuresServiceReferentiel(): float;



    public function getHeuresNonPayableFi(): float;



    public function getHeuresNonPayableFa(): float;



    public function getHeuresNonPayableFc(): float;



    public function getHeuresNonPayableReferentiel(): float;



    public function getHeuresComplFi(): float;



    public function getHeuresComplFa(): float;



    public function getHeuresComplFc(): float;



    public function getHeuresComplReferentiel(): float;



    public function getHeuresPrimes(): float;

}