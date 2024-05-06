<?php

namespace Formule\Model;

use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;

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



    protected function max(string $cellName): float
    {
        $result = null;
        foreach ($this->intervenant->getVolumesHoraires() as $l => $vh) {
            $cr = $this->{'c_' . $cellName}($l);
            if ($result === null || $cr > $result) {
                $result = $cr;
            }
        }
        //$this->debug['global']['max('.$cellName.')'] = $result;

        return $result;
    }



    protected function somme(string $cellName): float
    {
        $result = 0;
        foreach ($this->intervenant->getVolumesHoraires() as $l => $vh) {
            $cr = $this->{'c_' . $cellName}($l);
            $result += $cr;
        }
        //$this->debug['global']['somme('.$cellName.')'] = $result;

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
            'vh' => [],
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

        return $this->debug;
    }
}