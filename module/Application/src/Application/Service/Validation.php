<?php

namespace Application\Service;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\Validation as ValidationEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Exception\DbException;
use Application\Rule\Validation\PeutSupprimerValidationRule;
use Common\Exception\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Exception;

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
     * Enregistre une nouvelle validation de données personnelles.
     * 
     * NB: tout le travail est déjà fait via un formulaire en fait! 
     * Cette méthode existe surtout pour déclencher l'événement de workflow.
     * 
     * @param ValidationEntity $validation
     * @return void
     */
    public function enregistrerValidationDossier(ValidationEntity $validation)
    {
        $this->getEntityManager()->persist($validation);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Enregistre une nouvelle validation de services.
     * 
     * @param ValidationEntity $validation
     * @return void
     */
    public function enregistrerValidationServices(ValidationEntity $validation/*, $services*/)
    {
//        // peuplement de la nouvelle validation avec les volumes horaires non validés
//        foreach ($services as $s) { /* @var $s \Application\Entity\Db\Service */
//            foreach ($s->getVolumeHoraire() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
//                $validation->addVolumeHoraire($vh);
//            }
//        }
        $this->save($validation);
    }
    
    /**
     * Suppression d'une validation (historisation par défaut).
     * 
     * @param \Application\Entity\Db\Validation $validation
     * @throws DbException En cas d'erreur en base de données
     */
    public function supprimer(ValidationEntity $validation)
    {
        $softDelete = true;
        
//      La suppression physique d'une validation peut poser problème dans certains cas (trigger).
//        // NB: une validation de contrat doit être supprimée! Il existe une relation ManyToOne de Contrat vers Validation
//        // et si la validation est seulement historisée, Doctrine ne trouve plus la Validation référencée dans Contrat 
//        // (EntityNotFoundException) si le filtre 'historique' est actif.
//        if ($validation->getTypeValidation()->getCode() === TypeValidationEntity::CODE_CONTRAT) {
//            $softDelete = false;
//        }

        // Validation de services : il faut supprimer les liens Validation --> VolumeHoraire
        if ($validation->getTypeValidation()->getCode() === TypeValidationEntity::CODE_ENSEIGNEMENT) {
            foreach ($validation->getVolumeHoraire() as $vh) {
                $validation->removeVolumeHoraire($vh);
            }
        }

        // Validation du référentiel : il faut supprimer les liens Validation --> VolumeHoraireReferentiel
        if ($validation->getTypeValidation()->getCode() === TypeValidationEntity::CODE_REFERENTIEL) {
            foreach ($validation->getVolumeHoraireReferentiel() as $vh) {
                $validation->removeVolumeHoraireReferentiel($vh);
            }
        }
        
        try {
            $this->delete($validation, $softDelete);
        }
        catch (Exception $e) {
            var_dump($e);
            throw new DbException(DbException::translate($e)->getMessage());
        }
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
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param TypeValidationEntity $typeValidation
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureEns
     * @param StructureEntity $structureValidation
     * @return QueryBuilder
     */
    public function finderValidationsServices(
            TypeVolumeHoraireEntity $typeVolumeHoraire,  
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
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :ctvh")->setParameter('ctvh', $typeVolumeHoraire->getCode())
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
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param TypeValidationEntity $typeValidation
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureRef
     * @param StructureEntity $structureValidation
     * @return QueryBuilder
     */
    public function finderValidationsReferentiels(
            TypeVolumeHoraireEntity $typeVolumeHoraire,  
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
                ->join("v.volumeHoraireReferentiel", 'vh')
                ->join("vh.serviceReferentiel", 's')
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :ctvh")->setParameter('ctvh', $typeVolumeHoraire->getCode())
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
     * @todo On présuppose ici qu'il s'agit de valider les services!
     */
    public function canAdd($intervenant, $type, $runEx = false)
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $rule = $this->getServiceLocator()->get('PeutValiderServiceRule')->setIntervenant($intervenant);
        $rule->setTypeValidation($this->normalizeTypeValidation($type));
        if (!$rule->execute()) {
            $message = "";
            if ($role instanceof IntervenantRole) {
                $message = "Vous ne pouvez pas valider. ";
            }
            elseif ($role instanceof ComposanteRole) {
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
        $rule = new PeutSupprimerValidationRule($validation);
        if (!$rule->execute()) {
            $message = "Vous ne pouvez pas supprimer la validation. ";
            return $this->cannotDoThat($message . $rule->getMessage(), $runEx);
        }
        
        return true;
    }
}