<?php

namespace Formule\Model;

use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleTestIntervenant;
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

    protected Formule            $formule;
    protected FormuleIntervenant $intervenant;

    /** @var FormuleVolumeHoraire[] */
    protected array $volumesHoraires = [];

    /** @var FormuleVolumeHoraire[] */
    protected array $nonPayables = [];

    protected array $cache = [];

    protected int $mainLine = 20;

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
            $cr     = $this->c($col, $l);
            $result += $cr;
        }

        return $result;
    }



    /**
     * Retourne la dernière valeur de la liste pour la colonne donnée
     *
     * @param string $col
     * @return float
     */
    protected function derniere(string $col): float
    {
        $result = 0;
        foreach ($this->volumesHoraires as $l => $vh) {
            $cr     = $this->c($col, $l);
            $result = $cr;
        }

        return $result;
    }



    protected function c(string $name, int $l)
    {
        if ($l < 0) {
            return $this->cg($name . (string)($this->mainLine + $l));
        }

        if (!isset($this->cache['vh'][$l][$name])) {
            $cname                        = 'c_' . $name;
            $this->cache['vh'][$l][$name] = $this->$cname($l);
        }

        return $this->cache['vh'][$l][$name];
    }



    protected function cg(string $name)
    {
        if (!isset($this->cache['global'][$name])) {
            $cname                        = 'c_' . $name;
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



    public function calculer(FormuleIntervenant $intervenant, Formule $formule): void
    {
        $this->intervenant     = $intervenant;
        $this->volumesHoraires = [];
        $this->nonPayables     = [];
        foreach ($intervenant->getVolumesHoraires() as $volumesHoraire) {
            if ($volumesHoraire->isNonPayable()) {
                $this->nonPayables[] = $volumesHoraire;
            } else {
                $this->volumesHoraires[] = $volumesHoraire;
            }
        }

        $this->formule         = $formule;
        $this->cache           = [
            'vh'     => [],
            'global' => [],
        ];

        foreach ($this->volumesHoraires as $l => $volumeHoraire) {
            $this->calculPayable($l, $volumeHoraire);
            if ($intervenant->isDepassementServiceDuSansHC()) {
                $this->interdictionHC($l, $volumeHoraire);
            }
        }

        foreach ($this->nonPayables as $l => $volumeHoraire) {
            $this->calculNonPayable($l, $volumeHoraire);
        }

        if ($this->intervenant instanceof FormuleTestIntervenant) {
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

            $this->intervenant->setDebugTrace($this->cache);
        }
    }



    protected function calculNonPayable(int $l, FormuleVolumeHoraire $volumeHoraire): void
    {
        $volumeHoraire->setHeuresPrimes(0);
        if ($volumeHoraire->getVolumeHoraire()) {
            $hetd = $volumeHoraire->getHeures() * $volumeHoraire->getPonderationServiceCompl() * $volumeHoraire->getTauxServiceCompl();

            $volumeHoraire->setHeuresServiceFi(0);
            $volumeHoraire->setHeuresServiceFa(0);
            $volumeHoraire->setHeuresServiceFc(0);

            $volumeHoraire->setHeuresComplFi(0);
            $volumeHoraire->setHeuresComplFa(0);
            $volumeHoraire->setHeuresComplFc(0);

            $volumeHoraire->setHeuresNonPayableFi($hetd * $volumeHoraire->getTauxFi());
            $volumeHoraire->setHeuresNonPayableFa($hetd * $volumeHoraire->getTauxFa());
            $volumeHoraire->setHeuresNonPayableFc($hetd * $volumeHoraire->getTauxFc());
        } else {
            $hetd = $volumeHoraire->getHeures() * $volumeHoraire->getPonderationServiceCompl();

            $volumeHoraire->setHeuresServiceReferentiel(0);
            $volumeHoraire->setHeuresComplReferentiel(0);
            $volumeHoraire->setHeuresNonPayableReferentiel($hetd);
        }
    }



    protected function calculPayable(int $l, FormuleVolumeHoraire $volumeHoraire): void
    {
        foreach (self::RESCOLS as $resCol) {
            $cellColPos = $this->formule->{'get' . $resCol . 'Col'}();
            if ($cellColPos) {
                $val = $this->c($cellColPos, $l);
            } else {
                $val = 0.0;
            }
            $volumeHoraire->{'set' . $resCol}($val);
        }
    }



    protected function interdictionHC(int $l, FormuleVolumeHoraire $volumeHoraire): void
    {
        if ($volumeHoraire->getHeuresComplFi() !== 0.0) {
            $volumeHoraire->setHeuresNonPayableFi($volumeHoraire->getHeuresComplFi());
            $volumeHoraire->setHeuresComplFi(0);
        }
        if ($volumeHoraire->getHeuresComplFa() !== 0.0) {
            $volumeHoraire->setHeuresNonPayableFa($volumeHoraire->getHeuresComplFa());
            $volumeHoraire->setHeuresComplFa(0);
        }
        if ($volumeHoraire->getHeuresComplFc() !== 0.0) {
            $volumeHoraire->setHeuresNonPayableFc($volumeHoraire->getHeuresComplFc());
            $volumeHoraire->setHeuresComplFc(0);
        }
        if ($volumeHoraire->getHeuresComplReferentiel() !== 0.0) {
            $volumeHoraire->setHeuresNonPayableReferentiel($volumeHoraire->getHeuresComplReferentiel());
            $volumeHoraire->setHeuresComplReferentiel(0);
        }
        if ($volumeHoraire->getHeuresPrimes() !== 0.0) {
            /* Aucun endroit prévu pour stocker le non payable des primes */
            $volumeHoraire->setHeuresPrimes(0);
        }
    }
}