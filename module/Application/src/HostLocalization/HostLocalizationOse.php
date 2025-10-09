<?php

namespace Application\HostLocalization;

use Unicaen\Framework\Application\Application;
use UnicaenApp\HostLocalization\HostLocalizationInterface;

class HostLocalizationOse implements HostLocalizationInterface
{
    public function inEtablissement(): bool
    {
        $inEtablissement = Application::getInstance()->config()['global']['inEtablissement'] ?? true;
        if ($inEtablissement instanceof HostLocalizationInterface) {
            $inEtablissement = $inEtablissement->inEtablissement();
        }
        if ($inEtablissement instanceof \Closure) {
            $inEtablissement = $inEtablissement();
        }

        return (bool)$inEtablissement;
    }

}