<?php

namespace Formule\Model;

use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Unicaen\OpenDocument\Calc;
use Unicaen\OpenDocument\Calc\Sheet;

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
    protected array $debug = [];

    protected int $ligne = 0;



    protected function setLigne(int $ligne)
    {
        $this->ligne = $ligne;
    }



    protected function min(string $col): float
    {
        $result = null;
        foreach ($this->intervenant->getVolumesHoraires() as $l => $vh) {
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
        foreach ($this->intervenant->getVolumesHoraires() as $l => $vh) {
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
        foreach ($this->intervenant->getVolumesHoraires() as $l => $vh) {
            $cr = $this->c($col, $l);
            $result += $cr;
        }

        return $result;
    }



    protected function c(string $name, int $l)
    {
        $cname = 'c_' . $name;
        $resultat = $this->$cname($l);
        $this->debug['vh'][$l][$name] = $resultat;
        //$this->debug['global'][$name] = $resultat;

        return $resultat;
    }



    protected function cg(string $name)
    {
        $cname = 'c_' . $name;
        $resultat = $this->$cname();

        $this->debug['global'][$name] = $resultat;

        return $resultat;
    }



    protected function intervenant(): FormuleIntervenant
    {
        return $this->intervenant;
    }



    protected function volumeHoraire(int $l): FormuleVolumeHoraire
    {
        return $this->intervenant->getVolumesHoraires()->get($l);
    }



    public function calculer(FormuleIntervenant $intervenant, Formule $formule): array
    {
        $this->intervenant = $intervenant;
        $this->formule = $formule;
        $this->debug = [
            'vh'     => [],
            'global' => [],
        ];

        $volumesHoraires = $this->intervenant->getVolumesHoraires();

        foreach ($volumesHoraires as $l => $volumesHoraire) {
            foreach (self::RESCOLS as $resCol) {
                $cellColPos = $this->formule->{'get' . $resCol . 'Col'}();
                if ($cellColPos) {
                    $val = $this->{'c_' . $cellColPos}($l);
                    $this->debug['vh'][$l][$cellColPos] = $val;
                } else {
                    $val = 0.0;
                }
                $volumesHoraire->{'set' . $resCol}($val);
            }
        }

        foreach( $this->debug['vh'] as $vhi => $vhd){
            uksort($this->debug['vh'][$vhi], function($a, $b){
                return (int)(Calc::letterToNumber($a) - Calc::letterToNumber($b));
            });
        }

        uksort($this->debug['global'], function($a, $b){
            $aCoords = Calc::cellNameToCoords($a);
            $bCoords = Calc::cellNameToCoords($b);

            if ($aCoords['col'] == $bCoords['col']){
                return $aCoords['row'] - $bCoords['row'];
            }else{
                return $aCoords['col'] - $bCoords['col'];
            }
        });

        return $this->debug;
    }
}