<?php


namespace Adapter;

use Entity\Odf;

interface DataAdapterInterface
{
    public function run(Odf $odf): void;



    public function versionMin(): float;



    public function versionMax(): float;
}