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

        $hasHeuresNegatives = $this->hasHeuresNegatives($sap);
        if ($hasHeuresNegatives){
            $this->traitementHeuresNegatives($sap);
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

        if ($hasHeuresNegatives){
            foreach ($sap->lignesAPayer as $lap){
                $lap->heuresAA = $lap->intBuffer1;
                $lap->heuresAC = $lap->intBuffer2;
            }
        }
    }



    protected function hasHeuresNegatives(ServiceAPayer $sap): bool
    {
        foreach ($sap->lignesAPayer as $lap) {
            if ($lap->heuresAA + $lap->heuresAC < 0){
                return true;
            }
        }

        return false;
    }



    public function traitementHeuresNegatives(ServiceAPayer $sap)
    {
        /** @var LigneAPayer[] $laps */
        $laps = array_values($sap->lignesAPayer);

        // Initialisation des buffers en premier
        foreach( $laps as $i => $lap ) {
            $lap->intBuffer1 = $lap->heuresAA;
            $lap->intBuffer2 = $lap->heuresAC;
        }

        // parcours pour attraper les heures négatives
        foreach( $laps as $i => $lap ){

            // si le buffer 1 (heures AA) < 0
            if ($lap->heuresAA < 0){
                // Les heures négatives sont remises à 0 et les heures positives sont diminuées d'autant sur tous les volumes horaires antérieurs
                if ($i > 0) {
                    for ($i2 = $i - 1; $i2 >= 0; $i2--) {
                        $lap2 = $laps[$i2];
                        if ($lap2->heuresAA > 0 && $lap2->key() == $lap->key()) {
                            $hDiff = min($lap->heuresAA * -1, $lap2->heuresAA);
                            $lap2->heuresAA -= $hDiff;
                            $lap->heuresAA += $hDiff;
                        }
                        if ($lap->heuresAA == 0) {
                            break;
                        }
                    }
                }

                // s'il reste encore des heures négatives, on les retranche aux volumes horaires postérieurs
                if ($lap->heuresAA < 0){
                    for($i3 = $i+1; $i3 < count($laps); $i3++){
                        $lap3 = $laps[$i3];
                        if ($lap3->heuresAA > 0 && $lap3->key() == $lap->key()){
                            $hDiff = min($lap->heuresAA*-1, $lap3->heuresAA);
                            $lap3->heuresAA -= $hDiff;
                            $lap->heuresAA += $hDiff;
                        }
                        if ($lap->heuresAA == 0){
                            break;
                        }
                    }
                }
            }

            // si le buffer 2 (heures AC) < 0
            if ($lap->heuresAC < 0){
                // Les heures négatives sont remises à 0 et les heures positives sont diminuées d'autant sur tous les volumes horaires antérieurs
                if ($i > 0) {
                    for ($i2 = $i - 1; $i2 >= 0; $i2--) {
                        $lap2 = $laps[$i2];
                        if ($lap2->heuresAC > 0 && $lap2->key() == $lap->key()) {
                            $hDiff = min($lap->heuresAC * -1, $lap2->heuresAC);
                            $lap2->heuresAC -= $hDiff;
                            $lap->heuresAC += $hDiff;
                        }
                        if ($lap->heuresAC == 0) {
                            break;
                        }
                    }
                }

                // s'il reste encore des heures négatives, on les retranche aux volumes horaires postérieurs
                if ($lap->heuresAC < 0){
                    for($i3 = $i+1; $i3 < count($laps); $i3++){
                        $lap3 = $laps[$i3];
                        if ($lap3->heuresAC > 0 && $lap3->key() == $lap->key()){
                            $hDiff = min($lap->heuresAC*-1, $lap3->heuresAC);
                            $lap3->heuresAC -= $hDiff;
                            $lap->heuresAC += $hDiff;
                        }
                        if ($lap->heuresAC == 0){
                            break;
                        }
                    }
                }
            }

        }
    }
}