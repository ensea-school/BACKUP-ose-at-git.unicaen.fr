<?php

namespace Paiement\Tbl\Process\Sub;

class Consolidateur
{

    public function consolider(ServiceAPayer $sap)
    {
        if (empty($sap->lignesAPayer)) {
            return;
        }

        $laps = [];
        foreach ($sap->lignesAPayer as $i => $l) {
            $key = $l->key();
            if (!isset($laps[$key])) {
                $laps[$key] = $l;
            } else {
                $laps[$key]->heuresAA += $l->heuresAA;
                $laps[$key]->heuresAC += $l->heuresAC;
                foreach ($l->misesEnPaiement as $mepId => $mep) {
                    if (!array_key_exists($mepId, $laps[$key]->misesEnPaiement)) {
                        $laps[$key]->misesEnPaiement[$mepId] = $mep;
                    } else {
                        $laps[$key]->misesEnPaiement[$mepId]->heuresAA += $mep->heuresAA;
                        $laps[$key]->misesEnPaiement[$mepId]->heuresAC += $mep->heuresAC;
                        unset($l->misesEnPaiement[$mepId]);
                    }
                }
                unset($sap->lignesAPayer[$i]);
            }
        }

        /* Deuxième passe de calcul */
        /* Traitement des heures payées en trop à isoler */
        foreach ($sap->lignesAPayer as $i => $l) {
            $heuresAARestantes = $l->heuresAA;
            $heuresACRestantes = $l->heuresAC;
            foreach ($l->misesEnPaiement as $mepId => $mep) {
                $heuresEnMoins= false;
                if ($mep->heuresAA > $heuresAARestantes) {
                    $sapMep = $this->getSapMep($sap, $mep);
                    $sapMep->heuresAA += $mep->heuresAA - $heuresAARestantes;
                    $mep->heuresAA -= $mep->heuresAA - $heuresAARestantes;
                    $heuresEnMoins= true;
                }
                if ($mep->heuresAC > $heuresACRestantes) {
                    $sapMep = $this->getSapMep($sap, $mep);
                    $sapMep->heuresAC += $mep->heuresAC - $heuresACRestantes;
                    $mep->heuresAC -= $mep->heuresAC - $heuresACRestantes;
                    $heuresEnMoins= true;
                }
                if ($heuresEnMoins && $mep->heuresAA == 0 && $mep->heuresAC == 0){
                    unset($l->misesEnPaiement[$mepId]);
                }
            }
        }

        $sap->lignesAPayer = array_values($sap->lignesAPayer);
    }



    protected function getSapMep(ServiceAPayer $sap, MiseEnPaiement $reference): MiseEnPaiement
    {
        if (!array_key_exists($reference->id, $sap->misesEnPaiement)) {
            $nmep = $reference->newFrom();
            $sap->misesEnPaiement[$nmep->id] = $nmep;
        }

        return $sap->misesEnPaiement[$reference->id];
    }
}