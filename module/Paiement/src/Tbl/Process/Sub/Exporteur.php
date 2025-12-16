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
                var_dump($ldata['intervenant_id'].' ');
                $destination[$foundLine]['heures_a_payer_aa']   = round($destination[$foundLine]['heures_a_payer_aa'] + $ldata['heures_a_payer_aa'], 2);
                $destination[$foundLine]['heures_a_payer_ac']   = round($destination[$foundLine]['heures_a_payer_ac'] + $ldata['heures_a_payer_ac'], 2);
                $destination[$foundLine]['heures_demandees_aa'] = round($destination[$foundLine]['heures_demandees_aa'] + $ldata['heures_demandees_aa'], 2);
                $destination[$foundLine]['heures_demandees_ac'] = round($destination[$foundLine]['heures_demandees_ac'] + $ldata['heures_demandees_ac'], 2);
                $destination[$foundLine]['heures_payees_aa']    = round($destination[$foundLine]['heures_payees_aa'] + $ldata['heures_payees_aa'], 2);
                $destination[$foundLine]['heures_payees_ac']    = round($destination[$foundLine]['heures_payees_ac'] + $ldata['heures_payees_ac'], 2);
            } else {
                $destination[$foundLine] = $ldata;
            }
        }

    }



    protected function createKey(array $line): string
    {
        $keyData = $line['annee_id']
            . '|' . $line['service_id']
            . '|' . $line['service_referentiel_id']
            . '|' . $line['mission_id']
            . '|' . $line['type_intervenant_id']
            . '|' . $line['intervenant_id']
            . '|' . $line['structure_id']
            . '|' . $line['type_heures_id']
            . '|' . $line['periode_ens_id']
            . '|' . $line['mise_en_paiement_id']
            . '|' . $line['periode_paiement_id']
            . '|' . $line['centre_cout_id']
            . '|' . $line['domaine_fonctionnel_id']
            . '|' . $line['taux_remu_id']
            . '|' . $line['taux_horaire']
            . '|' . $line['taux_conges_payes'];
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