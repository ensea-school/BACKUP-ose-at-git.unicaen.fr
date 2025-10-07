<?php

namespace Workflow\Service;

use Application\Service\AbstractEntityService;
use Contrat\Entity\Db\Contrat;
use Contrat\Service\ContratServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Dossier\Entity\Db\IntervenantDossier;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\OffreEmploi;
use Mission\Entity\Db\VolumeHoraireMission;
use RuntimeException;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Workflow\Entity\Db\TypeValidation;
use Workflow\Entity\Db\Validation;

/**
 * Description of Validation
 *
 */
class ValidationService extends AbstractEntityService
{
    use TypeValidationServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
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



    public function validerDossier(IntervenantDossier $intervenantDossier, bool $complementaire = false): Validation
    {

        $validation  = $this->newEntity();
        $intervenant = $intervenantDossier->getIntervenant();
        $validation->setIntervenant($intervenantDossier->getIntervenant());
        $typeValidation = (!$complementaire) ? $this->getServiceTypeValidation()->getDonneesPerso() : $this->getServiceTypeValidation()->getDonneesPersoComplementaire();
        $validation->setTypeValidation($typeValidation);
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
        if ($volumeHoraireMission->getTypeVolumeHoraire()->isPrevu()) {
            $typeValidation = $this->getServiceTypeValidation()->getMission();
        } else {
            $typeValidation = $this->getServiceTypeValidation()->getMissionRealise();
        }

        $validation = $this->newEntity();
        $validation->setIntervenant($volumeHoraireMission->getMission()->getIntervenant());
        $validation->setTypeValidation($typeValidation);
        $validation->setStructure($volumeHoraireMission->getMission()->getStructure());
        $this->save($validation);
        $volumeHoraireMission->addValidation($validation);

        return $validation;
    }



    public function validerOffreEmploi(OffreEmploi $offreEmploi): Validation
    {
        $validation = $this->newEntity();
        $validation->setTypeValidation($this->getServiceTypeValidation()->getOffreEmploi());
        $validation->setStructure($offreEmploi->getStructure());
        $this->save($validation);
        $offreEmploi->setValidation($validation);

        return $validation;
    }



    public function validerCandidature(Candidature $candidature): Validation
    {
        $validation = $this->newEntity();
        $validation->setTypeValidation($this->getServiceTypeValidation()->getCandidature());
        $validation->setStructure($candidature->getIntervenant()->getStructure());
        $this->save($validation);
        $candidature->setValidation($validation);
        $candidature->setMotif(null);
        $this->getEntityManager()->persist($candidature);
        $this->getEntityManager()->flush($candidature);

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
        $tv = $this->getServiceTypeValidation()->getByCode(TypeValidation::CLOTURE_REALISE);

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
     * @return \Workflow\Entity\Db\Validation
     */
    public function newEntity(?TypeValidation $type = null): Validation
    {
        $entity = parent::newEntity();
        if ($type) {
            $entity->setTypeValidation($type);
        }

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
    public function finderByType($type, ?QueryBuilder $qb = null, $alias = null)
    {
        [$qb,
         $alias] = $this->initQuery($qb, $alias);

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
    public function lister(TypeValidation $typeValidation, Intervenant $intervenant, ?Structure $structure = null)
    {
        $dql = "
        SELECT
          v
        FROM
          Workflow\Entity\Db\Validation v
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