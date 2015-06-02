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
    use Traits\LocalContextAwareTrait;


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

        $typeFormationService = $this->getServiceLocator()->get('applicationTypeFormation');
        $typeFormationAlias = $typeFormationService->getAlias();

        $groupeTypeFormationService = $this->getServiceLocator()->get('applicationGroupeTypeFormation');
        $groupeTypeFormationAlias = $groupeTypeFormationService->getAlias();

        $qb
                ->innerJoin("$alias.typeFormation", $typeFormationAlias)
                ->innerJoin("$typeFormationAlias.groupe", $groupeTypeFormationAlias)
                ->andWhere("$alias.niveau = :niv AND $groupeTypeFormationAlias.libelleCourt = :lib")
                ->setParameter('niv', $niveau->getNiv())
                ->setParameter('lib', $niveau->getLib());
        
        return parent::getList($qb, $alias);
    }

    /**
     * Retourne le chercheur d'étapes orphelines (i.e. sans EP).
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByOrphelines(QueryBuilder $qb=null, $alias=null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.source", "src")
                ->andWhere("NOT EXISTS (SELECT eptmp FROM Application\Entity\Db\ElementPedagogique eptmp WHERE eptmp.etape = $alias)")
                ->andWhere("NOT EXISTS (SELECT cptmp FROM Application\Entity\Db\CheminPedagogique  cptmp WHERE cptmp.etape = $alias)")
                ->andWhere("$alias.specifiqueEchanges = 0 OR src.code = :sourceOse")
                ->setParameter('sourceOse', \Application\Entity\Db\Source::CODE_SOURCE_OSE);

        return $qb;
    }

    /**
     * Retourne le chercheur d'étapes orphelines (i.e. sans EP).
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByNonOrphelines(QueryBuilder $qb=null, $alias=null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $or = $qb->expr()->orX();
        $or->add($qb->expr()->exists("SELECT eptmp FROM Application\Entity\Db\ElementPedagogique eptmp WHERE eptmp.etape = $alias"));
        $or->add($qb->expr()->exists("SELECT cptmp FROM Application\Entity\Db\CheminPedagogique  cptmp WHERE cptmp.etape = $alias"));
        $qb->andWhere( $or );
        return $qb;
    }

    /**
     *
     * @param \Application\Entity\Db\Structure $structure
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function finderByStructure(\Application\Entity\Db\Structure $structure, QueryBuilder $qb=null, $alias=null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $structureService = $this->getServiceLocator()->get('applicationStructure');
        $structureAlias = $structureService->getAlias();

        $this->join( $structureService, $qb, 'structure');

        $qb->andWhere("$structureAlias.structureNiv2 = :structureNiv2")->setParameter('structureNiv2', $structure->getParenteNiv2());
        return $qb;
    }

    /**
     * Filtre par historique, si l'entité est compatible avec les historiques
     *
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByHistorique( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $dateObservation = $this->getServiceContext()->getDateObservation();
        if ($dateObservation){
            $dqldobs = ', :fbh_dateObservation';
            $qb->setParameter('fbh_dateObservation', $dateObservation, \Doctrine\DBAL\Types\Type::DATETIME);
        }else{
            $dqldobs = '';
        }

        $qb->setParameter('fbh_annee', $this->getServiceContext()->getAnnee());

        $qb->andWhere("
            1 = compriseEntre($alias.histoCreation,$alias.histoDestruction$dqldobs)
            OR EXISTS(
              SELECT
                cp.id
              FROM
                Application\Entity\Db\CheminPedagogique cp
                JOIN Application\Entity\Db\ElementPedagogique ep WITH ep = cp.elementPedagogique
              WHERE
                1 = compriseEntre(cp.histoCreation,cp.histoDestruction$dqldobs)
                AND 1 = compriseEntre(ep.histoCreation,ep.histoDestruction$dqldobs)
                AND cp.etape = $alias
                AND ep.annee = :fbh_annee
          )
        ");
        return $qb;
    }

    /**
     * Détermine si on peut ajouter une étape ou non
     *
     * @return boolean
     */
    public function canAdd($runEx = false)
    {
        $localContext = $this->getServiceLocalContext();
        
        $role         = $this->getServiceContext()->getSelectedIdentityRole();
        /* @var $role \Application\Acl\DbRole */
        
        if ($role instanceof \Application\Acl\AdministrateurRole) return true;

        if (!$localContext->getStructure()) {
            throw new \Common\Exception\LogicException("Le filtre structure est requis dans la méthode " . __METHOD__);
        }
        if ($localContext->getStructure()->getId() === $role->getStructure()->getId()
                || $localContext->getStructure()->estFilleDeLaStructureDeNiv2($role->getStructure())) {
            return true;
        }

        $this->cannotDoThat(
                "Votre structure de responsabilité ('{$role->getStructure()}') ne vous permet pas d'ajouter/modifier de formation"
                . "pour la structure '{$localContext->getStructure()}'", $runEx);

        return $this->cannotDoThat('Vous n\'avez pas les droits nécessaires pour ajouter ou modifier une formation', $runEx);
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

        $ir = $this->getServiceContext()->getSelectedIdentityRole();
        if ($ir instanceof \Application\Acl\ComposanteRole){
            if ($etape->getStructure() != $ir->getStructure()){
                return $this->cannotDoThat('Vous n\'avez pas les autorisations nécessaires pour éditer les modulateurs de cette structure', $runEx);
            }
        }elseif($ir->getRoleId() == \Application\Acl\Role::ROLE_ID || $ir->getRoleId() == 'user'){
            return $this->cannotDoThat('Vous n\'êtes pas autorisé à éditer de modulateurs', $runEx);
        }elseif($ir instanceof \Application\Acl\IntervenantRole){
            return $this->cannotDoThat('Les intervenants n\'ont pas la possibilité d\'ajouter de modulateur', $runEx);
        }

        $stm = $this->getServiceLocator()->get('applicationTypeModulateur');
        /* @var $stm \Application\Service\TypeModulateur */
        if (0 === $stm->count( $stm->finderByEtape($etape) ) ){
            return $this->cannotDoThat('Aucun modulateur ne peut être saisi sur cette étape', $runEx);
        }

        return true;
    }

    public function canEditCentresCouts($etape, $runEx=false)
    {
        if (!$etape instanceof EtapeEntity) {
            $etape = $this->get($etape);
        }

        $ir = $this->getServiceContext()->getSelectedIdentityRole();
        if ($ir instanceof \Application\Acl\ComposanteRole){
            if ($etape->getStructure() != $ir->getStructure()){
                return $this->cannotDoThat('Vous n\'avez pas les autorisations nécessaires pour paramétrer les centres de coûts de cette structure', $runEx);
            }
        }elseif($ir->getRoleId() == \Application\Acl\Role::ROLE_ID || $ir->getRoleId() == 'user'){
            return $this->cannotDoThat('Vous n\'êtes pas autorisé à paramétrer les centres de coûts', $runEx);
        }elseif($ir instanceof \Application\Acl\IntervenantRole){
            return $this->cannotDoThat('Les intervenants n\'ont pas la possibilité de paramétrer les centres de coûts', $runEx);
        }

//        $stm = $this->getServiceLocator()->get('applicationTypeModulateur');
//        /* @var $stm \Application\Service\TypeModulateur */
//        if (0 === $stm->count( $stm->finderByEtape($etape) ) ){
//            return $this->cannotDoThat('Aucun modulateur ne peut être saisi sur cette étape', $runEx);
//        }

        return true;
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
        return parent::save($entity);
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