<?php

namespace Application\HostLocalization;

use UnicaenApp\HostLocalization\HostLocalizationInterface;

class HostLocalizationOse implements HostLocalizationInterface
{
    public function inEtablissement(): bool
    {
        $inEtablissement = \AppConfig::get('global', 'inEtablissement', true);
        if ($inEtablissement instanceof HostLocalizationInterface) {
            $inEtablissement = $inEtablissement->inEtablissement();
        }
        if (is_callable($inEtablissement)) {
            $inEtablissement = $inEtablissement();
        }

        return (bool)$inEtablissement;
    }

}