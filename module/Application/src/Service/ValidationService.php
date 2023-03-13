<?php

namespace Application\Service;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Application\Service\Traits\ContratServiceAwareTrait;
use Application\Service\Traits\MiseEnPaiementServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Dossier\Entity\Db\IntervenantDossier;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of Validation
 *
 */
class ValidationService extends AbstractEntityService
{
    use TypeValidationServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use MiseEnPaiementServiceAwareTrait;
    use ContratServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Validation::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'v';
    }



    public function validerDossier(IntervenantDossier $intervenantDossier): Validation
    {
        $validation = $this->newEntity();
        /**
         * @var Intervenant $intervenant
         */
        $intervenant = $intervenantDossier->getIntervenant();
        $validation->setIntervenant($intervenantDossier->getIntervenant());
        $validation->setTypeValidation($this->getServiceTypeValidation()->getDonneesPerso());
        $validation->setStructure($intervenant->getStructure());
        $this->save($validation);

        return $validation;
    }



    public function validerMission(Mission $mission): Validation
    {
        $validation = $this->newEntity();
        $validation->setIntervenant($mission->getIntervenant());
        $validation->setTypeValidation($this->getServiceTypeValidation()->getMission());
        $validation->setStructure($mission->getStructure());
        $this->save($validation);
        $mission->addValidation($validation);

        return $validation;
    }



    public function validerVolumeHoraireMission(VolumeHoraireMission $volumeHoraireMission): Validation
    {
        $validation = $this->newEntity();
        $validation->setIntervenant($volumeHoraireMission->getMission()->getIntervenant());
        $validation->setTypeValidation($this->getServiceTypeValidation()->getMission());
        $validation->setStructure($volumeHoraireMission->getMission()->getStructure());
        $this->save($validation);
        $volumeHoraireMission->addValidation($validation);

        return $validation;
    }



    /**
     *
     * @param Intervenant            $intervenant
     * @param TypeVolumeHoraire|null $tvh
     *
     * @return Validation|null
     */
    public function getValidationClotureServices(Intervenant $intervenant)
    {
        $tv = $this->getServiceTypeValidation()->getByCode(TypeValidation::CODE_CLOTURE_REALISE);

        $validation = $this->getRepo()->findOneBy([
            'typeValidation'   => $tv,
            'intervenant'      => $intervenant,
            'histoDestruction' => null,
        ]);

        if (!$validation) {
            $role = $this->getServiceContext()->getSelectedIdentityRole();

            $validation = $this->newEntity($tv);
            $validation->setIntervenant($intervenant);
            $validation->setStructure($role->getStructure() ?: $intervenant->getStructure());
        }

        return $validation;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param Validation $entity Entité à détruire
     * @param bool       $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        /* On détruit d'abord les dépendances possibles ... */
        foreach ($entity->getMiseEnPaiement() as $mep) {
            /** @var MiseEnPaiement $mep */
            if (!$mep->estNonHistorise()) { // seulement pour les historisés!!
                $this->getServiceMiseEnPaiement()->delete($mep, false);
            }
        }

        foreach ($entity->getVolumeHoraire() as $vh) {
            $entity->removeVolumeHoraire($vh);
        }

        foreach ($entity->getVolumeHoraireReferentiel() as $vh) {
            $entity->removeVolumeHoraireReferentiel($vh);
        }


        if (!$softDelete) {
            /** @var Contrat[] $contrats */
            $contrats = $this->getEntityManager()->getRepository(Contrat::class)->findBy(['validation' => $entity]);
            foreach ($contrats as $contrat) {
                $contrat->setValidation(null);
                $this->getServiceContrat()->save($contrat);
            }

            $sql = "DELETE FROM validation WHERE id = " . (int)$entity->getId();
            $this->getEntityManager()->getConnection()->executeQuery($sql);

            return $this;
        } else {
            return parent::delete($entity, $softDelete); // TODO: Change the autogenerated stub
        }
    }



    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @param TypeValidationService|string $type
     *
     * @return \Application\Entity\Db\Validation
     */
    public function newEntity($type = null)
    {
        $entity = parent::newEntity();
        $entity->setTypeValidation($type);

        return $entity;
    }



    /**
     * Recherche par type
     *
     * @param TypeValidationService|string $type
     * @param QueryBuilder|null            $qb
     *
     * @return QueryBuilder
     */
    public function finderByType($type, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        if (is_string($type)) {
            $type = $this->getServiceTypeValidation()->getByCode($type);
        }

        $qb
            ->join("$alias.typeValidation", 'tv')
            ->andWhere("tv = :tv")
            ->setParameter('tv', $type);

        return $qb;
    }



    /**
     * @param TypeValidation $typeValidation
     * @param Intervenant    $intervenant
     * @param Structure|null $structure
     *
     * @return array
     */
    public function lister(TypeValidation $typeValidation, Intervenant $intervenant, Structure $structure = null)
    {
        $dql = "
        SELECT
          v
        FROM
          Application\Entity\Db\Validation v
        WHERE
          v.intervenant = :intervenant
          AND v.typeValidation = :typeValidation
          " . ($structure ? 'AND v.structure = :structure' : '') . "
        ORDER BY
          v.histoCreation
        ";

        $params = compact(
            'intervenant', 'typeValidation'
        );
        if ($structure) {
            $params['structure'] = $structure;
        }
        $res         = $this->getEntityManager()->createQuery($dql)->setParameters($params)->getResult();
        $validations = [];
        foreach ($res as $v) {
            $validations[$v->getId()] = $v;
        }

        return $validations;
    }
}