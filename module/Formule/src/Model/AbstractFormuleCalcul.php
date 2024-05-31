<?php

namespace Formule\Model;

use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Unicaen\OpenDocument\Calc;


class AbstractFormuleCalcul
{
    const RESCOLS = [
        'HeuresServiceFi',
        'HeuresServiceFa',
        'HeuresServiceFc',
        'HeuresServiceReferentiel',
        'HeuresComplFi',
        'HeuresComplFa',
        'HeuresComplFc',
        'HeuresComplReferentiel',
        'HeuresPrimes',
        'HeuresNonPayableFi',
        'HeuresNonPayableFa',
        'HeuresNonPayableFc',
        'HeuresNonPayableReferentiel',
    ];

    protected Formule $formule;
    protected FormuleIntervenant $intervenant;

    /** @var FormuleVolumeHoraire[] */
    protected array $volumesHoraires = [];
    protected array $cache = [];

    protected int $ligne = 0;



    protected function setLigne(int $ligne)
    {
        $this->ligne = $ligne;
    }



    protected function min(string $col): float
    {
        $result = null;
        foreach ($this->volumesHoraires as $l => $vh) {
            $cr = $this->c($col, $l);
            if ($result === null || $cr < $result) {
                $result = $cr;
            }
        }

        return $result;
    }



    protected function max(string $col): float
    {
        $result = null;
        foreach ($this->volumesHoraires as $l => $vh) {
            $cr = $this->c($col, $l);
            if ($result === null || $cr > $result) {
                $result = $cr;
            }
        }

        return $result;
    }



    protected function somme(string $col): float
    {
        $result = 0;
        foreach ($this->volumesHoraires as $l => $vh) {
            $cr = $this->c($col, $l);
            $result += $cr;
        }

        return $result;
    }



    protected function c(string $name, int $l)
    {
        if (!isset($this->cache['vh'][$l][$name])) {
            $cname = 'c_' . $name;
            $this->cache['vh'][$l][$name] = $this->$cname($l);
        }

        return $this->cache['vh'][$l][$name];
    }



    protected function cg(string $name)
    {
        if (!isset($this->cache['global'][$name])) {
            $cname = 'c_' . $name;
            $this->cache['global'][$name] = $this->$cname();
        }

        return $this->cache['global'][$name];
    }



    protected function intervenant(): FormuleIntervenant
    {
        return $this->intervenant;
    }



    protected function volumeHoraire(int $l): FormuleVolumeHoraire
    {
        return $this->volumesHoraires[$l];
    }



    public function calculer(FormuleIntervenant $intervenant, Formule $formule): array
    {
        $this->intervenant = $intervenant;
        $this->volumesHoraires = $intervenant->getVolumesHoraires()->toArray();
        $this->formule = $formule;
        $this->cache = [
            'vh'     => [],
            'global' => [],
        ];

        foreach ($this->volumesHoraires as $l => $volumesHoraire) {
            foreach (self::RESCOLS as $resCol) {
                $cellColPos = $this->formule->{'get' . $resCol . 'Col'}();
                if ($cellColPos) {
                    $val = $this->c($cellColPos, $l);
                } else {
                    $val = 0.0;
                }
                $volumesHoraire->{'set' . $resCol}($val);
            }
        }

        foreach ($this->cache['vh'] as $vhi => $vhd) {
            uksort($this->cache['vh'][$vhi], function ($a, $b) {
                return (int)(Calc::letterToNumber($a) - Calc::letterToNumber($b));
            });
        }

        uksort($this->cache['global'], function ($a, $b) {
            $aCoords = Calc::cellNameToCoords($a);
            $bCoords = Calc::cellNameToCoords($b);

            if ($aCoords['col'] == $bCoords['col']) {
                return $aCoords['row'] - $bCoords['row'];
            } else {
                return $aCoords['col'] - $bCoords['col'];
            }
        });

        return $this->cache;
    }
}