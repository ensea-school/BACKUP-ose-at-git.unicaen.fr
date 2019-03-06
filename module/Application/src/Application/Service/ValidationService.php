<?php

namespace Application\Service;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\Dossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Application\Service\Traits\ContratServiceAwareTrait;
use Application\Service\Traits\MiseEnPaiementServiceAwareTrait;
use RuntimeException;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of Validation
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationService extends AbstractEntityService
{
    use Traits\TypeValidationServiceAwareTrait;
    use Traits\TypeVolumeHoraireServiceAwareTrait;
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



    public function validerDossier(Dossier $dossier)
    {
        $typeDonneesPerso = $this->getServiceTypeValidation()->getByCode(TypeValidation::CODE_DONNEES_PERSO);

        $validation = $this->newEntity();
        $validation->setIntervenant($dossier->getIntervenant());
        $validation->setTypeValidation($typeDonneesPerso);
        $validation->setStructure($dossier->getIntervenant()->getStructure());
        $this->save($validation);

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
            'typeValidation' => $tv,
            'intervenant'    => $intervenant,
        ]);

        if (!$validation){
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
     * @param bool             $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        /* On détruit d'abord les dépendances possibles ... */
        foreach($entity->getMiseEnPaiement() as $mep){
            /** @var MiseEnPaiement $mep */
            if (!$mep->estNonHistorise()){ // seulement pour les historisés!!
                $this->getServiceMiseEnPaiement()->delete($mep, false);
            }
        }

        foreach ($entity->getVolumeHoraire() as $vh) {
            $entity->removeVolumeHoraire($vh);
        }

        foreach ($entity->getVolumeHoraireReferentiel() as $vh) {
            $entity->removeVolumeHoraireReferentiel($vh);
        }


        if (!$softDelete){
            /** @var Contrat[] $contrats */
            $contrats = $this->getEntityManager()->getRepository(Contrat::class)->findBy(['validation' => $entity]);
            foreach( $contrats as $contrat ){
                $contrat->setValidation(null);
                $this->getServiceContrat()->save($contrat);
            }

            $sql = "DELETE FROM validation WHERE id = ".(int)$entity->getId();
            $this->getEntityManager()->getConnection()->executeQuery($sql);

            return $this;
        }else{
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
        list($qb, $alias) = $this->initQuery($qb, $alias);

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