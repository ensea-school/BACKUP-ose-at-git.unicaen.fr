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
            $lastTauxRemu   = $lap->tauxRemu;
            $lastTauxValeur = $lap->tauxValeur;
        }

        foreach ($sap->misesEnPaiement as $smep) {
            $heuresAA = $smep->heuresAA;//(int)round($smep->heures * $lastPourcAA);
            $heuresAc = $smep->heuresAC;// - $heuresAA;

            $ldata     = [
                'annee_id'               => $sap->annee,
                'service_id'             => $sap->service,
                'service_referentiel_id' => $sap->serviceReferentiel,
                'mission_id'             => $sap->mission,
                'type_intervenant_id'    => $sap->typeIntervenant,
                'intervenant_id'         => $sap->intervenant,
                'structure_id'           => $sap->structure,
                'type_heures_id'         => $sap->typeHeures,
                'periode_ens_id'         => NULL,
                'mise_en_paiement_id'    => $smep->id,
                'periode_paiement_id'    => $smep->periodePaiement,
                'centre_cout_id'         => $smep->centreCout,
                'domaine_fonctionnel_id' => $smep->domaineFonctionnel ?: $sap->defDomaineFonctionnel,
                'taux_remu_id'           => $lastTauxRemu,
                'taux_horaire'           => $lastTauxValeur,
                'taux_conges_payes'      => $sap->tauxCongesPayes,
                'heures_a_payer_aa'      => 0.0,
                'heures_a_payer_ac'      => 0.0,
                'heures_demandees_aa'    => round($heuresAA / 100, 2),
                'heures_demandees_ac'    => round($heuresAc / 100, 2),
                'heures_payees_aa'       => $smep->periodePaiement ? round($heuresAA / 100, 2) : 0.0,
                'heures_payees_ac'       => $smep->periodePaiement ? round($heuresAc / 100, 2) : 0.0,
            ];
            $foundLine = $this->createKey($ldata);
            if (array_key_exists($foundLine,$destination)) {
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

            $ldata         = [
                'annee_id'               => $sap->annee,
                'service_id'             => $sap->service,
                'service_referentiel_id' => $sap->serviceReferentiel,
                'mission_id'             => $sap->mission,
                'type_intervenant_id'    => $sap->typeIntervenant,
                'intervenant_id'         => $sap->intervenant,
                'structure_id'           => $sap->structure,
                'type_heures_id'         => $sap->typeHeures,
                'periode_ens_id'         => $lap->periode,
                'mise_en_paiement_id'    => $mep->id,
                'periode_paiement_id'    => $mep->periodePaiement,
                'centre_cout_id'         => $mep->centreCout,
                'domaine_fonctionnel_id' => $mep->domaineFonctionnel ?: $sap->defDomaineFonctionnel,
                'taux_remu_id'           => $lap->tauxRemu,
                'taux_horaire'           => $lap->tauxValeur,
                'taux_conges_payes'      => $sap->tauxCongesPayes,
                'heures_a_payer_aa'      => round($mep->heuresAA / 100, 2),
                'heures_a_payer_ac'      => round($mep->heuresAC / 100, 2),
                'heures_demandees_aa'    => round($mep->heuresAA / 100, 2),
                'heures_demandees_ac'    => round($mep->heuresAC / 100, 2),
                'heures_payees_aa'       => $mep->periodePaiement ? round($mep->heuresAA / 100, 2) : 0.0,
                'heures_payees_ac'       => $mep->periodePaiement ? round($mep->heuresAC / 100, 2) : 0.0,
            ];
            $destination[$this->createKey($ldata)] = $ldata;
        }

        if ($rapAA + $rapAC > 0) {
            $ldata         = [
                'annee_id'               => $sap->annee,
                'service_id'             => $sap->service,
                'service_referentiel_id' => $sap->serviceReferentiel,
                'mission_id'             => $sap->mission,
                'type_intervenant_id'    => $sap->typeIntervenant,
                'intervenant_id'         => $sap->intervenant,
                'structure_id'           => $sap->structure,
                'type_heures_id'         => $sap->typeHeures,
                'periode_ens_id'         => $lap->periode,
                'mise_en_paiement_id'    => NULL,
                'periode_paiement_id'    => NULL,
                'centre_cout_id'         => $sap->defCentreCout,
                'domaine_fonctionnel_id' => $sap->defDomaineFonctionnel,
                'taux_remu_id'           => $lap->tauxRemu,
                'taux_horaire'           => $lap->tauxValeur,
                'taux_conges_payes'      => $sap->tauxCongesPayes,
                'heures_a_payer_aa'      => round($rapAA / 100, 2),
                'heures_a_payer_ac'      => round($rapAC / 100, 2),
                'heures_demandees_aa'    => 0.0,
                'heures_demandees_ac'    => 0.0,
                'heures_payees_aa'       => 0.0,
                'heures_payees_ac'       => 0.0,
            ];
            $destination[$this->createKey($ldata)] = $ldata;
        }
    }
}