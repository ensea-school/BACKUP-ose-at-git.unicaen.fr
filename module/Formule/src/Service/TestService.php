<?php

namespace Formule\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\Db\FormuleTestVolumeHoraire;
use Formule\Model\FormuleCalcul;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Util;


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
class TestService extends AbstractEntityService
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
     * @return TestService
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculer(FormuleTestIntervenant $formuleTestIntervenant): TestService
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
     * @return TestService
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculerTout(): TestService
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
                if (!array_key_exists($vhd['TYPE_INTERVENTION_CODE'], $typesIntervention) && count($typesIntervention) == 8){
                    throw new \Exception('Il est impossible de transférer cette fiche : il y a plus de 5 types d\'intervention spécifiques, différents de CM/TD/TP');
                }

                $typesIntervention[$vhd['TYPE_INTERVENTION_CODE']] = [
                    (float)$vhd['TAUX_SERVICE_DU'],
                    (float)$vhd['TAUX_SERVICE_COMPL'],
                ];
            }
        }

        /* On applique les taux au test de formule */
        $autresIndex = 0;
        foreach ($typesIntervention as $tic => $tit) {
            switch ($tic) {
                case 'TD':
                    break;
                case 'CM':
                    $fti->setTauxCmServiceDu($tit[0]);
                    $fti->setTauxCmServiceCompl($tit[1]);
                    break;
                case 'TP':
                    $fti->setTauxTpServiceDu($tit[0]);
                    $fti->setTauxTpServiceCompl($tit[1]);
                    break;
                default:
                    $autresIndex++;
                    $fti->setTauxAutreCode($autresIndex, $tic);
                    $fti->setTauxAutreServiceDu($autresIndex, $tit[0]);
                    $fti->setTauxAutreServiceCompl($autresIndex, $tit[1]);
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



    public function creerDepuisTableur(FormuleCalcul $formuleCalcul, Formule $formule, string $filename): FormuleTestIntervenant
    {
        $em = $this->getEntityManager();

        $data = $formuleCalcul->getData();
        $vhs = $data['volumes-horaires'];

        $typeInterveant = $em->getRepository(TypeIntervenant::class)->findOneBy(['code' => $data['i.type_intervenant_code'] ?? TypeIntervenant::CODE_PERMANENT]);
        $typeVolumeHoraire = $em->getRepository(TypeVolumeHoraire::class)->findOneBy(['code' => $data['i.type_volume_horaire_code'] ?? TypeVolumeHoraire::CODE_REALISE]);
        $etatVolumeHoraire = $em->getRepository(EtatVolumeHoraire::class)->findOneBy(['code' => EtatVolumeHoraire::CODE_SAISI]);

        $fti = new FormuleTestIntervenant();
        $fti->setLibelle($filename);
        $fti->setFormule($formule);
        $fti->setAnnee($this->getServiceContext()->getAnnee());
        $fti->setTypeIntervenant($typeInterveant);
        $fti->setTypeVolumeHoraire($typeVolumeHoraire);
        $fti->setEtatVolumeHoraire($etatVolumeHoraire);
        $fti->setHeuresServiceStatutaire($data['i.heures_service_statutaire'] ?? 0);
        $fti->setHeuresServiceModifie($data['i.heures_service_modifie'] ?? 0);
        $fti->setDepassementServiceDuSansHC($data['i.depassement_service_du_sans_hc'] ?? false);
        $fti->setParam1($data['i.param_1'] ?? null);
        $fti->setParam2($data['i.param_2'] ?? null);
        $fti->setParam3($data['i.param_3'] ?? null);
        $fti->setParam4($data['i.param_4'] ?? null);
        $fti->setParam5($data['i.param_5'] ?? null);
        $fti->setStructureCode($data['i.structure_code'] ?? '');

        /* Réduction des types d'intervention de la fiche à CP/TD/TP/AUTRE */
        $typesIntervention = [
            'CM' => [1.5, 1.5],
            'TD' => [1, 1],
            'TP' => [1, 2 / 3],
        ];
        $nbAutres = 0;

        foreach ($vhs as $vh) {
            if ($vh['vh.type_intervention_code']) {
                $typesIntervention[$vh['vh.type_intervention_code']] = [
                    $vh['vh.taux_service_du'],
                    $vh['vh.taux_service_compl'],
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
                case 'AUTRE1':
                    $fti->setTauxAutre1ServiceDu($tit[0]);
                    $fti->setTauxAutre1ServiceCompl($tit[1]);
                    break;
                case 'AUTRE2':
                    $fti->setTauxAutre2ServiceDu($tit[0]);
                    $fti->setTauxAutre2ServiceCompl($tit[1]);
                    break;
                case 'AUTRE3':
                    $fti->setTauxAutre3ServiceDu($tit[0]);
                    $fti->setTauxAutre3ServiceCompl($tit[1]);
                    break;
                case 'AUTRE4':
                    $fti->setTauxAutre4ServiceDu($tit[0]);
                    $fti->setTauxAutre4ServiceCompl($tit[1]);
                    break;
                case 'AUTRE5':
                    $fti->setTauxAutre5ServiceDu($tit[0]);
                    $fti->setTauxAutre5ServiceCompl($tit[1]);
                    break;
            }
        }


        foreach ($vhs as $vh) {
            $isReferentiel = Util::reduce($vh['vh.type_intervention_code'] ?? '') === 'referentiel';

            if ($vh['vh.structure_is_exterieur'] ?? false) {
                $structureCode = '__EXTERIEUR__';
            } elseif ($vh['vh.structure_is_univ'] ?? false) {
                $structureCode = '__UNIV__';
            } elseif ($vh['vh.structure_is_affectation'] ?? false) {
                $structureCode = $fti->getStructureCode();
            } else {
                $structureCode = $vh['vh.structure_code'] ?? null;
            }

            $typeInterventionCode = $vh['vh.type_intervention_code'] ?? null;
            if ($isReferentiel) {
                $typeInterventionCode = null;
            } elseif (!in_array($typeInterventionCode, ['CM', 'TD', 'TP'])) {
                $typeInterventionCode = 'AUTRE';
            }

            $ftvh = new FormuleTestVolumeHoraire();
            $ftvh->setFormuleTestIntervenant($fti);
            $ftvh->setReferentiel($isReferentiel);
            $ftvh->setServiceStatutaire($vh['vh.service_statutaire'] ?? true);
            $ftvh->setTypeInterventionCode($typeInterventionCode);
            $ftvh->setStructureCode($structureCode);
            $ftvh->setTauxFi((float)$vh['vh.taux_fi'] ?? 1);
            $ftvh->setTauxFa((float)$vh['vh.taux_fa'] ?? 0);
            $ftvh->setTauxFc((float)$vh['vh.taux_fc'] ?? 0);
            $ftvh->setPonderationServiceDu($vh['vh.ponderation_service_du'] ?? 1);
            $ftvh->setPonderationServiceCompl($vh['vh.ponderation_service_compl'] ?? 1);
            $ftvh->setParam1($vh['vh.param_1'] ?? null);
            $ftvh->setParam2($vh['vh.param_2'] ?? null);
            $ftvh->setParam3($vh['vh.param_3'] ?? null);
            $ftvh->setParam4($vh['vh.param_4'] ?? null);
            $ftvh->setParam5($vh['vh.param_5'] ?? null);
            $ftvh->setHeures($vh['vh.heures']);
            $ftvh->setAServiceFi($vh['vh.service_fi'] ?? null);
            $ftvh->setAServiceFa($vh['vh.service_fa'] ?? null);
            $ftvh->setAServiceFc($vh['vh.service_fc'] ?? null);
            $ftvh->setAServiceReferentiel($vh['vh.service_referentiel'] ?? null);
            $ftvh->setAHeuresComplFi($vh['vh.heures_compl_fi'] ?? null);
            $ftvh->setAHeuresComplFa($vh['vh.heures_compl_fa'] ?? null);
            $ftvh->setAHeuresComplFc($vh['vh.heures_compl_fc'] ?? null);
            $ftvh->setAHeuresComplFcMajorees($vh['vh.heures_compl_fc_majorees'] ?? null);
            $ftvh->setAHeuresComplReferentiel($vh['vh.heures_compl_referentiel'] ?? null);

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