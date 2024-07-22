<?php


namespace Adapter;

use Entity\Odf;
use Unicaen\BddAdmin\Bdd;

interface DataAdapterInterface
{
    public function run(Odf $odf, Bdd $pegase = null): void;



    public function versionMin(): float;



    public function versionMax(): float;
}