<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Etape as EtapeEntity;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Etape extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Etape';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'etp';
    }

    /**
     * 
     * @param \Application\Entity\NiveauEtape $niveau
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByNiveau(\Application\Entity\NiveauEtape $niveau, QueryBuilder $qb=null, $alias=null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb
                ->innerJoin("$alias.typeFormation", 'tf')
                ->innerJoin("tf.groupe", 'gtf')
                ->andWhere("$alias.niveau = :niv AND gtf.libelleCourt = :lib")
                ->setParameter('niv', $niveau->getNiv())
                ->setParameter('lib', $niveau->getLib());
        
        return parent::getList($qb, $alias);
    }
    
    /**
     * Détermine si on peut ajouter une étape ou non
     *
     * @return boolean
     */
    public function canAdd($runEx = false)
    {
        $localContext = $this->getContextProvider()->getLocalContext();
        $role         = $this->getServiceLocator()->get('ApplicationContextProvider')->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\DbRole) { 
            if (!$localContext->getStructure()) {
                throw new \Common\Exception\LogicException("Le filtre structure est requis dans la méthode " . __METHOD__);
            }
            if ($localContext->getStructure()->getId() === $role->getStructure()->getId()
                    || $localContext->getStructure()->estFilleDeLaStructureDeNiv2($role->getStructure())) {
                return true;
            }
            
            $this->cannotDoThat(
                    "Votre structure de responsabilité ('{$role->getStructure()}') ne vous permet pas d'ajouter/modifier d'étape"
                    . "pour la structure '{$localContext->getStructure()}'", $runEx);
        }

        return $this->cannotDoThat('Vous n\'avez pas les droits nécessaires pour ajouter ou modifier une étape', $runEx);
    }

    /**
     * Détermine si l'étape peut être éditée ou non
     * 
     * @param int|\Application\Entity\Db\Etape $etape
     * @return boolean
     */
    public function canSave($etape, $runEx = false)
    {
        if (! $this->canAdd($runEx)) {
            return false;
        }
        
        if (!$etape instanceof EtapeEntity) {
            $etape = $this->get($etape);
        }
        
        if ($etape->getSource()->getCode() !== \Application\Entity\Db\Source::CODE_SOURCE_OSE){
            $errStr = 'Cette formation n\'est pas modifiable dans OSE car elle provient du logiciel '.$etape->getSource();
            $errStr .= '. Si vous souhaitez mettre à jour ces informations, nous vous invitons donc à les modifier directement dans '.$etape->getSource().'.';
            
            return $this->cannotDoThat($errStr, $runEx);
        }
        
        return true;
    }

    /**
     * Détermine si l'étape peut être supprimée ou non
     *
     * @param \Application\Entity\Db\Etape $etape
     * @param boolean $runEx
     * @return boolean
     */
    public function canDelete(EtapeEntity $etape, $runEx=false)
    {
        return $this->canSave($etape,$runEx);
    }

    public function canEditModulateurs($etape, $runEx=false)
    {
        if (!$etape instanceof EtapeEntity) {
            $etape = $this->get($etape);
        }

        return true; /** @todo à sécuriser!! */
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return \Application\Entity\Db\Etape[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");
        return parent::getList($qb, $alias);
    }

    public function save($entity)
    {
        $this->canSave($entity,true);
        parent::save($entity);
    }

    public function delete($entity, $softDelete = true)
    {
        $this->canDelete($entity,true);
        return parent::delete($entity, $softDelete);
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     * @return EtapeEntity
     */
    public function newEntity()
    {
        $this->canAdd(true);
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource( $this->getServiceLocator()->get('ApplicationSource')->getOse() );
        return $entity;
    }

}