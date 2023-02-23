<?php

namespace Application\Service;

use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Service\Traits\StructureServiceAwareTrait;
use Enseignement\Service\ServiceServiceAwareTrait;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use Referentiel\Service\ServiceReferentielServiceAwareTrait;
use Referentiel\Service\VolumeHoraireReferentielServiceAwareTrait;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

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

        $sql = 'SELECT HEURES FROM V_HETD_PREV_VAL_STRUCT WHERE STRUCTURE_ID = :structure AND ANNEE_ID = :annee';
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

        $sql  = 'SELECT STRUCTURE_ID, HEURES FROM V_HETD_PREV_VAL_STRUCT WHERE ANNEE_ID = :annee';
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
            'DEPASSEMENT_SERVICE_DU_SANS_HC' => false,
            's'                              => [],
            'r'                              => [],
            'types-intervention'             => [],
            'has-ponderation-service-compl'  => false,
            'has-calcul'                     => false,
        ];

        $sql = "SELECT * FROM V_FORMULE_INTERVENANT WHERE INTERVENANT_ID = :intervenant";
        $id  = $conn->fetchAssociative($sql, $params);
        if ($id) {
            $data['HEURES_SERVICE_STATUTAIRE']      = $id['HEURES_SERVICE_STATUTAIRE'];
            $data['HEURES_SERVICE_MODIFIE']         = $id['HEURES_SERVICE_MODIFIE'];
            $data['DEPASSEMENT_SERVICE_DU_SANS_HC'] = $id['DEPASSEMENT_SERVICE_DU_SANS_HC'] == '1';
            $data['SERVICE_DU']                     = $data['HEURES_SERVICE_STATUTAIRE'] + $id['HEURES_SERVICE_MODIFIE'];
        }

        /* Volumes horaires */
        $sql  = "
        SELECT FVH.* FROM V_FORMULE_VOLUME_HORAIRE FVH WHERE 
          FVH.INTERVENANT_ID = :intervenant
          AND FVH.TYPE_VOLUME_HORAIRE_ID = :typeVolumeHoraire
          AND FVH.ETAT_VOLUME_HORAIRE_ID >= :etatVolumeHoraire
        ";
        $vhds = $conn->fetchAllAssociative($sql, $params);
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
                        'fonction'           => $this->getServiceServiceReferentiel()->get($drId)->getFonctionReferentiel(),
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
        $sql = "SELECT * FROM FORMULE_RESULTAT WHERE INTERVENANT_ID = :intervenant
            AND TYPE_VOLUME_HORAIRE_ID = :typeVolumeHoraire
            AND ETAT_VOLUME_HORAIRE_ID = :etatVolumeHoraire";
        $fr  = $conn->fetchAssociative($sql, $params);
        if ($fr) {
            $data['has-calcul'] = true;
            $frId               = $fr['ID'];
            unset($fr['ID']);
            foreach ($fr as $k => $v) {
                $data[$k] = $v;
            }
            $data['SERVICE_TOTAL']      = $data['SERVICE_FI']
                + $data['SERVICE_FA']
                + $data['SERVICE_FC']
                + $data['SERVICE_REFERENTIEL'];
            $data['HEURES_COMPL_TOTAL'] = $data['HEURES_COMPL_FI']
                + $data['HEURES_COMPL_FA']
                + $data['HEURES_COMPL_FC']
                + $data['HEURES_COMPL_FC_MAJOREES']
                + $data['HEURES_COMPL_REFERENTIEL'];
            $sql                        = "SELECT * FROM FORMULE_RESULTAT_SERVICE WHERE FORMULE_RESULTAT_ID = :frId";
            $frss                       = $conn->fetchAllAssociative($sql, compact('frId'));
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

            $sql  = "SELECT * FROM FORMULE_RESULTAT_SERVICE_REF WHERE FORMULE_RESULTAT_ID = :frId";
            $frss = $conn->fetchAllAssociative($sql, compact('frId'));
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
            return $ti1->getOrdre() - $ti2->getOrdre();
        });

        usort($data['s'], function ($ee1, $ee2) {
            if ($ee1['element-etablissement'] instanceof ElementPedagogique) {
                $ee1Code = $ee1['element-etablissement']->getCode();
            } elseif ($ee1['element-etablissement'] instanceof Etablissement) {
                $ee1Code = $ee1['element-etablissement']->getLibelle();
            }

            if ($ee2['element-etablissement'] instanceof ElementPedagogique) {
                $ee2Code = $ee2['element-etablissement']->getCode();
            } elseif ($ee2['element-etablissement'] instanceof Etablissement) {
                $ee2Code = $ee2['element-etablissement']->getLibelle();
            }

            return $ee1Code > $ee2Code ? 1 : 0;
        });

        usort($data['r'], function ($r1, $r2) {
            return $r1['fonction']->getLibelleCourt() > $r2['fonction']->getLibelleCourt() ? 1 : 0;
        });


        return $data;
    }
}