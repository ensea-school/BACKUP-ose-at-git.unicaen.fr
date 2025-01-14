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
            'typesHetd'          => $this->typesHetd,
            'intervenant'        => $this->intervenant,
            'services'           => $this->services,
        ];

        return $result;
    }



    private function prepareTypesHetd(): void
    {

        /* Initialisation des types d'heures HETD utiles */
        foreach ($this->trace->getValeurs() as $vn => $valeur) {
            if ($valeur->getValue() != 0.0) {
                $typesHetd[$vn] = true;
            }
            foreach ($this->trace->getSubs() as $sub) {
                foreach ($sub->getValeurs() as $vn => $valeur) {
                    if ($valeur->getValue() != 0.0) {
                        $typesHetd[$vn] = true;
                    }
                }
                foreach ($sub->getSubs() as $ssub) {
                    foreach ($ssub->getValeurs() as $vn => $valeur) {
                        if ($valeur->getValue() != 0.0) {
                            $typesHetd[$vn] = true;
                        }
                    }
                }
            }
        }
        $this->typesHetd = array_keys($typesHetd);
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
        ];
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
                    'tauxFa'             => (float)$data['TAUX_FI'],
                    'tauxFc'             => (float)$data['TAUX_FI'],
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
            foreach ($service->getSubs() as $vh) {
                if ($vh->getVolumeHoraire()->getVolumeHoraire()) {
                    $sId    = 'e' . $vh->getVolumeHoraire()->getService();
                    $vhId   = 'e' . $vh->getVolumeHoraire()->getVolumeHoraire();
                    $eIds[] = $vh->getVolumeHoraire()->getVolumeHoraire();
                } else {
                    $sId    = 'r' . $vh->getVolumeHoraire()->getServiceReferentiel();
                    $vhId   = 'r' . $vh->getVolumeHoraire()->getVolumeHoraireReferentiel();
                    $rIds[] = $vh->getVolumeHoraire()->getVolumeHoraireReferentiel();
                }
                $this->services[$sId]['volumesHoraires'][$vhId] = [
                    'type' => str_starts_with($vhId, 'e') ? 'enseignement' : 'referentiel',
                    'id'   => substr($vhId, 1),
                ];
                foreach ($this->typesHetd as $typeHetd) {
                    $valeur                                                            = $service->getValeur($typeHetd);
                    $this->services[$sId]['volumesHoraires'][$vhId]['hetd'][$typeHetd] = $this->valeurToJson($valeur);
                }
            }
        }
        if (!empty($eIds)) {
            $sql   = "
            SELECT
              vh.service_id     service_id,
              vh.id             id,
              vh.heures         heures,
              vh.horaire_debut  horaire_debut,
              vh.horaire_fin    horaire_fin,
              p.id              periode_id,
              p.code            periode_code,
              p.libelle_court   periode_libelle,
              ti.id             type_intervention_id,
              ti.code           type_intervention_id,
              ti.libelle        type_intervention_id,
              mnp.id            motif_non_paiement_id,
              mnp.code          motif_non_paiement_code,
              mnp.libelle_court motif_non_paiement_code
            FROM
              volume_horaire vh
              JOIN periode p ON p.id = vh.periode_id
              JOIN type_intervention ti ON ti.id = vh.type_intervention_id
              LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
            WHERE
              vh.id IN (" . implode(',', $eIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $sdata                                          = [
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
                    'motifNonPaiement' => [
                        'id'      => (int)$data['MOTIF_NON_PAIEMENT_ID'],
                        'code'    => $data['MOTIF_NON_PAIEMENT_CODE'],
                        'libelle' => $data['MOTIF_NON_PAIEMENT_LIBELLE'],
                    ],
                ];
                $vhId                                           = 'e' . $data['ID'];
                $sId                                            = 'e' . $data['SERVICE_ID'];
                $this->services[$sId]['volumesHoraires'][$vhId] = array_merge($this->services[$sId]['volumesHoraires'][$vhId], $sdata);
            }
        }
        if (!empty($rIds)) {
            $sql   = "
            SELECT
              sr.id             service_referentiel_id,
              vhr.id            id,
              vhr.heures        heures,
              mnp.id            motif_non_paiement_id,
              mnp.code          motif_non_paiement_code,
              mnp.libelle_court motif_non_paiement_code
            FROM
              volume_horaire_ref vhr
              JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
              LEFT JOIN motif_non_paiement mnp ON mnp.id = sr.motif_non_paiement_id
            WHERE
              vhr.id IN (" . implode(',', $rIds) . ")";
            $query = $this->getServiceFormule()->getEntityManager()->getConnection()->executeQuery($sql);
            while ($data = $query->fetchAssociative()) {
                $sdata                                          = [
                    'id'               => (int)$data['ID'],
                    'heures'           => (float)$data['HEURES'],
                    'motifNonPaiement' => [
                        'id'      => (int)$data['MOTIF_NON_PAIEMENT_ID'],
                        'code'    => $data['MOTIF_NON_PAIEMENT_CODE'],
                        'libelle' => $data['MOTIF_NON_PAIEMENT_LIBELLE'],
                    ],
                ];
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