<?php

namespace Paiement\Tbl\Process\Sub;

class Consolidateur
{

    public function consolider(ServiceAPayer $sap)
    {
        if (empty($sap->lignesAPayer)){
            return;
        }

        $laps = [];
        foreach ($sap->lignesAPayer as $l) {
            $key = $l->tauxRemu . '-' . $l->tauxValeur . '-' . ($l->periode ?? 0);
            if (!isset($laps[$key])) {
                $lap = new LigneAPayer();
                $lap->periode = $l->periode;
                $lap->tauxRemu = $l->tauxRemu;
                $lap->tauxValeur = $l->tauxValeur;
                $lap->heures = 0;
                $lap->heuresAA = 0;
                $lap->heuresAC = 0;
                $laps[$key] = $lap;
            }
            $heuresAA = (int)round($l->heures * $l->pourcAA);
            $laps[$key]->heures += $l->heures;
            $laps[$key]->heuresAA += $heuresAA;
            $laps[$key]->heuresAC += $l->heures - $heuresAA;
        }

        $sap->lignesAPayer = array_values($laps);
    }

}