<?php

namespace Service\Service;

use Application\Entity\Db\Structure;
use Application\Service\AbstractService;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use Service\Entity\Recherche;

class ResumeService extends AbstractService
{
    use TypeInterventionServiceAwareTrait;

    /**
     * Retourne les données du TBL des services en fonction des critères de recherche transmis
     *
     * @param Recherche $recherche
     *
     * @return array
     */
    public function getTableauBord(Recherche $recherche, array $options = [])
    {
        // initialisation
        $defaultOptions    = [
            'tri'              => 'intervenant',   // [intervenant, hetd]
            'columns'          => [],              // Liste des colonnes utiles, hors colonnes liées aux types d'intervention!!
            'ignored-columns'  => [],              // Liste des colonnes à ne pas récupérer, hors colonnes liées aux types d'intervention!!
            'isoler-non-payes' => true,            // boolean
            'regroupement'     => 'service',       // [service, intervenant]
            'composante'       => null,            // Composante qui en fait la demande
        ];
        $options           = array_merge($defaultOptions, $options);
        $annee             = $this->getServiceContext()->getAnnee();
        $data              = [];
        $shown             = [];
        $typesIntervention = [];
        $invertTi          = [];
        $numericColunms    = [
            'service-statutaire',
            'service-du-modifie',
            'heures-non-payees',
            'heures-ref',
            'service-fi',
            'service-fa',
            'service-fc',
            'service-referentiel',
            'heures-compl-fi',
            'heures-compl-fa',
            'heures-compl-fc',
            'heures-compl-fc-majorees',
            'heures-compl-referentiel',
            'total',
            'solde',
        ];
        $dateColumns       = [
            'service-date-modification',
            'intervenant-date-naissance',
            'date-cloture-service-realise',
        ];
        $addableColumns    = [
            '__total__',
            'heures-non-payees',
            'heures-ref',
            'service-fi',
            'service-fa',
            'service-fc',
            'service-referentiel',
            'heures-compl-fi',
            'heures-compl-fa',
            'heures-compl-fc',
            'heures-compl-fc-majorees',
            'heures-compl-referentiel',
            'total',
        ];

        // requêtage
        $conditions = [
            'annee_id = ' . $annee->getId(),
        ];
        if ($c1 = $recherche->getTypeVolumeHoraire()) $conditions['type_volume_horaire_id'] = '(type_volume_horaire_id = -1 OR type_volume_horaire_id = ' . $c1->getId() . ')';
        if ($c2 = $recherche->getEtatVolumeHoraire()) $conditions['etat_volume_horaire_id'] = '(etat_volume_horaire_id = -1 OR etat_volume_horaire_id = ' . $c2->getId() . ')';
        if ($c3 = $recherche->getTypeIntervenant()) $conditions['type_intervenant_id'] = '(type_intervenant_id = -1 OR type_intervenant_id = ' . $c3->getId() . ')';
        if ($c4 = $recherche->getIntervenant()) $conditions['intervenant_id'] = '(intervenant_id = -1 OR intervenant_id = ' . $c4->getId() . ')';
        //if ($c5 = $recherche->getNiveauFormation()    ) $conditions['niveau_formation_id']    = '(niveau_formation_id = -1 OR niveau_formation_id = '    . $c5->getId().')';
        if ($c6 = $recherche->getEtape()) $conditions['etape_id'] = '(etape_id = -1 OR etape_id = ' . $c6->getId() . ')';
        if ($c7 = $recherche->getElementPedagogique()) $conditions['element_pedagogique_id'] = '(element_pedagogique_id = -1 OR element_pedagogique_id = ' . $c7->getId() . ')';
        if ($c8 = $recherche->getStructureAff()) $conditions['structure_aff_id'] = '(structure_aff_id IS NULL OR structure_aff_id = -1 OR structure_aff_id = ' . $c8->getId() . ')';
        if ($c9 = $recherche->getStructureEns()) $conditions['structure_ens_id'] = '(structure_ens_id = -1 OR structure_ens_id = ' . $c9->getId() . ')';

        if ($options['composante'] instanceof Structure) {
            $id                       = (int)$options['composante']->getId();
            $conditions['composante'] = "(structure_aff_id IS NULL OR structure_aff_id = -1 OR structure_aff_id = $id OR structure_ens_id = -1 OR structure_ens_id = $id)";
        }

        switch ($options['tri']) {
            case 'intervenant':
                $orderBy = 'INTERVENANT_NOM, SERVICE_STRUCTURE_AFF_LIBELLE, SERVICE_STRUCTURE_ENS_LIBELLE, ETAPE_LIBELLE, ELEMENT_LIBELLE';
            break;
            case 'hetd':
                $orderBy = 'SOLDE, INTERVENANT_NOM, SERVICE_STRUCTURE_AFF_LIBELLE, SERVICE_STRUCTURE_ENS_LIBELLE, ETAPE_LIBELLE, ELEMENT_LIBELLE';
            break;
            default:
                $orderBy = 'INTERVENANT_NOM, SERVICE_STRUCTURE_AFF_LIBELLE, SERVICE_STRUCTURE_ENS_LIBELLE, ETAPE_LIBELLE, ELEMENT_LIBELLE';
            break;
        }

        $sql  = 'SELECT * FROM V_EXPORT_SERVICE WHERE ' . implode(' AND ', $conditions) . ' '
            . 'ORDER BY ' . $orderBy;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        $dateExtraction = new \DateTime();

        // récupération des données
        while ($d = $stmt->fetchAssociative()) {

            if ($options['isoler-non-payes'] && (int)$d['HEURES_NON_PAYEES'] === 1) {
                $d['HEURES_NON_PAYEES'] = $d['HEURES'];
                $d['HEURES']            = 0;
            }

            if ('intervenant' === $options['regroupement']) {
                $sid = $d['INTERVENANT_ID'];
            } else {
                $sid = $d['SERVICE_ID'] ? $d['SERVICE_ID'] . '_' . $d['PERIODE_ID'] : $d['ID'];
            }

            $ds = [
                '__total__'                 => (float)$d['HEURES'] + (float)$d['HEURES_NON_PAYEES'] + (float)$d['HEURES_REF'] + (float)$d['TOTAL'],
                'type-etat'                 => $d['TYPE_ETAT'],
                'date'                      => $dateExtraction,
                'service-date-modification' => $d['SERVICE_DATE_MODIFICATION'],
                'annee-libelle'             => (string)$annee,

                'intervenant-code'               => $d['INTERVENANT_CODE'],
                'intervenant-id'                 => $d['INTERVENANT_ID'],
                'intervenant-nom'                => $d['INTERVENANT_NOM'],
                'intervenant-date-naissance'     => $d['INTERVENANT_DATE_NAISSANCE'],
                'intervenant-statut-libelle'     => $d['INTERVENANT_STATUT_LIBELLE'],
                'intervenant-type-code'          => $d['INTERVENANT_TYPE_CODE'],
                'intervenant-type-libelle'       => $d['INTERVENANT_TYPE_LIBELLE'],
                'intervenant-grade-code'         => $d['INTERVENANT_GRADE_CODE'],
                'intervenant-grade-libelle'      => $d['INTERVENANT_GRADE_LIBELLE'],
                'intervenant-discipline-code'    => $d['INTERVENANT_DISCIPLINE_CODE'],
                'intervenant-discipline-libelle' => $d['INTERVENANT_DISCIPLINE_LIBELLE'],
                'heures-service-statutaire'      => (float)$d['SERVICE_STATUTAIRE'],
                'heures-service-du-modifie'      => (float)$d['SERVICE_DU_MODIFIE'],
                'service-structure-aff-libelle'  => $d['SERVICE_STRUCTURE_AFF_LIBELLE'],

                'service-structure-ens-libelle' => $d['SERVICE_STRUCTURE_ENS_LIBELLE'],
                'groupe-type-formation-libelle' => $d['GROUPE_TYPE_FORMATION_LIBELLE'],
                'type-formation-libelle'        => $d['TYPE_FORMATION_LIBELLE'],
                'etape-niveau'                  => empty($d['ETAPE_NIVEAU']) ? null : (int)$d['ETAPE_NIVEAU'],
                'etape-code'                    => $d['ETAPE_CODE'],
                'etape-etablissement-libelle'   => $d['ETAPE_LIBELLE'] ? $d['ETAPE_LIBELLE'] : ($d['SERVICE_REF_FORMATION'] ? $d['SERVICE_REF_FORMATION'] : $d['ETABLISSEMENT_LIBELLE']),
                'element-code'                  => $d['ELEMENT_CODE'],
                'element-fonction-libelle'      => $d['ELEMENT_LIBELLE'] ? $d['ELEMENT_LIBELLE'] : $d['FONCTION_REFERENTIEL_LIBELLE'],
                'element-discipline-code'       => $d['ELEMENT_DISCIPLINE_CODE'],
                'element-discipline-libelle'    => $d['ELEMENT_DISCIPLINE_LIBELLE'],
                'element-taux-fi'               => (float)$d['ELEMENT_TAUX_FI'],
                'element-taux-fc'               => (float)$d['ELEMENT_TAUX_FC'],
                'element-taux-fa'               => (float)$d['ELEMENT_TAUX_FA'],
                'commentaires'                  => $d['COMMENTAIRES'],
                'element-ponderation-compl'     => $d['ELEMENT_PONDERATION_COMPL'] === null ? null : (float)$d['ELEMENT_PONDERATION_COMPL'],
                'element-source-libelle'        => $d['ELEMENT_SOURCE_LIBELLE'],

                'periode-libelle'              => $d['PERIODE_LIBELLE'],
                'heures-non-payees'            => (float)$d['HEURES_NON_PAYEES'],
                // types d'intervention traités en aval
                'heures-ref'                   => (float)$d['HEURES_REF'],
                'service-fi'                   => (float)$d['SERVICE_FI'],
                'service-fa'                   => (float)$d['SERVICE_FA'],
                'service-fc'                   => (float)$d['SERVICE_FC'],
                'service-referentiel'          => (float)$d['SERVICE_REFERENTIEL'],
                'heures-compl-fi'              => (float)$d['HEURES_COMPL_FI'],
                'heures-compl-fa'              => (float)$d['HEURES_COMPL_FA'],
                'heures-compl-fc'              => (float)$d['HEURES_COMPL_FC'],
                'heures-compl-fc-majorees'     => (float)$d['HEURES_COMPL_FC_MAJOREES'],
                'heures-compl-referentiel'     => (float)$d['HEURES_COMPL_REFERENTIEL'],
                'total'                        => (float)$d['HEURES_COMPL_FI'] + (float)$d['HEURES_COMPL_FA'] + (float)$d['HEURES_COMPL_FC'] + (float)$d['HEURES_COMPL_FC_MAJOREES'] + (float)$d['HEURES_COMPL_REFERENTIEL'],
                'solde'                        => (float)$d['SOLDE'],
                'date-cloture-service-realise' => $d['DATE_CLOTURE_REALISE'],
            ];

            if (
                $ds['heures-service-statutaire'] > 0
                && $ds['heures-service-statutaire'] + $ds['heures-service-du-modifie'] == 0
                && empty($ds['etape-code'])
            ) {
                $ds['__total__']++; // pour que le cas spécifique des décharges totales soit pris en compte
            }

            if ($d['TYPE_INTERVENTION_ID'] != null) {
                $tid = $d['TYPE_INTERVENTION_ID'];
                if (!isset($typesIntervention[$tid])) {
                    $typesIntervention[$tid] = $this->getServiceTypeIntervention()->get($tid);
                }
                $typeIntervention                                              = $typesIntervention[$tid];
                $invertTi['type-intervention-' . $typeIntervention->getCode()] = $typeIntervention->getId();
                $ds['type-intervention-' . $typeIntervention->getCode()]       = (float)$d['HEURES'];
            }
            foreach ($ds as $column => $value) {
                if (empty($options['columns']) || in_array($column, $options['columns']) || 0 === strpos($column, 'type-intervention-')) {
                    if (!isset($shown[$column])) $shown[$column] = 0;
                    if (is_float($value)) {
                        $shown[$column] += $value;
                    } else {
                        $shown[$column] += empty($value) ? 0 : 1;
                    }
                }
                if (in_array($column, $options['ignored-columns'])) {
                    $shown[$column] = 0;
                }
            }
            if (!isset($data[$sid])) {
                $data[$sid] = $ds;
            } else {
                foreach ($ds as $column => $value) {
                    if (empty($options['columns']) || in_array($column, $options['columns']) || 0 === strpos($column, 'type-intervention-')) {
                        if (in_array($column, $addableColumns) || 0 === strpos($column, 'type-intervention-')) {
                            if (!isset($data[$sid][$column])) {
                                $data[$sid][$column] = $value;
                            } // pour les types d'intervention no initialisés
                            else $data[$sid][$column] += $value;
                        } elseif ($value != $data[$sid][$column]) {
                            $data[$sid][$column] = null;
                        }
                    }
                }
            }
        }

        // tri et préparation des entêtes
        $head = [
            'type-etat'                 => 'Type État',
            'date'                      => 'Date d\'extraction',
            'annee-libelle'             => 'Année universitaire',
            'service-date-modification' => 'Date de modif. du service',

            'intervenant-code'               => 'Code intervenant',
            'intervenant-id'                 => 'Id intervenant',
            'intervenant-nom'                => 'Intervenant',
            'intervenant-date-naissance'     => 'Date de naissance',
            'intervenant-statut-libelle'     => 'Statut intervenant',
            'intervenant-type-code'          => 'Type d\'intervenant (Code)',
            'intervenant-type-libelle'       => 'Type d\'intervenant',
            'intervenant-grade-code'         => 'Grade (Code)',
            'intervenant-grade-libelle'      => 'Grade',
            'intervenant-discipline-code'    => 'Discipline intervenant (Code)',
            'intervenant-discipline-libelle' => 'Discipline intervenant',
            'heures-service-statutaire'      => 'Service statutaire',
            'heures-service-du-modifie'      => 'Modification de service du',
            'service-structure-aff-libelle'  => 'Structure d\'affectation',

            'service-structure-ens-libelle' => 'Structure d\'enseignement',
            'groupe-type-formation-libelle' => 'Groupe de type de formation',
            'type-formation-libelle'        => 'Type de formation',
            'etape-niveau'                  => 'Niveau',
            'etape-code'                    => 'Code formation',
            'etape-etablissement-libelle'   => 'Formation ou établissement',
            'element-code'                  => 'Code enseignement',
            'element-fonction-libelle'      => 'Enseignement ou fonction référentielle',
            'element-discipline-code'       => 'Discipline ens. (Code)',
            'element-discipline-libelle'    => 'Discipline ens.',
            'element-taux-fi'               => 'Taux FI',
            'element-taux-fc'               => 'Taux FC',
            'element-taux-fa'               => 'Taux FA',
            'commentaires'                  => 'Commentaires',
            'element-ponderation-compl'     => 'Majoration',
            'element-source-libelle'        => 'Source enseignement',
            'periode-libelle'               => 'Période',
            'heures-non-payees'             => 'Heures non payées',
        ];
        uasort($typesIntervention, function ($ti1, $ti2) {
            return $ti1->getOrdre() - $ti2->getOrdre();
        });
        foreach ($typesIntervention as $typeIntervention) {
            /* @var $typeIntervention TypeIntervention */
            $head['type-intervention-' . $typeIntervention->getCode()] = $typeIntervention->getCode();
        }
        $head['heures-ref']                   = 'Référentiel';
        $head['service-fi']                   = 'HETD Service FI';
        $head['service-fa']                   = 'HETD Service FA';
        $head['service-fc']                   = 'HETD Service FC';
        $head['service-referentiel']          = 'HETD Service Référentiel';
        $head['heures-compl-fi']              = 'HETD Compl. FI';
        $head['heures-compl-fa']              = 'HETD Compl. FA';
        $head['heures-compl-fc']              = 'HETD Compl. FC';
        $head['heures-compl-fc-majorees']     = 'HETD Compl. FC D714-60';
        $head['heures-compl-referentiel']     = 'HETD Compl. référentiel';
        $head['total']                        = 'Total HETD';
        $head['solde']                        = 'Solde HETD';
        $head['date-cloture-service-realise'] = 'Clôture du service réalisé';

        // suppression des informations superflues
        foreach ($shown as $column => $visibility) {
            if (isset($head[$column]) && empty($visibility)) {
                unset($head[$column]);
                if (isset($invertTi[$column])) {
                    unset($typesIntervention[$invertTi[$column]]);
                }
            }
        }
        $columns = array_keys($head);
        foreach ($data as $sid => $d) {
            if (0 == $d['__total__']) {
                unset($data[$sid]); // pas d'affichage pour quelqu'un qui n'a rien
            } else {
                $data[$sid] = [];
                foreach ($columns as $column) {
                    $value = isset($d[$column]) ? $d[$column] : null;
                    if (null === $value && (in_array($column, $numericColunms) || 0 === strpos($column, 'type-intervention-'))) {
                        $value = 0;
                    }

                    if (in_array($column, $dateColumns)) {
                        if (empty($value)) $value = null; else $value = \DateTime::createFromFormat('Y-m-d', substr($value, 0, 10));
                    }

                    $data[$sid][$column] = $value;
                }
            }
        }

        return [
            'head'               => $head,
            'data'               => $data,
            'types-intervention' => $typesIntervention,
        ];
    }
}