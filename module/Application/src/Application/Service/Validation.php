<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;

/**
 * Description of Validation
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Validation extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Validation';
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

    /**
     * Retourne une nouvelle entité de la classe donnée
     * 
     * @param TypeValidation|string $type
     * @return \Application\Entity\Db\Validation
     */
    public function newEntity($type = null)
    {
        $entity = parent::newEntity();
        $entity->setTypeValidation($this->normalizeTypeValidation($type));
        
        return $entity;
    }
    
    /**
     * Recherche par type 
     *
     * @param TypeValidation|string $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByType($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.typeValidation", 'tv')
                ->andWhere("tv = :tv")
                ->setParameter('tv', $this->normalizeTypeValidation($type));

        return $qb;
    }
    
    /**
     * Recherche par intervenant concerné. 
     *
     * @param IntervenantEntity $intervenant
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByIntervenant(IntervenantEntity $intervenant, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.intervenant", 'i')
                ->andWhere("i = :intervenant")
                ->setParameter('intervenant', $intervenant);

        return $qb;
    }
    
    /**
     * Recherche par structure d'intervention (i.e. structure où sont effectués les enseignements). 
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByStructureIntervention(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.volumeHoraire", 'vh')
                ->join("vh.service", 'vhs')
                ->andWhere("vhs.structureEns = :structure")
                ->setParameter('structure', $structure);

        return $qb;
    }

    /**
     * Retourne la liste des services dont les volumes horaires sont validés ou non.
     *
     * @param boolean|\Application\Entity\Db\Contrat $contrat <code>true</code>, <code>false</code> ou 
     * bien un Contrat précis
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContrat($contrat, QueryBuilder $qb = null, $alias = null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb     ->addSelect("vh")
                ->join("$alias.volumeHoraire", 'vh')/*
                ->join("vh.service", 's')
                ->join("s.volumeHoraire", 'vh2')*/;
                
        if ($contrat instanceof \Application\Entity\Db\Contrat) {
            $qb
                    ->join("vh.contrat", "c")
                    ->andWhere("c = :contrat")->setParameter('contrat', $contrat);
        }
        else {
            $value = $contrat ? 'is not null' : 'is null';
            $qb->andWhere("vh.contrat $value");
        }
        
        return $qb;
    }
    
    /**
     * 
     * @param TypeValidationEntity|string $type
     * @return TypeValidationEntity
     * @throws RuntimeException
     */
    public function normalizeTypeValidation($type)
    {
        if (null === $type) {
            return null;
        }
        if ($type instanceof TypeValidationEntity) {
            return $type;
        }
        
        $qb = $this->getServiceLocator()->get('ApplicationTypeValidation')->finderByCode($code = $type);
        $type = $qb->getQuery()->getOneOrNullResult();
        if (!$type) {
            throw new RuntimeException("Aucun type de validation trouvé avec le code '$code'.");
        }
        
        return $type;
    }
    
    /**
     * 
     * @param TypeValidationEntity $typeValidation
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureEns
     * @param StructureEntity $structureValidation
     * @return QueryBuilder
     */
    public function finderValidationsServices(
            TypeValidationEntity $typeValidation = null, 
            IntervenantEntity $intervenant = null, 
            StructureEntity $structureEns = null, 
            StructureEntity $structureValidation = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select("v, tv, str, i, vh, s, strens, strens2")
                ->from('Application\Entity\Db\Validation', 'v')
                ->join("v.typeValidation", 'tv')
                ->join("v.structure", 'str') // auteur de la validation
                ->join("v.intervenant", "i")
                ->join("v.volumeHoraire", 'vh')
                ->join("vh.service", 's')
                ->join("s.structureEns", 'strens')
                ->join("strens.structureNiv2", 'strens2')
                ->orderBy("v.histoModification", 'desc')
                ->addOrderBy("strens.libelleCourt", 'asc');
        
        if ($typeValidation) {
            $qb->andWhere("tv = :tv")->setParameter('tv', $typeValidation);
        }
        if ($intervenant) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if ($structureEns) {
            $qb->andWhere("strens = :structureEns OR strens2 = :structureEns")->setParameter('structureEns', $structureEns);
        }
        if ($structureValidation) {
            $qb->andWhere("str = :structureValidation")->setParameter('structureValidation', $structureValidation);
        }
        
//        var_dump($qb->getQuery()->getSQL());
        
        return $qb;
    }
    
    /**
     * 
     * @param TypeValidationEntity $typeValidation
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureRef
     * @param StructureEntity $structureValidation
     * @return QueryBuilder
     */
    public function finderValidationsReferentiels(
            TypeValidationEntity $typeValidation = null, 
            IntervenantEntity $intervenant = null, 
            StructureEntity $structureRef = null, 
            StructureEntity $structureValidation = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select("v, tv, str, i, vh, s, strref")
                ->from('Application\Entity\Db\Validation', 'v')
                ->join("v.typeValidation", 'tv')
                ->join("v.structure", 'str')
                ->join("v.intervenant", "i")
                ->join("v.volumeHoraireRef", 'vh')
                ->join("vh.serviceReferentiel", 's')
                ->join("s.structure", 'strref')
                ->orderBy("v.histoModification", 'desc')
                ->addOrderBy("strref.libelleCourt", 'asc');
        
        if ($typeValidation) {
            $qb->andWhere("tv = :tv")->setParameter('tv', $typeValidation);
        }
        if ($intervenant) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if ($structureRef) {
            $qb->andWhere("strref = :structureRef")->setParameter('structureRef', $structureRef);
        }
        if ($structureValidation) {
            $qb->andWhere("str = :structureValidation")->setParameter('structureValidation', $structureValidation);
        }
        
//        var_dump($qb->getQuery()->getSQL());
        
        return $qb;
    }
    
    /**
     * Détermine si on peut saisir une validation de services.
     *
     * @param \Application\Entity\Db\Intervenant $intervenant Intervenant concerné
     * @param \Application\Entity\Db\TypeValidation|string $type Type de validation concerné
     * @return boolean
     * @todo L'idée des canXxx est bonne mais trop simpliste : le contexte (intervenant, type de validation, 
     * contrat, ...) varie trop. Idée : transmettre au canXxx les règles métiers à tester plutôt que des paramètres ?
     */
    public function canAdd($intervenant, $type, $runEx = false)
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $rule = new \Application\Rule\Intervenant\PeutValiderServiceRule($intervenant, $this->normalizeTypeValidation($type));
                if (!$rule->execute()) {
                    $message = "?";
                    if ($role instanceof \Application\Acl\IntervenantRole) {
                        $message = "Vous ne pouvez pas valider. ";
                    }
                    elseif ($role instanceof \Application\Acl\ComposanteRole) {
                        $message = "Vous ne pouvez pas valider pour $intervenant. ";
                    }
                    return $this->cannotDoThat($message . $rule->getMessage(), $runEx);
                }
            
        return true;
    }
    
    /**
     * Détermine si on peut supprimer une validation.
     *
     * @param \Application\Entity\Db\Validation $validation Validation concernée
     * @return boolean
     */
    public function canDelete($validation, $runEx = false)
    {
        $rule = new \Application\Rule\Validation\PeutSupprimerValidationRule($validation);
        if (!$rule->execute()) {
            $message = "Vous ne pouvez pas supprimer la validation. ";
            return $this->cannotDoThat($message . $rule->getMessage(), $runEx);
        }
        
        return true;
    }

    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param \Application\Entity\Db\Validation $entity     Entité à détruire
     * @param bool $softDelete
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
//        foreach ($entity->getVolumeHoraire() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
//            $entity->removeVolumeHoraire($vh);
//        }
//        
        return parent::delete($entity, $softDelete);
    }
}