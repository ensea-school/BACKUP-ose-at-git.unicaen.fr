<?php

namespace Formule\Service;


use Formule\Entity\FormuleIntervenant;

/**
 * Description of CalculateurService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculateurService
{
    public function calculer(FormuleIntervenant $formuleIntervenant)
    {

    }



    public function getIntervenantVariables()
    {
        $variables = [
            'annee'                      => 'Année universitaire',
            'typeVolumeHoraire'          => 'Type de volume horaire, chaine de caractères [prevu, realise]',
            'etatVolumeHoraire'          => 'État du volume horaire, chaine de caractères [saisi, valide, contrat-edite, contrat-signe]',
            'type'                       => 'Type d\'intervenant, chaine de caractères [permanent, vacataire, etudiant]',
            'structure'                  => 'Code de la structure (chaine de caractères ou null)',
            'heuresServiceStatutaire'    => 'Nombre d\'heures de service statutaire (nombre réel)',
            'heuresServiceModifie'       => 'Nombre d\'heures de service modifiées (nombre réel)',
            'heuresServiceDu'            => 'Nombre d\'heures de service dû (nombre réel)',
            'depassementServiceDuSansHC' => 'Le dépassement du service dû ne devra pas engendrer d\'heures complémentaires (booléen)',
            'param1'                     => 'Paramètre personnalisé 1',
            'param2'                     => 'Paramètre personnalisé 2',
            'param3'                     => 'Paramètre personnalisé 3',
            'param4'                     => 'Paramètre personnalisé 4',
            'param5'                     => 'Paramètre personnalisé 5',

        ];

        $resultVariables = [];
        foreach ($variables as $nom => $description) {
            $resultVariables['intervenant.' . $nom] = [
                'description' => $description,
                'value'       => null,
            ];
        }

        return $resultVariables;
    }



    public function getVolumeHoraireVariables()
    {
        $variables = [
            'type'                    => 'Type de volume horaire, chaîne de caractères [enseignement, referentiel]',
            'structure'               => 'Code de la structure (chaine de caractères ou null)',
            'typeIntervention'        => 'Code du type d\'intervention (chaîne de caractères ou null si on est sur du référentiel, attention au respect de la casse)',
            'structureUniversite'     => 'Détermine si la structure fait référence à une université ou non (booléen)',
            'structureExterieur'      => 'Détermine si le volume horaire est fait à l\'extérieur de l\'établissement ou pas (booléen)',
            'structureAffectation'    => 'Détermine si la structure d\'enseignement est la même que la structure d\'affectation de l\intervenant (booléen)',
            'serviceStatutaire'       => 'Le volume horaire peut entrer dans le service ou pas (booléen)',
            'nonPayable'              => 'Le volume horaire est non payable ou pas (booléen)',
            'tauxFi'                  => 'Taux FI à appliquer (flottant)',
            'tauxFa'                  => 'Taux FI à appliquer (flottant)',
            'tauxFc'                  => 'Taux FI à appliquer (flottant)',
            'tauxServiceDu'           => 'Coëfficient multiplicateur du type d\'intervention pour les heures entrant dans le service (flottant)',
            'tauxServiceCompl'        => 'Coëfficient multiplicateur du type d\'intervention pour les heures complémentaires (flottant)',
            'ponderationServiceDu'    => 'Taux de majoration des heures en service (flottant)',
            'ponderationServiceCompl' => 'Taux de majoration des heures complémentaires (flottant)',
            'heures'                  => 'Nombre d\heures (flottant)',
            'param1'                  => 'Paramètre personnalisé 1',
            'param2'                  => 'Paramètre personnalisé 2',
            'param3'                  => 'Paramètre personnalisé 3',
            'param4'                  => 'Paramètre personnalisé 4',
            'param5'                  => 'Paramètre personnalisé 5',
        ];

        $resultVariables = [];
        foreach ($variables as $nom => $description) {
            $resultVariables['volumeHoraire.' . $nom] = [
                'description' => $description,
                'value'       => null,
            ];
        }

        return $resultVariables;
    }



    public function getVolumeHoraireResultats()
    {
        $variables = [
            'heuresServiceFi'          => 'Heures de service en formation initiale (FI) (flottant)',
            'heuresServiceFa'          => 'Heures de service en formation en apprentissage (FA) (flottant)',
            'heuresServiceFc'          => 'Heures de service en formation continue (FC) (flottant)',
            'heuresServiceReferentiel' => 'Heures de service en référentiel (flottant)',

            'heuresNonPayableFi'          => 'Heures non payables en formation initiale (FI) (flottant)',
            'heuresNonPayableFa'          => 'Heures non payables en formation en apprentissage (FA) (flottant)',
            'heuresNonPayableFc'          => 'Heures non payables en formation continue (FC) (flottant)',
            'heuresNonPayableReferentiel' => 'Heures non payables en référentiel (flottant)',

            'heuresComplFi'          => 'Heures complémentaires en formation initiale (FI) (flottant)',
            'heuresComplFa'          => 'Heures complémentaires en formation en apprentissage (FA) (flottant)',
            'heuresComplFc'          => 'Heures complémentaires en formation continue (FC) (flottant)',
            'heuresComplReferentiel' => 'Heures complémentaires en référentiel (flottant)',
            'heuresprimes'           => 'Heures relatives aux primes FC D714-60 ou autres (flottant)',
        ];

        $resultVariables = [];
        foreach ($variables as $nom => $description) {
            $resultVariables[$nom] = [
                'description' => $description,
                'value'       => null,
            ];
        }

        return $resultVariables;
    }
}