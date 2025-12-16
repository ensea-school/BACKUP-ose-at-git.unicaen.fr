<?php

namespace Paiement\Tbl\Process\Sub;

class Exporteur
{

    public function exporter(ServiceAPayer $sap, array &$destination)
    {
        if (empty($sap->lignesAPayer)) {
            return;
        }

        foreach ($sap->lignesAPayer as $lap) {
            $this->exporterLAP($sap, $lap, $destination);
            $lastTauxRemu = $lap->tauxRemu;
            $lastTauxValeur = $lap->tauxValeur;
        }

        foreach($sap->misesEnPaiement as $smep){
            $heuresAA = $smep->heuresAA;//(int)round($smep->heures * $lastPourcAA);
            $heuresAc = $smep->heuresAC;// - $heuresAA;

            $ldata = [
                'ANNEE_ID'                   => $sap->annee,
                'SERVICE_ID'                 => $sap->service,
                'SERVICE_REFERENTIEL_ID'     => $sap->serviceReferentiel,
                'MISSION_ID'                 => $sap->mission,
                'TYPE_INTERVENANT_ID'        => $sap->typeIntervenant,
                'INTERVENANT_ID'             => $sap->intervenant,
                'STRUCTURE_ID'               => $sap->structure,
                'TYPE_HEURES_ID'             => $sap->typeHeures,
                'PERIODE_ENS_ID'             => NULL,
                'MISE_EN_PAIEMENT_ID'        => $smep->id,
                'PERIODE_PAIEMENT_ID'        => $smep->periodePaiement,
                'CENTRE_COUT_ID'             => $smep->centreCout,
                'DOMAINE_FONCTIONNEL_ID'     => $smep->domaineFonctionnel ?: $sap->defDomaineFonctionnel,
                'TAUX_REMU_ID'               => $lastTauxRemu,
                'TAUX_HORAIRE'               => $lastTauxValeur,
                'TAUX_CONGES_PAYES'          => $sap->tauxCongesPayes,
                'HEURES_A_PAYER_AA'          => 0.0,
                'HEURES_A_PAYER_AC'          => 0.0,
                'HEURES_DEMANDEES_AA'        => round($heuresAA / 100, 2),
                'HEURES_DEMANDEES_AC'        => round($heuresAc / 100, 2),
                'HEURES_PAYEES_AA'           => $smep->periodePaiement ? round($heuresAA / 100, 2) : 0.0,
                'HEURES_PAYEES_AC'           => $smep->periodePaiement ? round($heuresAc / 100, 2) : 0.0,
            ];
            $foundLine = $this->createKey($ldata);
            if (array_key_exists($foundLine,$destination)) {
                var_dump($ldata['INTERVENANT_ID'].' ');
                $destination[$foundLine]['HEURES_A_PAYER_AA']   = round($destination[$foundLine]['HEURES_A_PAYER_AA'] + $ldata['HEURES_A_PAYER_AA'], 2);
                $destination[$foundLine]['HEURES_A_PAYER_AC']   = round($destination[$foundLine]['HEURES_A_PAYER_AC'] + $ldata['HEURES_A_PAYER_AC'], 2);
                $destination[$foundLine]['HEURES_DEMANDEES_AA'] = round($destination[$foundLine]['HEURES_DEMANDEES_AA'] + $ldata['HEURES_DEMANDEES_AA'], 2);
                $destination[$foundLine]['HEURES_DEMANDEES_AC'] = round($destination[$foundLine]['HEURES_DEMANDEES_AC'] + $ldata['HEURES_DEMANDEES_AC'], 2);
                $destination[$foundLine]['HEURES_PAYEES_AA']    = round($destination[$foundLine]['HEURES_PAYEES_AA'] + $ldata['HEURES_PAYEES_AA'], 2);
                $destination[$foundLine]['HEURES_PAYEES_AC']    = round($destination[$foundLine]['HEURES_PAYEES_AC'] + $ldata['HEURES_PAYEES_AC'], 2);
            } else {
                $destination[$foundLine] = $ldata;
            }
        }

    }



    protected function createKey(array $line): string
    {
        $keyData = $line['ANNEE_ID']
            . '|' . $line['SERVICE_ID']
            . '|' . $line['SERVICE_REFERENTIEL_ID']
            . '|' . $line['MISSION_ID']
            . '|' . $line['TYPE_INTERVENANT_ID']
            . '|' . $line['INTERVENANT_ID']
            . '|' . $line['STRUCTURE_ID']
            . '|' . $line['TYPE_HEURES_ID']
            . '|' . $line['PERIODE_ENS_ID']
            . '|' . $line['MISE_EN_PAIEMENT_ID']
            . '|' . $line['PERIODE_PAIEMENT_ID']
            . '|' . $line['CENTRE_COUT_ID']
            . '|' . $line['DOMAINE_FONCTIONNEL_ID']
            . '|' . $line['TAUX_REMU_ID']
            . '|' . $line['TAUX_HORAIRE']
            . '|' . $line['TAUX_CONGES_PAYES'];
        return $keyData;
    }



    protected function exporterLAP(ServiceAPayer $sap, LigneAPayer $lap, array &$destination)
    {
        $rapAA = $lap->heuresAA;
        $rapAC = $lap->heuresAC;

        foreach ($lap->misesEnPaiement as $mep) {
            $rapAA -= $mep->heuresAA;
            $rapAC -= $mep->heuresAC;

            $ldata = [
                'ANNEE_ID'                   => $sap->annee,
                'SERVICE_ID'                 => $sap->service,
                'SERVICE_REFERENTIEL_ID'     => $sap->serviceReferentiel,
                'MISSION_ID'                 => $sap->mission,
                'TYPE_INTERVENANT_ID'        => $sap->typeIntervenant,
                'INTERVENANT_ID'             => $sap->intervenant,
                'STRUCTURE_ID'               => $sap->structure,
                'TYPE_HEURES_ID'             => $sap->typeHeures,
                'PERIODE_ENS_ID'             => $lap->periode,
                'MISE_EN_PAIEMENT_ID'        => $mep->id,
                'PERIODE_PAIEMENT_ID'        => $mep->periodePaiement,
                'CENTRE_COUT_ID'             => $mep->centreCout,
                'DOMAINE_FONCTIONNEL_ID'     => $mep->domaineFonctionnel ?: $sap->defDomaineFonctionnel,
                'TAUX_REMU_ID'               => $lap->tauxRemu,
                'TAUX_HORAIRE'               => $lap->tauxValeur,
                'TAUX_CONGES_PAYES'          => $sap->tauxCongesPayes,
                'HEURES_A_PAYER_AA'          => round($mep->heuresAA / 100, 2),
                'HEURES_A_PAYER_AC'          => round($mep->heuresAC / 100, 2),
                'HEURES_DEMANDEES_AA'        => round($mep->heuresAA / 100, 2),
                'HEURES_DEMANDEES_AC'        => round($mep->heuresAC / 100, 2),
                'HEURES_PAYEES_AA'           => $mep->periodePaiement ? round($mep->heuresAA / 100, 2) : 0.0,
                'HEURES_PAYEES_AC'           => $mep->periodePaiement ? round($mep->heuresAC / 100, 2) : 0.0,
            ];
            $destination[$this->createKey($ldata)] = $ldata;
        }

        if ($rapAA + $rapAC > 0) {
            $ldata = [
                'ANNEE_ID'                   => $sap->annee,
                'SERVICE_ID'                 => $sap->service,
                'SERVICE_REFERENTIEL_ID'     => $sap->serviceReferentiel,
                'MISSION_ID'                 => $sap->mission,
                'TYPE_INTERVENANT_ID'        => $sap->typeIntervenant,
                'INTERVENANT_ID'             => $sap->intervenant,
                'STRUCTURE_ID'               => $sap->structure,
                'TYPE_HEURES_ID'             => $sap->typeHeures,
                'PERIODE_ENS_ID'             => $lap->periode,
                'MISE_EN_PAIEMENT_ID'        => NULL,
                'PERIODE_PAIEMENT_ID'        => NULL,
                'CENTRE_COUT_ID'             => $sap->defCentreCout,
                'DOMAINE_FONCTIONNEL_ID'     => $sap->defDomaineFonctionnel,
                'TAUX_REMU_ID'               => $lap->tauxRemu,
                'TAUX_HORAIRE'               => $lap->tauxValeur,
                'TAUX_CONGES_PAYES'          => $sap->tauxCongesPayes,
                'HEURES_A_PAYER_AA'          => round($rapAA / 100, 2),
                'HEURES_A_PAYER_AC'          => round($rapAC / 100, 2),
                'HEURES_DEMANDEES_AA'        => 0.0,
                'HEURES_DEMANDEES_AC'        => 0.0,
                'HEURES_PAYEES_AA'           => 0.0,
                'HEURES_PAYEES_AC'           => 0.0,
            ];
            $destination[$this->createKey($ldata)] = $ldata;
        }
    }
}