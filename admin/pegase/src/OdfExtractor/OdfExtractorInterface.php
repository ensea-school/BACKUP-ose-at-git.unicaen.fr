<?php

namespace OdfExtractor;

use BddAdmin\Bdd;
use Entity\Odf;

interface OdfExtractorInterface
{
    public function extract(Bdd $ose, Odf $odf): void;



    public function versionMin(): float;



    public function versionMax(): float;
}