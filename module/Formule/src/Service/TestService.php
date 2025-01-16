<?php

namespace Formule\Service;

use Application\Hydrator\GenericHydrator;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Formule\Entity\FormuleVolumeHoraire;
use RuntimeException;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\Db\FormuleTestVolumeHoraire;
use Formule\Model\FormuleCalcul;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Util;


/**
 * Description of FormuleTestIntervenantService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method FormuleTestIntervenant[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method FormuleTestIntervenant newEntity()
 *
 */
class TestService extends AbstractEntityService
{
    use FormuleServiceAwareTrait;
    use ParametresServiceAwareTrait;


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



    public function calculerAttendu(FormuleTestIntervenant $formuleTestIntervenant): TestService
    {
        $sql = "BEGIN ose_formule.test(" . ((int)$formuleTestIntervenant->getId()) . "); END;";
        $this->getEntityManager()->getConnection()->executeStatement($sql);

        $this->getEntityManager()->refresh($formuleTestIntervenant);
        foreach ($formuleTestIntervenant->getVolumesHoraires() as $vhe) {
            $this->getEntityManager()->refresh($vhe);
        }

        return $this;
    }



    public function creerDepuisIntervenant(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleTestIntervenant
    {
        $fsi = $this->getServiceFormule()->getFormuleServiceIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

        $fti = new FormuleTestIntervenant();

        $hydrator = new GenericHydrator($this->getEntityManager(), $fsi);
        $hydrator->setExtractType($hydrator::EXTRACT_TYPE_ORIGINAL);

        $data = $hydrator->extract($fsi);

        unset($data['id']);
        $hydrator->hydrate($data, $fti);

        $taux = [];

        $vhIgnore = ['id','formuleIntervenant', 'tauxServiceDu', 'tauxServiceCompl'];
        $vhHydrator = new GenericHydrator($this->getEntityManager(), FormuleVolumeHoraire::class, $vhIgnore);
        foreach( $fsi->getVolumesHoraires() as $vh){
            $taux[$vh->getTypeInterventionCode()] = [
                'serviceDu' => $vh->getTauxServiceDu(),
                'serviceCompl' => $vh->getTauxServiceCompl(),
            ];
            $vhData = $vhHydrator->extract($vh);
            unset($vhData['id']);
            $tvh = new FormuleTestVolumeHoraire();
            $vhHydrator->hydrate($vhData, $tvh);
            $fti->addVolumeHoraire($tvh);
        }

        $fti->setLibelle((string)$intervenant);
        $fti->setFormule($this->getServiceFormule()->getCurrent($intervenant->getId()));

        /* Traitement des taux */

        return $fti;


        /* Réduction des types d'intervention de la fiche à CP/TD/TP/AUTRE */
        $typesIntervention = [
            'CM' => [1.5, 1.5],
            'TD' => [1, 1],
            'TP' => [1, 2 / 3],
        ];
        $nbAutres = 0;
        foreach ($vhdata as $vhd) {
            if ($vhd['TYPE_INTERVENTION_CODE']) {
                if (!array_key_exists($vhd['TYPE_INTERVENTION_CODE'], $typesIntervention) && count($typesIntervention) == 8) {
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
        //$this->save($fti);

        return $fti;
    }



    public function toJson(FormuleTestIntervenant $formuleTestIntervenant): array
    {
        $intervenantHydrator = new GenericHydrator($this->getEntityManager());
        $intervenantHydrator->spec($formuleTestIntervenant);
        $intervenantHydrator->setExtractType($intervenantHydrator::EXTRACT_TYPE_JSON);

        $volumeHoraireHydrator = new GenericHydrator($this->getEntityManager());
        $volumeHoraireHydrator->spec(FormuleTestVolumeHoraire::class);
        $volumeHoraireHydrator->setExtractType($volumeHoraireHydrator::EXTRACT_TYPE_JSON);

        $iData = $intervenantHydrator->extract($formuleTestIntervenant);
        $iData['heuresServiceFi'] = $formuleTestIntervenant->getHeuresServiceFi();
        $iData['heuresServiceFa'] = $formuleTestIntervenant->getHeuresServiceFa();
        $iData['heuresServiceFc'] = $formuleTestIntervenant->getHeuresServiceFc();
        $iData['heuresServiceReferentiel'] = $formuleTestIntervenant->getHeuresServiceReferentiel();
        $iData['heuresComplFi'] = $formuleTestIntervenant->getHeuresComplFi();
        $iData['heuresComplFa'] = $formuleTestIntervenant->getHeuresComplFa();
        $iData['heuresComplFc'] = $formuleTestIntervenant->getHeuresComplFc();
        $iData['heuresComplReferentiel'] = $formuleTestIntervenant->getHeuresComplReferentiel();
        $iData['heuresPrimes'] = $formuleTestIntervenant->getHeuresPrimes();
        $iData['heuresService'] = $formuleTestIntervenant->getHeuresServiceFi() + $formuleTestIntervenant->getHeuresServiceFa() + $formuleTestIntervenant->getHeuresServiceFc() + $formuleTestIntervenant->getHeuresServiceReferentiel();
        $iData['heuresCompl'] = $formuleTestIntervenant->getHeuresComplFi() + $formuleTestIntervenant->getHeuresComplFa() + $formuleTestIntervenant->getHeuresComplFc() + $formuleTestIntervenant->getHeuresComplReferentiel();
        $iData['heuresNonPayableFi'] = $formuleTestIntervenant->getHeuresNonPayableFi();
        $iData['heuresNonPayableFa'] = $formuleTestIntervenant->getHeuresNonPayableFa();
        $iData['heuresNonPayableFc'] = $formuleTestIntervenant->getHeuresNonPayableFc();
        $iData['heuresNonPayableReferentiel'] = $formuleTestIntervenant->getHeuresNonPayableReferentiel();
        $iData['heuresNonPayable'] = $formuleTestIntervenant->getHeuresNonPayableFi() + $formuleTestIntervenant->getHeuresNonPayableFa() + $formuleTestIntervenant->getHeuresNonPayableFc() + $formuleTestIntervenant->getHeuresNonPayableReferentiel();

        $json = [
            'intervenant'     => $iData,
            'volumesHoraires' => [],
        ];

        foreach ($formuleTestIntervenant->getVolumesHoraires() as $volumeHoraire) {
            $vhArray = $volumeHoraireHydrator->extract($volumeHoraire);
            if ($vhArray['referentiel']) {
                $vhArray['typeInterventionCode'] = 'Référentiel';
            }
            $json['volumesHoraires'][] = $vhArray;
        }

        return $json;
    }



    public function fromJson(FormuleTestIntervenant $formuleTestIntervenant, array $intervenantData, array $volumesHorairesData): void
    {
        $intervenantHydrator = new GenericHydrator($this->getEntityManager());
        $intervenantHydrator->spec($formuleTestIntervenant);
        $intervenantHydrator->hydrate($intervenantData, $formuleTestIntervenant);

        $volumeHoraireHydrator = new GenericHydrator($this->getEntityManager());
        $volumeHoraireHydrator->spec(FormuleTestVolumeHoraire::class);

        $vhDiff = [];
        // on liste les volumes horaires existants déjà en BDD et on supprime les autres sans ID
        foreach ($formuleTestIntervenant->getVolumesHoraires() as $volumeHoraire) {
            if (!$volumeHoraire->getId()) {
                // les volumes horaires pas encore enregistrés sont détruits : ils seront recréés ensuite à partir des données JSON
                $formuleTestIntervenant->removeVolumeHoraire($volumeHoraire);
            } else {
                $vhDiff[$volumeHoraire->getId()] = ['vh' => $volumeHoraire, 'toDelete' => true];
            }
        }

        // On ajoute ou on modifie les volumes horaires en fonction des données transmises
        foreach ($volumesHorairesData as $vh) {
            $vh = (array)$vh;
            unset($vh['tauxServiceDu']);
            unset($vh['tauxServiceCompl']);

            $vhOk = ($vh['structureCode'] ?? null) !== null && ($vh['heures'] ?? null) !== null;

            if ($vhOk) {
                $vhId = $vh['id'] ?? -1;
                if (array_key_exists($vhId, $vhDiff)) {
                    $vhDiff[$vhId]['toDelete'] = false;
                    $volumeHoraireHydrator->hydrate($vh, $vhDiff[$vhId]['vh']);
                } else {
                    $volumeHoraire = new FormuleTestVolumeHoraire();
                    $volumeHoraireHydrator->hydrate($vh, $volumeHoraire);
                    $formuleTestIntervenant->addVolumeHoraire($volumeHoraire);
                }
            }
        }

        // On supprime les anciens volumes horaires qui auront été supprimés
        foreach ($vhDiff as $vhd) {
            if ($vhd['toDelete']) {
                $formuleTestIntervenant->removeVolumeHoraire($vhd['vh']);
            }
        }

    }



    public function get($id, $autoClear = false): FormuleTestIntervenant
    {
        $em = $this->getEntityManager();

        if (0 == $id) {
            $formuleTestIntervenant = new FormuleTestIntervenant();
            $formuleTestIntervenant->setAnnee($this->getServiceContext()->getAnnee());
            $formuleTestIntervenant->setFormule($em->find(Formule::class, $this->getServiceParametres()->get('formule')));
            $formuleTestIntervenant->setTypeIntervenant($em->getRepository(TypeIntervenant::class)->findOneBy(['code' => TypeIntervenant::CODE_PERMANENT]));
            $formuleTestIntervenant->setTypeVolumeHoraire($em->getRepository(TypeVolumeHoraire::class)->findOneBy(['code' => TypeVolumeHoraire::CODE_REALISE]));
            $formuleTestIntervenant->setEtatVolumeHoraire($em->getRepository(EtatVolumeHoraire::class)->findOneBy(['code' => EtatVolumeHoraire::CODE_VALIDE]));

            return $formuleTestIntervenant;
        }

        $dql = "
        SELECT
          fti, ftvh
        FROM
          " . FormuleTestIntervenant::class . " fti
          LEFT JOIN fti.volumesHoraires ftvh
        WHERE
          fti.id = :id
        ";

        $formuleTestIntervenant = $em->createQuery($dql)->setParameters(compact('id'))->getResult();
        if (isset($formuleTestIntervenant[0])) {
            $formuleTestIntervenant = $formuleTestIntervenant[0];
        }else{
            $formuleTestIntervenant = null;
        }

        if (!$formuleTestIntervenant) {
            throw new \Exception('l\'ID demandé est invalide');
        }

        return $formuleTestIntervenant;
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

        foreach ($entity->getVolumesHoraires() as $vhe) {
            $this->getEntityManager()->persist($vhe);
            $this->getEntityManager()->flush($vhe);
        }

        return $entity;
    }

}