<?php

namespace Paiement\Tbl\Process\Sub;

class Consolidateur
{

    public function consolider(ServiceAPayer $sap)
    {
        $oldLap = $sap->lignesAPayer;
        $sap->lignesAPayer = [];

        foreach ($oldLap as $l) {
            $key = $l->key;
            if (!isset($sap->lignesAPayer[$key])) {
                $lap = new LigneAPayer();
                $sap->lignesAPayer[$key] = $lap;
                $lap->tauxRemu = $l->tauxRemu;
                $lap->tauxValeur = $l->tauxValeur;
                $lap->heures = 0;
                $lap->heuresAA = 0;
                $lap->heuresAC = 0;
            }
            $heuresAA = $l->heures * $l->pourcAA;
            $sap->lignesAPayer[$key]->heures = $sap->lignesAPayer[$key]->heures + $l->heures;
            $sap->lignesAPayer[$key]->heuresAA = $sap->lignesAPayer[$key]->heuresAA + $heuresAA;
            $sap->lignesAPayer[$key]->heuresAC = $sap->lignesAPayer[$key]->heuresAC + $l->heures - $heuresAA;
        }
    }

}