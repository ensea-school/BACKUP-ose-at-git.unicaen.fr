<?php

namespace Application\Service;

use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\FormuleTestIntervenant;
use Application\Entity\Db\FormuleTestStructure;
use Application\Entity\Db\FormuleTestVolumeHoraire;
use Application\Entity\Db\Intervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\FormuleServiceAwareTrait;


/**
 * Description of FormuleTestIntervenantService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method FormuleTestIntervenant get($id)
 * @method FormuleTestIntervenant[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method FormuleTestIntervenant newEntity()
 *
 */
class FormuleTestIntervenantService extends AbstractEntityService
{
    use FormuleServiceAwareTrait;
    use FormuleServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return FormuleTestIntervenant::class;
    }


    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'fti';
    }


    /**
     * @param FormuleTestIntervenant $formuleTestIntervenant
     *
     * @return FormuleTestIntervenantService
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculer(FormuleTestIntervenant $formuleTestIntervenant): FormuleTestIntervenantService
    {
        $sql = "BEGIN ose_formule.test(" . ((int)$formuleTestIntervenant->getId()) . "); END;";
        $this->getEntityManager()->getConnection()->executeStatement($sql);

        $this->getEntityManager()->refresh($formuleTestIntervenant);
        foreach ($formuleTestIntervenant->getVolumeHoraireTest() as $vhe) {
            $this->getEntityManager()->refresh($vhe);
        }

        return $this;
    }


    /**
     * @return FormuleTestIntervenantService
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculerTout(): FormuleTestIntervenantService
    {
        $sql = "BEGIN ose_formule.test_tout; END;";
        $this->getEntityManager()->getConnection()->executeStatement($sql);

        return $this;
    }


    public function creerDepuisIntervenant(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleTestIntervenant
    {
        $conn = $this->getEntityManager()->getConnection();

        $formule = $this->getServiceFormule()->getCurrent();
        $intervenantQuery = trim($conn->executeQuery('SELECT ' . $formule->getPackageName() . '.INTERVENANT_QUERY Q FROM DUAL')->fetchOne());
        $volumeHoraireQuery = trim($conn->executeQuery('SELECT ' . $formule->getPackageName() . '.VOLUME_HORAIRE_QUERY Q FROM DUAL')->fetchOne());

        $sql = "BEGIN ose_formule.intervenant.id := " . $intervenant->getId() . "; END;";
        $conn->executeStatement($sql);

        $params = ['intervenant' => $intervenant->getId()];

        $idata = $conn->fetchAllAssociative('SELECT * FROM (' . $intervenantQuery . ') q WHERE intervenant_id = :intervenant', $params)[0];
        $params['typeVolumeHoraire'] = $typeVolumeHoraire->getId();
        $params['etatVolumeHoraire'] = $etatVolumeHoraire->getId();
        $vhdata = $conn->fetchAllAssociative('SELECT * FROM (' . $volumeHoraireQuery . ') q WHERE intervenant_id = :intervenant AND type_volume_horaire_id = :typeVolumeHoraire AND etat_volume_horaire_id >= :etatVolumeHoraire', $params);

        $fti = new FormuleTestIntervenant();
        $fti->setLibelle((string)$intervenant);
        $fti->setFormule($formule);
        $fti->setAnnee($intervenant->getAnnee());
        $fti->setTypeIntervenant($intervenant->getStatut()->getTypeIntervenant());
        $fti->setTypeVolumeHoraire($typeVolumeHoraire);
        $fti->setEtatVolumeHoraire($etatVolumeHoraire);
        $fti->setHeuresServiceStatutaire((float)$idata['HEURES_SERVICE_STATUTAIRE']);
        $fti->setHeuresServiceModifie((float)$idata['HEURES_SERVICE_MODIFIE']);
        $fti->setDepassementServiceDuSansHC($idata['DEPASSEMENT_SERVICE_DU_SANS_HC'] == '1');
        $fti->setParam1($idata['PARAM_1']);
        $fti->setParam2($idata['PARAM_2']);
        $fti->setParam3($idata['PARAM_3']);
        $fti->setParam4($idata['PARAM_4']);
        $fti->setParam5($idata['PARAM_5']);
        $fti->setStructureCode($idata['STRUCTURE_CODE']);

        /* Réduction des types d'intervention de la fiche à CP/TD/TP/AUTRE */
        $typesIntervention = [
            'CM' => [1.5, 1.5],
            'TD' => [1, 1],
            'TP' => [1, 2 / 3],
        ];
        $nbAutres = 0;
        foreach ($vhdata as $vhd) {
            if ($vhd['TYPE_INTERVENTION_CODE']) {
                $typesIntervention[$vhd['TYPE_INTERVENTION_CODE']] = [
                    (float)$vhd['TAUX_SERVICE_DU'],
                    (float)$vhd['TAUX_SERVICE_COMPL'],
                ];
            }
        }
        foreach ($typesIntervention as $tic => $tit) {
            if (!in_array($tic, ['CM', 'TD', 'TP'])) {
                $nbAutres++;
            } else {
                $typesIntervention[$tic][2] = $tic;
            }
        }
        if ($nbAutres == 1) {
            foreach ($typesIntervention as $tic => $tit) {
                if (!in_array($tic, ['CM', 'TD', 'TP'])) {
                    $typesIntervention[$tic][2] = 'AUTRE';
                    $nbAutres--;
                }
            }
        } else {
            if ($nbAutres > 1) {
                foreach ($typesIntervention as $tic => $tit) {
                    if (!in_array($tic, ['CM', 'TD', 'TP'])) {
                        if ($tit[0] == $typesIntervention['CM'][0] && $tit[1] == $typesIntervention['CM'][1]) {
                            $typesIntervention[$tic][2] = 'CM';
                            $nbAutres--;
                        }
                        if ($tit[0] == $typesIntervention['TD'][0] && $tit[1] == $typesIntervention['TD'][1]) {
                            $typesIntervention[$tic][2] = 'TD';
                            $nbAutres--;
                        }
                        if ($tit[0] == $typesIntervention['TP'][0] && $tit[1] == $typesIntervention['TP'][1]) {
                            $typesIntervention[$tic][2] = 'TP';
                            $nbAutres--;
                        }
                    }
                }
            }
        }
        if ($nbAutres == 1) {
            foreach ($typesIntervention as $tic => $tit) {
                if (!isset($tit[2])) {
                    $typesIntervention[$tic][2] = 'AUTRE';
                }
            }
        }

        if ($nbAutres > 1) {
            throw new \Exception('La fiche de service de cet intervenant ne peut pas être transformée en test de formule : elle comporte de trop nombreux types d\'intervention différents des CM/TP/TP');
        }

        /* On applique les taux au test de formule */
        foreach ($typesIntervention as $tic => $tit) {
            switch ($tit[2]) {
                case 'CM':
                    $fti->setTauxCmServiceDu($tit[0]);
                    $fti->setTauxCmServiceCompl($tit[1]);
                    break;
                case 'TP':
                    $fti->setTauxTpServiceDu($tit[0]);
                    $fti->setTauxTpServiceCompl($tit[1]);
                    break;
                case 'AUTRE':
                    $fti->setTauxAutreServiceDu($tit[0]);
                    $fti->setTauxAutreServiceCompl($tit[1]);
                    break;
            }
        }

        foreach ($vhdata as $vh) {
            $ftvh = new FormuleTestVolumeHoraire();
            $ftvh->setFormuleTestIntervenant($fti);
            $ftvh->setReferentiel($vh['VOLUME_HORAIRE_REF_ID'] != null);
            $ftvh->setServiceStatutaire($vh['SERVICE_STATUTAIRE'] == '1');
            $ftvh->setTypeInterventionCode($vh['TYPE_INTERVENTION_CODE']);
            $ftvh->setStructureCode($vh['STRUCTURE_CODE']);
            $ftvh->setTauxFi((float)$vh['TAUX_FI']);
            $ftvh->setTauxFa((float)$vh['TAUX_FA']);
            $ftvh->setTauxFc((float)$vh['TAUX_FC']);
            $ftvh->setPonderationServiceDu((float)$vh['PONDERATION_SERVICE_DU']);
            $ftvh->setPonderationServiceCompl((float)$vh['PONDERATION_SERVICE_COMPL']);
            $ftvh->setParam1($vh['PARAM_1']);
            $ftvh->setParam2($vh['PARAM_2']);
            $ftvh->setParam3($vh['PARAM_3']);
            $ftvh->setParam4($vh['PARAM_4']);
            $ftvh->setParam5($vh['PARAM_5']);
            $ftvh->setHeures((float)$vh['HEURES']);
            $fti->addVolumeHoraireTest($ftvh);
        }
        $this->save($fti);

        return $fti;
    }


    /**
     * Sauvegarde une entité
     *
     * @param FormuleTestIntervenant $entity
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        parent::save($entity);

        foreach ($entity->getVolumeHoraireTest() as $vhe) {
            $this->getEntityManager()->persist($vhe);
            $this->getEntityManager()->flush($vhe);
        }

        return $entity;
    }

}