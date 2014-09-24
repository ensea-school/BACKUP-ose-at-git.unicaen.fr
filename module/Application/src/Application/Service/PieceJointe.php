<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypePieceJointe as TypePieceJointeEntity;
use Application\Entity\Db\PieceJointe as PieceJointeEntity;
use Application\Entity\Db\Fichier as FichierEntity;
use Application\Entity\Db\IntervenantExterieur as IntervenantExterieurEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Validation as ValidationEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Assertion\PieceJointeAssertion;
use Application\Assertion\FichierAssertion;
use BjyAuthorize\Exception\UnAuthorizedException;
use Application\Acl\ComposanteDbRole;

/**
 * Description of PieceJointe
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PieceJointe extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\PieceJointe';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'pj';
    }

    /**
     * Retourne la liste des pièces jointes d'un type donné.
     *
     * @param TypePieceJointeEntity $type
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByType(TypePieceJointeEntity $type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.type = :type")->setParameter('type', $type);
        return $qb;
    }

    /**
     * Retourne la liste des pièces jointes d'un dossier donné.
     *
     * @param \Application\Entity\Db\Dossier $dossier
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByDossier(\Application\Entity\Db\Dossier $dossier, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.dossier = :dossier")->setParameter('dossier', $dossier);
        return $qb;
    }

    /**
     * Ajoute comme critère l'existence ou l'inexistence de fichier joint.
     *
     * @param boolean $exists
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByExistsFichier($exists = true, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $op = $exists ? '> 0' : '= 0';
        $qb->andWhere("SIZE ($alias.fichier) $op");
        return $qb;
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return PieceJointeEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");
        return parent::getList($qb, $alias);
    }
    
    /**
     * Création si besoin de la PieceJointe et ajout des Fichiers associés.
     * 
     * @param array $files Ex: ['tmp_name' => '/tmp/k65sd4d', 'name' => 'Image.png', 'type' => 'image/png', 'size' => 321215]
     * @param boolean $deleteFiles Supprimer les fichiers après création de la PJ$
     * @return PieceJointeEntity[]
     */
    public function createFromFiles($files, IntervenantExterieurEntity $intervenant, TypePieceJointeEntity $type, $deleteFiles = true)
    {
        if (!$files) {
           throw new \Common\Exception\LogicException("Aucune donnée sur les fichiers spécifiée.");
        }
        $instances = [];
        
        // création si besoin d'une PieceJointe
        $qb = $this->finderByType($type);
        $this->finderByDossier($intervenant->getDossier(), $qb);
        $pj = $qb->getQuery()->getOneOrNullResult(); /* @var $pj PieceJointeEntity */
        if (!$pj) {
            $pj = (new PieceJointeEntity())
                    ->setType($type)
                    ->setDossier($intervenant->getDossier())
                    ->setValidation(null);
            
            if (!$this->getAuthorize()->isAllowed($pj, PieceJointeAssertion::PRIVILEGE_CREATE)) {
                throw new UnAuthorizedException('Création de la pièce jointe suivante interdite : ' . $pj);
            }
        
            $this->getEntityManager()->persist($pj);
        }
            
        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = $file['name'];
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];
            
            $fichier = (new FichierEntity())
                    ->setType($typeFichier)
                    ->setNom($nomFichier)
                    ->setTaille($tailleFichier)
                    ->setContenu(file_get_contents($path))
                    ->setValidation(null);
            
//            if (!$this->getAuthorize()->isAllowed($pj, Fichierssertion::PRIVILEGE_CREATE)) {
//                throw new UnAuthorizedException('Création du fichier suivant interdite : ' . $pj);
//            }
        
            $pj->addFichier($fichier);
            
            $this->getEntityManager()->persist($fichier);
            $instances[] = $fichier;
            
            if ($deleteFiles) {
                unlink($path);
            }
        }

        $this->getEntityManager()->flush();
        
        return $instances;
    }
    
    /**
     * Validation d'une PJ.
     * 
     * @param \Application\Entity\Db\PieceJointe $pj
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Entity\Db\Validation
     * @throws UnAuthorizedException
     */
    public function valider(PieceJointeEntity $pj, IntervenantEntity $intervenant)
    {        
        $role                  = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceValidation     = $this->getServiceLocator()->get('ApplicationValidation');
        $serviceTypeValidation = $this->getServiceLocator()->get('ApplicationTypeValidation');
        
        if ($role instanceof ComposanteDbRole) {
            $structure = $role->getStructure();
        }
        else {
            $structure = $intervenant->getStructure();
        }
        
        $qb = $serviceTypeValidation->finderByCode(TypeValidationEntity::CODE_PIECE_JOINTE);
        $typeValidation = $qb->getQuery()->getSingleResult();
        
        $validation = $serviceValidation->newEntity($typeValidation);
        $validation->setIntervenant($intervenant);
        $validation->setStructure($structure);
        
        $pj->setValidation($validation);
        
        $this->getEntityManager()->persist($validation);
        $this->getEntityManager()->persist($pj);
        $this->getEntityManager()->flush();
        
        // validation de chaque fichier joint
        foreach ($pj->getFichier() as $fichier) {
            if (!$fichier->getValidation()) {
                $this->validerFichier($fichier, $pj, $intervenant);
            }
        }
        
        return $validation;
    }
    
    /**
     * Dévalidation d'une PJ.
     * 
     * @param \Application\Entity\Db\PieceJointe $pj
     * @return \Application\Entity\Db\Validation Validation historisée
     * @throws UnAuthorizedException
     */
    public function devalider(PieceJointeEntity $pj)
    {        
        $validation        = $pj->getValidation();
        $serviceValidation = $this->getServiceLocator()->get('ApplicationValidation');
        
        $serviceValidation->delete($validation, true);
        
        $pj->setValidation(null);
        
        $this->getEntityManager()->flush($pj);
        
        // dévalidation de chaque fichier joint
        foreach ($pj->getFichier() as $fichier) {
            if ($fichier->getValidation()) {
                $this->devaliderFichier($fichier, $pj);
            }
        }
        
        return $validation;
    }
    
    /**
     * Validation d'une PJ.
     * 
     * @param \Application\Entity\Db\Fichier $fichier
     * @param \Application\Entity\Db\PieceJointe $pj
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Entity\Db\Validation
     * @throws UnAuthorizedException
     */
    public function validerFichier(FichierEntity $fichier, PieceJointeEntity $pj, IntervenantEntity $intervenant)
    {        
        $role                  = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceValidation     = $this->getServiceLocator()->get('ApplicationValidation');
        $serviceTypeValidation = $this->getServiceLocator()->get('ApplicationTypeValidation');
        
        if ($role instanceof ComposanteDbRole) {
            $structure = $role->getStructure();
        }
        else {
            $structure = $intervenant->getStructure();
        }
        
        $qb = $serviceTypeValidation->finderByCode(TypeValidationEntity::CODE_FICHIER);
        $typeValidation = $qb->getQuery()->getSingleResult();
        
        $validation = $serviceValidation->newEntity($typeValidation);
        $validation->setIntervenant($intervenant);
        $validation->setStructure($structure);
        
        $fichier->setValidation($validation);
        
        $this->getEntityManager()->persist($validation);
        $this->getEntityManager()->persist($fichier);
        $this->getEntityManager()->flush();
        
        return $validation;
    }
    
    /**
     * Dévalidation d'un fichier.
     * 
     * @param \Application\Entity\Db\Fichier $fichier
     * @param \Application\Entity\Db\PieceJointe $pj
     * @return \Application\Entity\Db\Validation Validation historisée
     * @throws UnAuthorizedException
     */
    public function devaliderFichier(FichierEntity $fichier, PieceJointeEntity $pj)
    {        
        $validation        = $fichier->getValidation();
        $serviceValidation = $this->getServiceLocator()->get('ApplicationValidation');
        
        $serviceValidation->delete($validation, true);
        
        $fichier->setValidation(null);
        
        $this->getEntityManager()->flush($fichier);
        
        return $validation;
    }
    
    /**
     * Détermine si on peut saisir les pièces justificatives.
     *
     * @param \Application\Entity\Db\Intervenant $intervenant Intervenant concerné
     * @return boolean
     */
    public function canAdd($intervenant, $runEx = false)
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $rule = new \Application\Rule\Intervenant\PeutSaisirPieceJointeRule($intervenant);
        if (!$rule->execute()) {
            $message = "?";
            if ($role instanceof \Application\Acl\IntervenantRole) {
                $message = "Vous ne pouvez pas saisir de pièce justificative. ";
            }
            elseif ($role instanceof \Application\Acl\ComposanteDbRole) {
                $message = "Vous ne pouvez pas saisir de pièce justificative pour $intervenant. ";
            }
            return $this->cannotDoThat($message . $rule->getMessage(), $runEx);
        }
        
        return true;
    }
}