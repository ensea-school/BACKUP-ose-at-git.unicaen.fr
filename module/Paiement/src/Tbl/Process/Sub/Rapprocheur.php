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

        foreach ($sap->lignesAPayer as $lap) {
            $lap->misesEnPaiement = [];

            $npAA = $lap->heuresAA;
            $npAC = $lap->heuresAC;

            foreach ($sap->misesEnPaiement as $mid => $mep) {
                if ($npAA + $npAC == 0) {
                    // plus rien à rapprocher
                    break;
                }

                $nmep = new MiseEnPaiement();
                $nmep->id = $mep->id;
                $nmep->domaineFonctionnel = $mep->domaineFonctionnel;
                $nmep->centreCout = $mep->centreCout;
                $nmep->periodePaiement = $mep->periodePaiement;
                $nmep->date = $mep->date;
                $nmep->heuresAA = 0;
                $nmep->heuresAC = 0;

                if (Rapprocheur::REGLE_ORDRE_SAISIE == $this->regle) {

                    $heures = min($mep->heures, $npAA);
                    if ($heures > 0) {
                        $nmep->heuresAA += $heures;
                        $mep->heures -= $heures;
                        $npAA -= $heures;
                    }

                    $heures = min($mep->heures, $npAC);
                    if ($heures > 0) {
                        $nmep->heuresAC += $heures;
                        $mep->heures -= $heures;
                        $npAC -= $heures;
                    }

                } else {
                    $heures = min($mep->heures, $npAA + $npAC);

                    $aaHeures = min((int)round($heures * $npAA / ($npAA + $npAC)), $npAA);
                    if ($aaHeures > 0) {
                        $nmep->heuresAA += $aaHeures;
                        $mep->heures -= $aaHeures;
                        $npAA -= $aaHeures;
                    }

                    $acHeures = $heures - $aaHeures;
                    if ($acHeures > 0) {
                        $nmep->heuresAC += $acHeures;
                        $mep->heures -= $acHeures;
                        $npAC -= $acHeures;
                    }

                }

                if ($nmep->heuresAA + $nmep->heuresAC > 0) {
                    $lap->misesEnPaiement[$nmep->id] = $nmep;
                    if ($mep->heures == 0) {
                        unset($sap->misesEnPaiement[$mid]);
                    }
                }
            }
        }
    }



    protected function rapprochementProrata(ServiceAPayer $sap)
    {
        foreach ($sap->lignesAPayer as $lap) {
            $lap->misesEnPaiement = [];

            $npAA = $lap->heuresAA;
            $npAC = $lap->heuresAC;

            foreach ($sap->misesEnPaiement as $mid => $mep) {
                if ($npAA + $npAC == 0) {
                    // plus rien à rapprocher
                    break;
                }

                $nmep = new MiseEnPaiement();
                $nmep->id = $mep->id;
                $nmep->domaineFonctionnel = $mep->domaineFonctionnel;
                $nmep->centreCout = $mep->centreCout;
                $nmep->periodePaiement = $mep->periodePaiement;
                $nmep->date = $mep->date;
                $nmep->heuresAA = 0;
                $nmep->heuresAC = 0;

                $heures = min($mep->heures, $npAA + $npAC);

                $aaHeures = min((int)round($heures * $npAA / ($npAA + $npAC)), $npAA);
                if ($aaHeures > 0) {
                    $nmep->heuresAA += $aaHeures;
                    $mep->heures -= $aaHeures;
                    $npAA -= $aaHeures;
                }

                $acHeures = $heures - $aaHeures;
                if ($acHeures > 0) {
                    $nmep->heuresAC += $acHeures;
                    $mep->heures -= $acHeures;
                    $npAC -= $acHeures;
                }

                if ($nmep->heuresAA + $nmep->heuresAC > 0) {
                    $lap->misesEnPaiement[$nmep->id] = $nmep;
                    if ($mep->heures == 0) {
                        unset($sap->misesEnPaiement[$mid]);
                    }
                }
            }
        }
    }



    protected function rapprochementOrdre(ServiceAPayer $sap)
    {
        foreach ($sap->lignesAPayer as $lap) {
            $lap->misesEnPaiement = [];

            $npAA = $lap->heuresAA;
            $npAC = $lap->heuresAC;

            foreach ($sap->misesEnPaiement as $mid => $mep) {
                if ($npAA + $npAC == 0) {
                    // plus rien à rapprocher
                    break;
                }

                $nmep = new MiseEnPaiement();
                $nmep->id = $mep->id;
                $nmep->domaineFonctionnel = $mep->domaineFonctionnel;
                $nmep->centreCout = $mep->centreCout;
                $nmep->periodePaiement = $mep->periodePaiement;
                $nmep->date = $mep->date;
                $nmep->heuresAA = 0;
                $nmep->heuresAC = 0;

                if (Rapprocheur::REGLE_ORDRE_SAISIE == $this->regle) {

                    $heures = min($mep->heures, $npAA);
                    if ($heures > 0) {
                        $nmep->heuresAA += $heures;
                        $mep->heures -= $heures;
                        $npAA -= $heures;
                    }

                    $heures = min($mep->heures, $npAC);
                    if ($heures > 0) {
                        $nmep->heuresAC += $heures;
                        $mep->heures -= $heures;
                        $npAC -= $heures;
                    }

                } else {
                    $heures = min($mep->heures, $npAA + $npAC);

                    $aaHeures = min((int)round($heures * $npAA / ($npAA + $npAC)), $npAA);
                    if ($aaHeures > 0) {
                        $nmep->heuresAA += $aaHeures;
                        $mep->heures -= $aaHeures;
                        $npAA -= $aaHeures;
                    }

                    $acHeures = $heures - $aaHeures;
                    if ($acHeures > 0) {
                        $nmep->heuresAC += $acHeures;
                        $mep->heures -= $acHeures;
                        $npAC -= $acHeures;
                    }

                }

                if ($nmep->heuresAA + $nmep->heuresAC > 0) {
                    $lap->misesEnPaiement[$nmep->id] = $nmep;
                    if ($mep->heures == 0) {
                        unset($sap->misesEnPaiement[$mid]);
                    }
                }
            }
        }
    }
}