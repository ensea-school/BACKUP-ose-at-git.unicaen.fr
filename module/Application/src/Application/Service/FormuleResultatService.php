<?php

namespace Application\Service;

use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\ServiceReferentielServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireServiceAwareTrait;

/**
 * Description of FormuleResultat
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleResultatService extends AbstractEntityService
{
    use ServiceServiceAwareTrait;
    use ServiceReferentielServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use VolumeHoraireReferentielServiceAwareTrait;
    use StructureServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\FormuleResultat::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'form_r';
    }



    /**
     * Retourne le volume d'heures prévisionnelles faites pour une structure donnée, en année universitaire (par défaut)
     * ou bien par année civile en appliquant la règle des 4/10 / 6/10.
     *
     * @param Structure $structure
     *
     * @return float
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTotalPrevisionnelValide(Structure $structure = null)
    {
        if (!$structure) return $this->getTotalPrevisionnelValideWS(); // on ByPasse!!!

        $params = [
            'structure' => (integer)$structure->getId(),
            'annee'     => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql = 'SELECT heures FROM V_HETD_PREV_VAL_STRUCT WHERE structure_id = :structure AND annee_id = :annee';
        $sr  = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetch();

        if (isset($sr['HEURES'])) {
            return (float)$sr['HEURES'];
        } else {
            return (float)0;
        }
    }



    private function getTotalPrevisionnelValideWS()
    {
        $params = [
            'annee' => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql  = 'SELECT structure_id, heures FROM V_HETD_PREV_VAL_STRUCT WHERE annee_id = :annee';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        $res = ['total' => 0];
        while ($d = $stmt->fetch()) {
            $structureId = (int)$d['STRUCTURE_ID'];
            $heures      = (float)$d['HEURES'];

            $res[$structureId] = $heures;
            $res['total']      += $heures;
            $res['total']      += $heures;
        }

        return $res;
    }



    /**
     * @param Intervenant       $intervenant
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param EtatVolumeHoraire $etatVolumeHoraire
     *
     * @return array
     */
    public function getData(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $params = [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
            'etatVolumeHoraire' => $etatVolumeHoraire->getId(),
        ];

        /* Données générales */
        $data = [
            'STRUCTURE'                      => $intervenant->getStructure(),
            'typeVolumeHoraire'              => $typeVolumeHoraire,
            'etatVolumeHoraire'              => $etatVolumeHoraire,
            'HEURES_SERVICE_STATUTAIRE'      => $intervenant->getStatut()->getServiceStatutaire(),
            'HEURES_SERVICE_MODIFIE'         => 0,
            'HEURES_DECHARGE'                => 0,
            'DEPASSEMENT_SERVICE_DU_SANS_HC' => false,
            's'                              => [],
            'r'                              => [],
            'types-intervention'             => [],
            'has-ponderation-service-compl'  => false,
            'has-calcul'                     => false,
        ];

        $sql = "SELECT * FROM v_formule_intervenant WHERE intervenant_id = :intervenant";
        $id  = $conn->fetchAssoc($sql, $params);
        if ($id) {
            $data['HEURES_SERVICE_STATUTAIRE']      = $id['HEURES_SERVICE_STATUTAIRE'];
            $data['HEURES_SERVICE_MODIFIE']         = $id['HEURES_SERVICE_MODIFIE'];
            $data['HEURES_DECHARGE']                = $id['HEURES_DECHARGE'];
            $data['DEPASSEMENT_SERVICE_DU_SANS_HC'] = $id['DEPASSEMENT_SERVICE_DU_SANS_HC'] == '1';
        }

        /* Volumes horaires */
        $sql  = "
        SELECT fvh.* FROM v_formule_volume_horaire fvh WHERE 
          fvh.intervenant_id = :intervenant
          AND fvh.type_volume_horaire_id = :typeVolumeHoraire
          AND fvh.etat_volume_horaire_id >= :etatVolumeHoraire
        ";
        $vhds = $conn->fetchAll($sql, $params);
        foreach ($vhds as $vhd) {
            if ($vhd['VOLUME_HORAIRE_ID']) {
                $dsId = (int)$vhd['SERVICE_ID'];
                $tiId = (int)$vhd['TYPE_INTERVENTION_ID'];

                if (!isset($data['s'][$dsId])) {
                    $service          = $this->getServiceService()->get($dsId);
                    $data['s'][$dsId] = [
                        'element-etablissement'     => $service->getElementPedagogique() ? $service->getElementPedagogique() : $service->getEtablissement(),
                        'structure'                 => $this->getServiceStructure()->get($vhd['STRUCTURE_ID']),
                        'TAUX_FI'                   => $vhd['TAUX_FI'],
                        'TAUX_FA'                   => $vhd['TAUX_FA'],
                        'TAUX_FC'                   => $vhd['TAUX_FC'],
                        'PONDERATION_SERVICE_DU'    => (float)$vhd['PONDERATION_SERVICE_DU'],
                        'PONDERATION_SERVICE_COMPL' => (float)$vhd['PONDERATION_SERVICE_COMPL'],
                        'SERVICE_STATUTAIRE'        => $vhd['SERVICE_STATUTAIRE'] == '1',
                        'heures'                    => [],
                        'hetd'                      => [],
                    ];
                }
                if (!isset($data['s'][$dsId]['heures'][$tiId])) {
                    $data['s'][$dsId]['heures'][$tiId] = 0;
                }
                if (!isset($data['types-intervention'][$tiId])) {
                    $data['types-intervention'][$tiId] = $this->getServiceTypeIntervention()->get($tiId);
                }

                $data['s'][$dsId]['heures'][$tiId] += (float)$vhd['HEURES'];
            } else {
                $drId = (int)$vhd['SERVICE_REFERENTIEL_ID'];
                if (!isset($data['r'][$drId])) {
                    $data['r'][$drId] = [
                        'fonction'           => $this->getServiceServiceReferentiel()->get($drId)->getFonction(),
                        'structure'          => $this->getServiceStructure()->get($vhd['STRUCTURE_ID']),
                        'SERVICE_STATUTAIRE' => $vhd['SERVICE_STATUTAIRE'] == '1',
                        'heures'             => 0,
                        'hetd'               => [],
                    ];
                }
                $data['r'][$drId]['heures'] += (float)$vhd['HEURES'];
            }
        }

        /* Résultats de formule */
        $sql = "SELECT * FROM formule_resultat WHERE intervenant_id = :intervenant
            AND type_volume_horaire_id = :typeVolumeHoraire
            AND etat_volume_horaire_id = :etatVolumeHoraire";
        $fr  = $conn->fetchAssoc($sql, $params);
        if ($fr) {
            $data['has-calcul'] = true;
            $frId               = $fr['ID'];
            unset($fr['ID']);
            foreach ($fr as $k => $v) {
                $data[$k] = $v;
            }
            $sql  = "SELECT * FROM formule_resultat_service WHERE formule_resultat_id = :frId";
            $frss = $conn->fetchAll($sql, compact('frId'));
            foreach ($frss as $frs) {
                $dsId = $frs['SERVICE_ID'];
                if (isset($data['s'][$dsId])) {
                    unset($frs['ID']);
                    unset($frs['FORMULE_RESULTAT_ID']);
                    unset($frs['SERVICE_ID']);
                    unset($frs['TOTAL']);
                    $data['s'][$dsId]['hetd'] = $frs;
                }
            }

            $sql  = "SELECT * FROM formule_resultat_service_ref WHERE formule_resultat_id = :frId";
            $frss = $conn->fetchAll($sql, compact('frId'));
            foreach ($frss as $frs) {
                $drId = $frs['SERVICE_REFERENTIEL_ID'];
                if (isset($data['r'][$drId])) {
                    unset($frs['ID']);
                    unset($frs['FORMULE_RESULTAT_ID']);
                    unset($frs['SERVICE_REFERENTIEL_ID']);
                    unset($frs['TOTAL']);
                    $data['r'][$drId]['hetd'] = $frs;
                }
            }
        }

        /* Tri final */
        usort($data['types-intervention'], function ($ti1, $ti2) {
            return $ti1->getOrdre() > $ti2->getOrdre();
        });

        return $data;
    }
}