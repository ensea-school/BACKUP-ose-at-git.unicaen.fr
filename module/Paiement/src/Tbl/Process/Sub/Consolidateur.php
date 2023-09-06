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
            $key = $l->tauxRemu . '-' . $l->tauxValeur;
            if (!isset($laps[$key])) {
                $lap = new LigneAPayer();
                $lap->tauxRemu = $l->tauxRemu;
                $lap->tauxValeur = $l->tauxValeur;
                $lap->heures = 0;
                $lap->heuresAA = 0;
                $lap->heuresAC = 0;
                $laps[$key] = $lap;
            }
            $heuresAA = round($l->heures * $l->pourcAA);
            $laps[$key]->heures = $laps[$key]->heures + $l->heures;
            $laps[$key]->heuresAA = $laps[$key]->heuresAA + $heuresAA;
            $laps[$key]->heuresAC = $laps[$key]->heuresAC + $l->heures - $heuresAA;
        }

        $sap->lignesAPayer = array_values($laps);
    }

}