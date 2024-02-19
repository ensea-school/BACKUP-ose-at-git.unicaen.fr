<?php

namespace Formule\Service;


use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Formule\Entity\Db\FormuleResultatIntervenant;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

/**
 * Description of FormuleService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormuleService extends AbstractService
{
    public function getService(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleIntervenant
    {
        $sql = "
        SELECT
          *
        FROM
          v_formule_intervenant
        WHERE
          intervenant_id = :intervenant
        ";

        $params = [
            'intervenant' => $intervenant->getId(),
        ];
        $res = $this->getEntityManager()->getConnection()->fetchAssociative($sql, $params);

        $formuleIntervenant = new FormuleIntervenant();
        $formuleIntervenant->setId($intervenant->getId());
        $formuleIntervenant->setAnnee($intervenant->getAnnee());
        $formuleIntervenant->setTypeVolumeHoraire($typeVolumeHoraire);
        $formuleIntervenant->setEtatVolumeHoraire($etatVolumeHoraire);
        $formuleIntervenant->setTypeIntervenant($intervenant->getStatut()->getTypeIntervenant());
        $formuleIntervenant->setStructureCode($res['STRUCTURE_CODE']);
        $formuleIntervenant->setHeuresServiceStatutaire((float)$res['HEURES_SERVICE_STATUTAIRE']);
        $formuleIntervenant->setHeuresServiceModifie((float)$res['HEURES_SERVICE_MODIFIE']);
        $formuleIntervenant->setDepassementServiceDuSansHC($res['DEPASSEMENT_SERVICE_DU_SANS_HC'] === '1');


        $sql = "
        SELECT
          *
        FROM
          v_formule_volume_horaire
        WHERE
          intervenant_id = :intervenant
          AND type_volume_horaire_id = :typeVolumeHoraire
          AND etat_volume_horaire_id >= :etatVolumeHoraire
        ";

        $params = [
            'intervenant' => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
            'etatVolumeHoraire' => $etatVolumeHoraire->getId(),
        ];
        $ress = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);

        foreach ($ress as $res){
            $fvh = new FormuleVolumeHoraire();
            $fvh->setFormuleIntervenant($formuleIntervenant);
            $formuleIntervenant->addVolumeHoraire($fvh);

            $fvh->setId((int)$res['ID']);
            $fvh->setVolumeHoraire((int)$res['VOLUME_HORAIRE_ID'] ?: null);
            $fvh->setVolumeHoraireReferentiel((int)$res['VOLUME_HORAIRE_REF_ID'] ?: null);
            $fvh->setService((int)$res['SERVICE_ID'] ?: null);
            $fvh->setServiceReferentiel((int)$res['SERVICE_REFERENTIEL_ID'] ?: null);

            $fvh->setStructureCode($res['STRUCTURE_CODE']);
            $fvh->setTypeInterventionCode($res['TYPE_INTERVENTION_CODE']);
            $fvh->setStructureUniv($res['STRUCTURE_IS_UNIV'] === '1');
            $fvh->setStructureExterieur($res['STRUCTURE_IS_EXTERIEUR'] === '1');
            $fvh->setServiceStatutaire($res['SERVICE_STATUTAIRE'] === '1');
            $fvh->setNonPayable($res['NON_PAYABLE'] === '1');

            $fvh->setTauxFi((float)$res['TAUX_FI']);
            $fvh->setTauxFa((float)$res['TAUX_FA']);
            $fvh->setTauxFc((float)$res['TAUX_FC']);
            $fvh->setTauxServiceDu((float)$res['TAUX_SERVICE_DU']);
            $fvh->setTauxServiceCompl((float)$res['TAUX_SERVICE_COMPL']);
            $fvh->setPonderationServiceDu((float)$res['PONDERATION_SERVICE_DU']);
            $fvh->setPonderationServiceCompl((float)$res['PONDERATION_SERVICE_COMPL']);
            $fvh->setHeures((float)$res['HEURES']);
        }

        return $formuleIntervenant;
    }



    public function getResultat(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleIntervenant
    {
        /** @var EntityRepository $repo */
        $repo = $this->getEntityManager()->getRepository(FormuleResultatIntervenant::class);

        $dql = "
        SELECT
          fri, frvh
        FROM
          ".FormuleResultatIntervenant::class." fri
          JOIN fri.volumesHoraires frvh
        WHERE
          fri.intervenant = :intervenant
          AND fri.typeVolumeHoraire = :typeVolumeHoraire
          AND fri.etatVolumeHoraire = :etatVolumeHoraire
        ";

        $params =[
            'intervenant' => $intervenant,
            'typeVolumeHoraire' => $typeVolumeHoraire,
            'etatVolumeHoraire' => $etatVolumeHoraire,
        ];

        $formuleResultatIntervenant = $this->getEntityManager()->createQuery($dql)->setParameters($params)->getResult()[0];

        return $formuleResultatIntervenant;
    }



    public function getTest(FormuleTestIntervenant $intervenant): FormuleIntervenant
    {
        /** @var FormuleTestIntervenant $repo */
        $repo = $this->getEntityManager()->getRepository(FormuleTestIntervenant::class);

        $dql = "
        SELECT
          fti, ftvh
        FROM
          ".FormuleTestIntervenant::class." fti
          JOIN fti.volumesHoraires ftvh
        WHERE
          fti.id = :intervenant
        ";

        $params =[
            'intervenant' => $intervenant,
        ];

        $formuleTestIntervenant = $this->getEntityManager()->createQuery($dql)->setParameters($params)->getResult()[0];

        return $formuleTestIntervenant;
    }


}