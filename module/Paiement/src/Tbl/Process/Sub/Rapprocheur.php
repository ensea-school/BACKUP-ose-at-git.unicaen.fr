<?php

namespace Paiement\Tbl\Process\Sub;

class Rapprocheur
{
    const REGLE_PRORATA      = 'prorata';
    const REGLE_ORDRE_SAISIE = 'ordre-saisie';

    protected string $regle;



    public function getRegle(): string
    {
        return $this->regle;
    }



    public function setRegle(string $regle): Rapprocheur
    {
        if (!in_array($regle, [self::REGLE_PRORATA, self::REGLE_ORDRE_SAISIE])) {
            throw new \Exception('Règle de répartition par année civile inconnue,  (voir paramètre global regle_repartition_annee_civile)');
        }
        $this->regle = $regle;

        return $this;
    }



    public function rapprocher(ServiceAPayer $sap)
    {
        if (empty($sap->misesEnPaiement)) {
            return; // rien à rapprocher
        }

        foreach ($sap->misesEnPaiement as $mid => $mep) {
            foreach ($sap->lignesAPayer as $lap) {
                // heures non payées, AA & AC
                $npAA = $lap->nonPayeAA();
                $npAC = $lap->nonPayeAC();

                if (($npAA + $npAC == 0) || empty($mep)) {
                    // plus rien à rapprocher
                    continue;
                }

                // heures payées. Les mises en paiement non rapprochées ont toutes leurs heures en AA
                $mepHeures = $mep->heuresAA;
                // heures payées nouvellement rapprochées
                $nmepAA = 0;
                $nmepAC = 0;

                if (Rapprocheur::REGLE_ORDRE_SAISIE == $this->regle) {

                    $heures = min($mepHeures, $npAA);
                    if ($heures > 0) {
                        $nmepAA += $heures;
                        $mepHeures -= $heures;
                        $npAA -= $heures;
                    }

                    $heures = min($mepHeures, $npAC);
                    if ($heures > 0) {
                        $nmepAC += $heures;
                        $mepHeures -= $heures;
                        $npAC -= $heures;
                    }

                } else {
                    $heures = min($mepHeures, $npAA + $npAC);

                    $aaHeures = min((int)round($heures * $npAA / ($npAA + $npAC)), $npAA);
                    if ($aaHeures > 0) {
                        $nmepAA += $aaHeures;
                        $mepHeures -= $aaHeures;
                        $npAA -= $aaHeures;
                    }

                    $acHeures = $heures - $aaHeures;
                    if ($acHeures > 0) {
                        $nmepAC += $acHeures;
                        $mepHeures -= $acHeures;
                        $npAC -= $acHeures;
                    }

                }

                if ($nmepAA > 0 || $nmepAC > 0 || $mepHeures == 0) {
                    $nmep = $mep->newFrom();
                    $nmep->heuresAA = $nmepAA;
                    $nmep->heuresAC = $nmepAC;
                    $lap->misesEnPaiement[$nmep->id] = $nmep;
                    $mep->heuresAA = $mepHeures;
                }
                if ($mep->heuresAA + $mep->heuresAC == 0) {
                    unset($sap->misesEnPaiement[$mid]);
                    $mep = null;
                }
            }
        }
    }
}