<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypePieceJointe as TypePieceJointeEntity;
use Application\Entity\Db\PieceJointe as PieceJointeEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;

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
     * Création de pièces jointes à partir de fichiers déposés.
     * 
     * @param array $files Ex: ['tmp_name' => '/tmp/k65sd4d', 'name' => 'Image.png', 'type' => 'image/png', 'size' => 321215]
     * @param boolean $deleteFiles Supprimer les fichiers après création de la PJ$
     * @return PieceJointeEntity[]
     */
    public function createFromFiles($files, IntervenantEntity $intervenant, TypePieceJointeEntity $type, $deleteFiles = true)
    {
        $instances = [];
        
        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = $file['name'];
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];
            
            $pj = (new PieceJointe())
                    ->setType($type)
                    ->setDossier($intervenant->getDossier())
                    ->setNomFichier($nomFichier)
                    ->setTailleFichier($tailleFichier)
                    ->setTypeFichier($typeFichier)
                    ->setFichier(file_get_contents($path))
                    ->setValidation(null);
            
            $this->getEntityManager()->persist($pj);
            $this->getEntityManager()->flush();
            
            $instances[] = $pj;
            
            if ($deleteFiles) {
                unlink($path);
            }
        }
        
        return $instances;
    }
    
    public function valider(PieceJointeEntity $pj)
    {
            $this->validation = $serviceValidation->newEntity($typeValidation);
            $this->validation->setIntervenant($this->intervenant);
            if ($role instanceof ComposanteDbRole) {
                $this->validation->setStructure($role->getStructure());
            }
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