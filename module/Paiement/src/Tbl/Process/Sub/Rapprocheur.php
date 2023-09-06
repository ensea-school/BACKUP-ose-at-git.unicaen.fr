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
        if ('prorata' === $this->getRegle()) {
            // Chaque mise en paiement est répartie selon le prorata AA/AC

            foreach ($sap->lignesAPayer as $lapId => $lap) {
                $lap->misesEnPaiement = [];
                $aaNp = $lap->heuresAA;
                $acNp = $lap->heuresAC;
                foreach ($sap->misesEnPaiement as $mepId => $mep) {
                    $nmep = null;
                    $heures = min($mep->heures, $aaNp);
                    if ($heures > 0) {
                        $nmep = new MiseEnPaiement();
                        $nmep->heures = $heures;
                        $nmep->heuresAA = $heures;
                        $nmep->id = $mep->id;
                        $nmep->domaineFonctionnel = $mep->domaineFonctionnel;
                        $nmep->centreCout = $mep->centreCout;
                        $nmep->periodePaiement = $mep->periodePaiement;
                        $nmep->date = $mep->date;

                        // on retire les heures des NP et des MEP
                        $aaNp = $aaNp - $heures;
                        $mep->heures = $mep->heures - $heures;
                    }

                    if ($nmep){
                        //$lap->misesEnPaiement[$nmep->id] = $
                    }
                }
            }
        }
        if ('ordre-saisie' === $this->getRegle()) {
            // Les premières mises en paiement sont considérées en AA, puis ce qui dépasse est en AC
        }
    }
}