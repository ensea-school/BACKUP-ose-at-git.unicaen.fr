<?php

namespace Application\HostLocalization;

use UnicaenApp\HostLocalization\HostLocalizationInterface;

class HostLocalizationOse implements HostLocalizationInterface
{
    public function inEtablissement(): bool
    {
        $inEtablissement = \OseAdmin::instance()->config()->get('global', 'inEtablissement', true);
        if ($inEtablissement instanceof HostLocalizationInterface) {
            $inEtablissement = $inEtablissement->inEtablissement();
        }
        if ($inEtablissement instanceof \Closure) {
            $inEtablissement = $inEtablissement();
        }

        return (bool)$inEtablissement;
    }

}