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
                'SERVICE_REFERENTIEL_ID'     => $sap->referentiel,
                'MISSION_ID'                 => $sap->mission,
                'FORMULE_RES_SERVICE_ID'     => $sap->formuleResService,
                'FORMULE_RES_SERVICE_REF_ID' => $sap->formuleResServiceRef,
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
            $destination[] = $ldata;
        }

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
                'SERVICE_REFERENTIEL_ID'     => $sap->referentiel,
                'MISSION_ID'                 => $sap->mission,
                'FORMULE_RES_SERVICE_ID'     => $sap->formuleResService,
                'FORMULE_RES_SERVICE_REF_ID' => $sap->formuleResServiceRef,
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
            $destination[] = $ldata;
        }

        if ($rapAA + $rapAC > 0) {
            $ldata = [
                'ANNEE_ID'                   => $sap->annee,
                'SERVICE_ID'                 => $sap->service,
                'SERVICE_REFERENTIEL_ID'     => $sap->referentiel,
                'MISSION_ID'                 => $sap->mission,
                'FORMULE_RES_SERVICE_ID'     => $sap->formuleResService,
                'FORMULE_RES_SERVICE_REF_ID' => $sap->formuleResServiceRef,
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
            $destination[] = $ldata;
        }
    }
}