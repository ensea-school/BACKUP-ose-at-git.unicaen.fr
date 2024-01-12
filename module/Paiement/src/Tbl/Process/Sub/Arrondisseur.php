<?php

namespace Paiement\Tbl\Process\Sub;

class Arrondisseur
{

    private array $possibilites = [
        -1 => [
            0 => 1,
            1 => 9,
            2 => 8,
            3 => 7,
            4 => 3,
            5 => 2,
            6 => 8,
            7 => 6,
            8 => 5,
            9 => 4,
        ],
        1  => [
            0 => 1,
            1 => 4,
            2 => 5,
            3 => 6,
            4 => 8,
            5 => 2,
            6 => 3,
            7 => 7,
            8 => 8,
            9 => 9,
        ]
    ];



    public function arrondir(ServiceAPayer $sap)
    {
        /* l'arrondisseur ne traite que les heures AA, puisque la répartition AA/AC n'a pas encore eu lieu */

        if ($sap->heures === null) {
            return;
        }

        $vhHeures = 0;
        foreach ($sap->lignesAPayer as $id => $lap) {
            $vhHeures = $vhHeures + $lap->heuresAA;
        }

        $diff = $sap->heures - $vhHeures;

        if ($diff === 0 || empty($sap->lignesAPayer)) {
            return; // rien à faire
        }

        $sens = $diff > 0 ? 1 : -1;
        $occurences = (int)abs($diff);

        $allreadyChanged = [];
        for ($occ = 0; $occ < $occurences; $occ++) {
            $selected = null;
            $maxScore = -1;
            foreach ($sap->lignesAPayer as $k => $lap) {
                $nbr = '00'.(string)$lap->heuresAA;
                $cent = (int)substr($nbr, -1);
                $dec = (int)substr($nbr, -2, 1);
                $score = 100000 * $this->possibilites[$sens][$cent] + 10000 * $this->possibilites[$sens][$dec];
                if (in_array($k, $allreadyChanged)) {
                    $score = $score / 100;
                }
                if ($score > $maxScore) {
                    $maxScore = $score;
                    $selected = $k;
                }
            }
            $sap->lignesAPayer[$selected]->heuresAA = (int)round($sap->lignesAPayer[$selected]->heuresAA + $sens);
            $allreadyChanged[] = $selected;
        }

        // plus besoin des heures du service : l'arrondi a déjà été fait
        $sap->heures = null;
    }
}