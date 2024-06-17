<?php

namespace Reader;

use unicaen\BddAdmin\Bdd;
use Entity\Odf;

interface ReaderInterface
{
    public function run(Bdd $pegase, Odf $odf): void;



    public function versionMin(): float;



    public function versionMax(): float;
}