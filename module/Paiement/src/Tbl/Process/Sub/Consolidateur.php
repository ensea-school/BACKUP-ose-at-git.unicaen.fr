<?php

namespace Paiement\Tbl\Process\Sub;

class Consolidateur
{

    public function consolider(ServiceAPayer $sap, $full = true)
    {
        if (empty($sap->lignesAPayer)) {
            return;
        }

        $laps = [];
        foreach ($sap->lignesAPayer as $i => $l) {
            if ($full) {
                $key = $l->tauxRemu . '-' . $l->tauxValeur . '-' . ($l->periode ?? 0);
            } else {
                $key = $i;
            }
            if (!isset($laps[$key])) {
                $lap = new LigneAPayer();
                $lap->periode = $l->periode;
                if (empty($l->volumeHoraireId)) {
                    $lap->volumeHoraireId = null;
                } elseif (empty($lap->volumeHoraireId)) {
                    $lap->volumeHoraireId = $l->volumeHoraireId;
                }

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