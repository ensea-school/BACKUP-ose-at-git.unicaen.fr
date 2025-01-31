<?php

namespace Formule\Model;

use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleServiceIntervenant;
use Formule\Model\Arrondisseur\Ligne;
use Formule\Model\Arrondisseur\Valeur;
use Formule\Service\FormuleServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Hydrator\ExtractionInterface;

class FormuleDetailsExtractor implements ExtractionInterface
{
    use FormuleServiceAwareTrait;

    private FormuleServiceIntervenant $fres;
    private Formule                   $formule;
    private Ligne                     $trace;
    private Intervenant               $intervenantEntity;

    private array $iParams            = [];
    private array $vhParams           = [];
    private array $typesInterventions = [];
    private array $typesHetd          = [];
    private array $intervenant        = [];
    private array $services           = [];
    private array $visibilite         = [
        'horaires'          => false,
        'motifsNonPaiement' => false,
        'majorations'       => false,
        'servicesStatutaire' => false,
    ];



    public function extract(object $object): array
    {
        if (!$object instanceof FormuleServiceIntervenant) {
            throw new \Exception('L\'objet fourni doit être de type FormuleServiceIntervenant');
        }

        // Initialisation des objets source de données : résultat de formule, formule, trace d'arrondisseur
        $this->fres = $object;

        $this->formule = $this->getServiceFormule()->getCurrent($this->fres->getAnnee());
        if (null == $this->fres->getArrondisseurTrace()) {
            $this->getServiceFormule()->calculer($this->fres);
        }
        $this->trace = $this->fres->getArrondisseurTrace();

        $this->intervenantEntity = $this->getServiceFormule()->getEntityManager()->find(Intervenant::class, $this->fres->getIntervenantId());


        // Préparations
        $this->prepareTypesHetd();
        $this->prepareIntervenant();
        $this->prepareParams();
        $this->prepareServices();
        $this->prepareVolumesHoraires();


        // Forge du résultat et envoi
        $result = [
            'iParams'            => $this->iParams,
            'vhParams'           => $this->vhParams,
            'typesInterventions' => $this->typesInterventions,
            'typesHetd'          => $this->finaliserTypesHetd($this->typesHetd),
            'intervenant'        => $this->intervenant,
            'services'           => $this->services,
            'visibilite'         => $this->visibilite,
        ];

        return $result;
    }



    private function prepareTypesHetd(): void
    {
        $this->typesHetd = $this->trace->getValeursUtilisees();
    }



    private function finaliserTypesHetd(array $typesHetd): array
    {
        $tree = [];

        if (in_array(Ligne::TOTAL, $typesHetd)) {
            $tree['Total'] = [];
        }

        $tradCats  = [
            Ligne::CAT_SERVICE     => 'Service',
            Ligne::CAT_COMPL       => 'Heures compl.',
            Ligne::CAT_NON_PAYABLE => 'Non payable',
        ];
        $tradTypes = [
            Ligne::TYPE_FI          => 'FI',
            Ligne::TYPE_FA          => 'FA',
            Ligne::TYPE_FC          => 'FC',
            Ligne::TYPE_REFERENTIEL => 'Référentiel',
        ];

        foreach (Ligne::CATEGORIES as $cat) {
            $tcat = $tradCats[$cat];
            if (in_array($cat, $typesHetd)) {
                $tree[$tcat][] = 'Total';
            }
            if (in_array($cat . Ligne::TYPE_ENSEIGNEMENT, $typesHetd)) {
                $tree[$tcat][] = 'Tot. Ens.';
            }
            foreach (Ligne::TYPES as $type) {
                if (in_array($cat . $type, $typesHetd)) {
                    $ttype = $tradTypes[$type];
                    if (!array_key_exists($tcat, $tree)) {
                        $tree[$tcat] = [];
                    }
                    $tree[$tcat][] = $ttype;
                }
            }
        }

        if (in_array(Ligne::CAT_TYPE_PRIME, $typesHetd)) {
            $tree['Primes'] = [];
        }

        // on repasse le non payable en dernier
        if (isset($tree[$tradCats[Ligne::CAT_NON_PAYABLE]])) {
            $np = $tree[$tradCats[Ligne::CAT_NON_PAYABLE]];
            unset($tree[$tradCats[Ligne::CAT_NON_PAYABLE]]);
            $tree[$tradCats[Ligne::CAT_NON_PAYABLE]] = $np;
        }

        return $tree;
    }



    private function prepareIntervenant(): void
    {
        $structure = $this->intervenantEntity->getStructure();
        if ($structure) {
            $structure = [
                'id'      => $structure->getId(),
                'code'    => $structure->getCode(),
                'libelle' => $structure->getLibelleCourt(),
            ];
        }

        $this->intervenant = [
            'id'                         => $this->fres->getIntervenantId(),
            'anneeId'                    => $this->fres->getAnnee()->getId(),
            'typeVolumeHoraireId'        => $this->fres->getTypeVolumeHoraire()->getId(),
            'etatVolumeHoraireId'        => $this->fres->getEtatVolumeHoraire()->getId(),
            'typeIntervenant'            => [
                'id'      => $this->fres->getTypeIntervenant()->getId(),
                'code'    => $this->fres->getTypeIntervenant()->getCode(),
                'libelle' => $this->fres->getTypeIntervenant()->getLibelle(),
            ],
            'structure'                  => $structure,
            'heuresServiceStatutaire'    => $this->fres->getHeuresServiceStatutaire(),
            'heuresServiceModifie'       => $this->fres->getHeuresServiceModifie(),
            'depassementServiceDuSansHC' => $this->fres->isDepassementServiceDuSansHC(),
            'params'                     => [], // peuplé par prepareParams
            'serviceDu'                  => $this->fres->getServiceDu(),
            'hetd'                       => [],
            'arrondisseur'               => $this->fres->getArrondisseur(),
        ];
        foreach ($this->typesHetd as $typeHetd) {
            $valeur                               = $this->trace->getValeur($typeHetd);
            $this->intervenant['hetd'][$typeHetd] = $this->valeurToJson($valeur);
        }
    }



    private function prepareParams(): void
    {
        // par intervenant
        for ($i = 1; $i <= 5; $i++) {
            if ($plib = $this->formule->{"getIParam$i" . "Libelle"}()) {
                $this->iParams[$i]               = $plib;
                $this->intervenant['params'][$i] = $this->fres->{"getParam$i"}();
            }
        }
        // par volume horaire
        for ($i = 1; $i <= 5; $i++) {
            if ($plib = $this->formule->{"getVhParam$i" . "Libelle"}()) {
                $this->vhParams[$i] = $plib;
            }
        }
    }



    private function prepareServices(): void
    {
        $eIds = [];
        $rIds = [];
        foreach ($this->trace->getSubs() as $sname => $service) {
            if (str_starts_with($sname, 'e')) {
                $eIds[] = (int)substr($sname, 1);
            } elseif (str_starts_with($sname, 'r')) {
                $rIds[] = (int)substr($sname, 1);
            }
            $this->services[$sname] = [
                'type'            => str_starts_with($sname, 'e') ? 'enseignement' : 'referentiel',
                'id'              => substr($sname, 1),
                'volumesHoraires' => [],
                'hetd'            => [],
            ];
            foreach ($this->typesHetd as $typeHetd) {
                $valeur                                    = $service->getValeur($typeHetd);
                $this->services[$sname]['hetd'][$typeHetd] = $this->valeurToJson($valeur);
            }
        }
        if (!empty($eIds)) {
            $sql   = "
            SELECT
              s.id              id,
              str.id            structure_id,
              str.libelle_court structure_libelle,
              etab.id           etablissement_id,
              etab.libelle      etablissement_libelle,
              s.description     description,
              e.id              etape_id,
              e.code            etape_code,
              e.libelle         etape_libelle,
              ep.id             ep_id,
              ep.code           ep_code,
              ep.libelle        ep_libelle,
              ep.taux_fi        taux_fi,
              ep.taux_fa        taux_fa,
              ep.taux_fc        taux_fc
            FROM
              service s
              JOIN etablissement etab ON etab.id = s.etablissement_id
              LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
              LEFT JOIN etape e ON e.id = ep.etape_id
              LEFT JOIN structure str ON str.id = ep.structure_id
            WHERE
              s.id IN (" . implode(',', $eIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $sdata = [
                    'id'                 => (int)$data['ID'],
                    'structure'          => $data['STRUCTURE_ID'] ? [
                        'id'      => (int)$data['STRUCTURE_ID'],
                        'libelle' => $data['STRUCTURE_LIBELLE'],
                    ] : null,
                    'etablissement'      => [
                        'id'      => (int)$data['ETABLISSEMENT_ID'],
                        'libelle' => $data['ETABLISSEMENT_LIBELLE'],
                    ],
                    'description'        => $data['DESCRIPTION'],
                    'etape'              => $data['ETAPE_ID'] ? [
                        'id'      => (int)$data['ETAPE_ID'],
                        'code'    => $data['ETAPE_CODE'],
                        'libelle' => $data['ETAPE_LIBELLE'],
                    ] : null,
                    'elementPedagogique' => $data['EP_ID'] ? [
                        'id'      => (int)$data['EP_ID'],
                        'code'    => $data['EP_CODE'],
                        'libelle' => $data['EP_LIBELLE'],
                    ] : null,
                    'tauxFi'             => (float)$data['TAUX_FI'],
                    'tauxFa'             => (float)$data['TAUX_FA'],
                    'tauxFc'             => (float)$data['TAUX_FC'],
                ];

                $this->services['e' . $data['ID']] = array_merge($this->services['e' . $data['ID']], $sdata);
            }
        }
        if (!empty($rIds)) {
            $sql   = "
            SELECT
              sr.id             id,
              str.id            structure_id,
              str.libelle_court structure_libelle,
              fr.id             fonction_id,
              fr.code           fonction_code,
              fr.libelle_court  fonction_libelle,
              sr.commentaires   description
            FROM
              service_referentiel sr
              JOIN structure str ON str.id = sr.structure_id
              JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
            WHERE
              sr.id IN (" . implode(',', $rIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $sdata                             = [
                    'id'          => (int)$data['ID'],
                    'structure'   => [
                        'id'      => (int)$data['STRUCTURE_ID'],
                        'libelle' => $data['STRUCTURE_LIBELLE'],
                    ],
                    'fonction'    => [
                        'id'      => (int)$data['FONCTION_ID'],
                        'code'    => $data['FONCTION_CODE'],
                        'libelle' => $data['FONCTION_LIBELLE'],
                    ],
                    'description' => $data['DESCRIPTION'],
                ];
                $this->services['r' . $data['ID']] = array_merge($this->services['r' . $data['ID']], $sdata);
            }
        }
    }



    private function prepareVolumesHoraires(): void
    {
        $eIds = [];
        $rIds = [];
        foreach ($this->trace->getSubs() as $sname => $service) {
            foreach ($service->getSubs() as $subVh) {
                $vh = $subVh->getVolumeHoraire();
                if ($vh->getVolumeHoraire()) {
                    $sId    = 'e' . $vh->getService();
                    $vhId   = 'e' . $vh->getVolumeHoraire();
                    $eIds[] = $vh->getVolumeHoraire();
                } else {
                    $sId    = 'r' . $vh->getServiceReferentiel();
                    $vhId   = 'r' . $vh->getVolumeHoraireReferentiel();
                    $rIds[] = $vh->getVolumeHoraireReferentiel();
                }
                $this->services[$sId]['volumesHoraires'][$vhId] = [
                    'type'                    => str_starts_with($vhId, 'e') ? 'enseignement' : 'referentiel',
                    'id'                      => substr($vhId, 1),
                    'tauxServiceDu'           => $vh->getTauxServiceDu(),
                    'tauxServiceCompl'        => $vh->getTauxServiceCompl(),
                    'ponderationServiceDu'    => $vh->getPonderationServiceDu(),
                    'ponderationServiceCompl' => $vh->getPonderationServiceCompl(),
                    'nonPayable'              => $vh->isNonPayable(),
                    'serviceStatutaire'       => $vh->isServiceStatutaire(),
                    'params'                  => [],
                ];
                if (!$this->visibilite['majorations'] && ($vh->getPonderationServiceDu() != 1.0 && $vh->getPonderationServiceCompl() != 1.0)) {
                    $this->visibilite['majorations'] = true;
                }
                if (!$this->visibilite['motifsNonPaiement'] && $vh->isNonPayable()){
                    $this->visibilite['motifsNonPaiement'] = true;
                }
                if (!$this->visibilite['servicesStatutaire'] && !$vh->isServiceStatutaire()){
                    $this->visibilite['servicesStatutaire'] = true;
                }
                foreach ($this->vhParams as $i => $null) {
                    $this->services[$sId]['volumesHoraires'][$vhId]['params'][$i] = $vh->{"getParam$i"}();
                }
                foreach ($this->typesHetd as $typeHetd) {
                    $valeur                                                            = $subVh->getValeur($typeHetd);
                    $this->services[$sId]['volumesHoraires'][$vhId]['hetd'][$typeHetd] = $this->valeurToJson($valeur);
                }
            }
        }
        if (!empty($eIds)) {
            $sql   = "
            SELECT
              vh.service_id         service_id,
              vh.id                 id,
              vh.heures             heures,
              vh.horaire_debut      horaire_debut,
              vh.horaire_fin        horaire_fin,
              p.id                  periode_id,
              p.code                periode_code,
              p.libelle_court       periode_libelle,
              ti.id                 type_intervention_id,
              ti.code               type_intervention_code,
              ti.libelle            type_intervention_libelle,
              mnp.id                motif_non_paiement_id,
              mnp.code              motif_non_paiement_code,
              mnp.libelle_court     motif_non_paiement_libelle,
              vh.histo_creation     histo_creation,
              hcu.id                histo_createur_id,
              hcu.display_name      histo_createur_libelle,
              vh.histo_modification histo_modification,
              hmu.id                histo_modificateur_id,
              hmu.display_name      histo_modificateur_libelle
            FROM
              volume_horaire vh
              JOIN periode p ON p.id = vh.periode_id
              JOIN type_intervention ti ON ti.id = vh.type_intervention_id
              LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
              LEFT JOIN utilisateur hcu ON hcu.id = vh.histo_createur_id
              LEFT JOIN utilisateur hmu ON hmu.id = vh.histo_modificateur_id
            WHERE
              vh.id IN (" . implode(',', $eIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $sdata = [
                    'id'               => (int)$data['ID'],
                    'heures'           => (float)$data['HEURES'],
                    'horaireDebut'     => $data['HORAIRE_DEBUT'],
                    'horaireFin'       => $data['HORAIRE_FIN'],
                    'periode'          => [
                        'id'      => (int)$data['PERIODE_ID'],
                        'code'    => $data['PERIODE_CODE'],
                        'libelle' => $data['PERIODE_LIBELLE'],
                    ],
                    'typeIntervention' => [
                        'id'      => (int)$data['TYPE_INTERVENTION_ID'],
                        'code'    => $data['TYPE_INTERVENTION_CODE'],
                        'libelle' => $data['TYPE_INTERVENTION_LIBELLE'],
                    ],
                    'motifNonPaiement' => $data['MOTIF_NON_PAIEMENT_ID'] ? [
                        'id'      => (int)$data['MOTIF_NON_PAIEMENT_ID'],
                        'code'    => $data['MOTIF_NON_PAIEMENT_CODE'],
                        'libelle' => $data['MOTIF_NON_PAIEMENT_LIBELLE'],
                    ] : null,
                    'histo'            => [
                        'creation'     => $data['HISTO_CREATION'],
                        'createur'     => [
                            'id'      => $data['HISTO_CREATEUR_ID'],
                            'libelle' => $data['HISTO_CREATEUR_LIBELLE'],
                        ],
                        'modification' => $data['HISTO_MODIFICATION'],
                        'modificateur' => [
                            'id'      => $data['HISTO_MODIFICATEUR_ID'],
                            'libelle' => $data['HISTO_MODIFICATEUR_LIBELLE'],
                        ],
                    ],
                ];

                if (($sdata['horaireDebut'] || $sdata['horaireFin']) && !$this->visibilite['horaires']) {
                    $this->visibilite['horaires'] = true;
                }
                if ($sdata['motifNonPaiement'] && !$this->visibilite['motifsNonPaiement']) {
                    $this->visibilite['motifsNonPaiement'] = true;
                }
                $vhId                                           = 'e' . $data['ID'];
                $sId                                            = 'e' . $data['SERVICE_ID'];
                $this->services[$sId]['volumesHoraires'][$vhId] = array_merge($this->services[$sId]['volumesHoraires'][$vhId], $sdata);
            }
        }
        if (!empty($rIds)) {
            $sql   = "
            SELECT
              sr.id                  service_referentiel_id,
              vhr.id                 id,
              vhr.heures             heures,
              mnp.id                 motif_non_paiement_id,
              mnp.code               motif_non_paiement_code,
              mnp.libelle_court      motif_non_paiement_libelle,
              vhr.histo_creation     histo_creation,
              hcu.id                 histo_createur_id,
              hcu.display_name       histo_createur_libelle,
              vhr.histo_modification histo_modification,
              hmu.id                 histo_modificateur_id,
              hmu.display_name       histo_modificateur_libelle
            FROM
              volume_horaire_ref vhr
              JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
              LEFT JOIN motif_non_paiement mnp ON mnp.id = sr.motif_non_paiement_id
              LEFT JOIN utilisateur hcu ON hcu.id = vhr.histo_createur_id
              LEFT JOIN utilisateur hmu ON hmu.id = vhr.histo_modificateur_id
            WHERE
              vhr.id IN (" . implode(',', $rIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $sdata = [
                    'id'               => (int)$data['ID'],
                    'heures'           => (float)$data['HEURES'],
                    'motifNonPaiement' => $data['MOTIF_NON_PAIEMENT_ID'] ? [
                        'id'      => (int)$data['MOTIF_NON_PAIEMENT_ID'],
                        'code'    => $data['MOTIF_NON_PAIEMENT_CODE'],
                        'libelle' => $data['MOTIF_NON_PAIEMENT_LIBELLE'],
                    ] : null,
                    'histo'            => [
                        'creation'     => $data['HISTO_CREATION'],
                        'createur'     => [
                            'id'      => $data['HISTO_CREATEUR_ID'],
                            'libelle' => $data['HISTO_CREATEUR_LIBELLE'],
                        ],
                        'modification' => $data['HISTO_MODIFICATION'],
                        'modificateur' => [
                            'id'      => $data['HISTO_MODIFICATEUR_ID'],
                            'libelle' => $data['HISTO_MODIFICATEUR_LIBELLE'],
                        ],
                    ],
                ];
                if ($sdata['motifNonPaiement'] && !$this->visibilite['motifsNonPaiement']) {
                    $this->visibilite['motifsNonPaiement'] = true;
                }
                $vhId                                           = 'r' . $data['ID'];
                $sId                                            = 'r' . $data['SERVICE_REFERENTIEL_ID'];
                $this->services[$sId]['volumesHoraires'][$vhId] = array_merge($this->services[$sId]['volumesHoraires'][$vhId], $sdata);
            }
        }
    }



    private function valeurToJson(Valeur $valeur): array
    {
        return [
            'valeur'   => $valeur->getValueFinale(),
            'arrondi'  => $valeur->getArrondi(),
            'original' => $valeur->getValue(),
        ];
    }
}