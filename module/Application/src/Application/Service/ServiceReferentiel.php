<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\ServiceReferentiel as ServiceReferentielEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\Validation as ValidationEntity;
use Application\Entity\Service\Recherche;

/**
 * Description of ServiceReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentiel extends AbstractEntityService
{
    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\ServiceReferentiel';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'seref';
    }

    /**
     * Initialise une requête
     * Permet de retourner des valeurs par défaut ou de les forcer en cas de besoin
     * Format de sortie : array( $qb, $alias ).
     *
     * @param QueryBuilder|null $qb      Générateur de requêtes
     * @param string|null $alias         Alias d'entité
     * @return array
     */
    public function initQuery(QueryBuilder $qb=null, $alias=null)
    {
        list($qb, $alias) = parent::initQuery($qb, $alias);

        $this->join( $this->getServiceStructure()          , $qb, 'structure'  , true, $alias )
             ->join( $this->getServiceFonctionReferentiel(), $qb, 'fonction'   , true, $alias )
             ->join( $this->getServiceIntervenant()        , $qb, 'intervenant', true, $alias );

        return [$qb,$alias];
    }

    /**
     *
     * @param TypeIntervenantEntity $typeIntervenant
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByTypeIntervenant(TypeIntervenantEntity $typeIntervenant=null, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if ($typeIntervenant){
            $this->join( $this->getServiceIntervenant(), $qb, 'intervenant', $alias );
            $this->getServiceIntervenant()->finderByType( $typeIntervenant, $qb );
        }
        return $qb;
    }


    /**
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByStructureAff(StructureEntity $structure=null, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if ($structure){
            $this->join( $this->getServiceIntervenant(), $qb, 'intervenant', $alias );
            $this->getServiceIntervenant()->finderByStructure( $structure, $qb );
        }
        return $qb;
    }


    /**
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByStructureEns(StructureEntity $structure=null, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if ($structure){
            $this->finderByStructure( $structure, $qb, $alias );
        }
        return $qb;
    }


    /**
     * Retourne le query builder permettant de rechercher les services référentiels
     * selon la composante spécifiée.
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByComposante(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $iAlias             = $this->getServiceIntervenant()->getAlias();
        $filter = "($iAlias.structure = :composante OR $alias.structure = :composante)";
        $qb->andWhere($filter)->setParameter('composante', $structure);

        return $qb;
    }

    /**
     * Retourne la liste des services selon le contexte donné
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this->join( $this->getServiceIntervenant(), $qb, 'intervenant', false, $alias );
        $this->getServiceIntervenant()->finderByAnnee( $context->getAnnee(), $qb );

        if ($role instanceof \Application\Acl\IntervenantRole){ // Si c'est un intervenant
            $this->finderByIntervenant( $role->getIntervenant(), $qb, $alias );
        }

        return $qb;
    }

    /**
     *
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByTypeVolumeHoraire(TypeVolumeHoraireEntity $typeVolumeHoraire, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if ($typeVolumeHoraire){
            $serviceVolumeHoraireReferentiel = $this->getServiceLocator()->get('applicationVolumeHoraireReferentiel'); /* @var $serviceVolumeHoraireReferentiel VolumeHoraireReferentiel */

            $this->join( $serviceVolumeHoraireReferentiel, $qb, 'volumeHoraireReferentiel' );
            $serviceVolumeHoraireReferentiel->finderByTypeVolumeHoraire( $typeVolumeHoraire, $qb );
        }
        return $qb;
    }

    /**
     * Retourne la liste des intervenants
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return \Application\Entity\Db\ServiceReferentiel[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb
                ->addOrderBy($this->getServiceIntervenant()->getAlias().'.nomUsuel')
                ->addOrderBy($this->getServiceStructure()->getAlias().'.libelleCourt')
                ->addOrderBy($this->getServiceFonctionReferentiel()->getAlias().'.libelleCourt');

        return parent::getList($qb, $alias);
    }

    /**
     *
     * @param ServiceReferentielEntity[] $servicesReferentiels
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     */
    public function setTypeVolumeHoraire($servicesReferentiels, TypeVolumeHoraireEntity $typeVolumeHoraire)
    {
        foreach ( $servicesReferentiels as $serviceReferentiel) {
            $serviceReferentiel->setTypeVolumeHoraire($typeVolumeHoraire);
        }
    }

    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return ServiceReferentielEntity
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        if ($this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $entity->setIntervenant( $this->getContextProvider()->getGlobalContext()->getIntervenant() );
        }
        return $entity;
    }

    /**
     * Sauvegarde une entité
     *
     * @param ServiceReferentielEntity $entity
     * @throws \Common\Exception\RuntimeException
     */
    public function save($entity)
    {
        $this->getEntityManager()->getConnection()->beginTransaction();
        try{
            if (! $entity->getIntervenant() && $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
                $entity->setIntervenant( $this->getContextProvider()->getGlobalContext()->getIntervenant() );
            }
            if (! $this->getAuthorize()->isAllowed($entity, $entity->getId() ? 'update' : 'create')){
                throw new \BjyAuthorize\Exception\UnAuthorizedException('Saisie interdite');
            }

            $serviceAllreadyExists = null;
            if (! $entity->getId()){ // uniquement pour les nouveaux services!!
                $serviceAllreadyExists = $this->getRepo()->findOneBy([
                    'intervenant'  => $entity->getIntervenant(),
                    'structure'    => $entity->getStructure(),
                    'fonction'     => $entity->getFonction(),
                    'commentaires' => $entity->getCommentaires(),
                ]);
            }
            if ($serviceAllreadyExists){
                $result = $serviceAllreadyExists;
            }else{
                $result = parent::save($entity);
            }

            /* Sauvegarde automatique des volumes horaires associés */
            $serviceVolumeHoraire = $this->getServiceLocator()->get('applicationVolumeHoraireReferentiel');
            /* @var $serviceVolumeHoraire VolumeHoraireReferentiel */
            foreach( $entity->getVolumeHoraireReferentiel() as $volumeHoraire ){
                if ($result !== $entity) $volumeHoraire->setServiceReferentiel($result);
                if ($volumeHoraire->getRemove()){
                    $serviceVolumeHoraire->delete($volumeHoraire);
                }else{
                    $serviceVolumeHoraire->save( $volumeHoraire );
                }
            }
            $this->getEntityManager()->getConnection()->commit();
        }catch (Exception $e ){
            $this->getEntityManager()->getConnection()->rollBack();
            throw $e;
        }
        return $result;
    }

    /**
     *
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureRef
     * @return QueryBuilder
     */
    public function finderReferentielsNonValides(
            TypeVolumeHoraireEntity $typeVolumeHoraire,
            IntervenantEntity $intervenant = null,
            StructureEntity $structureRef = null)
    {
        $dqlNotExists = <<<EOS
SELECT vhv FROM Application\Entity\Db\VolumeHoraireReferentiel vhv
JOIN vhv.validation v
WHERE vhv = vh
EOS;

        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select("s2, i, vh, f, strref")
                ->from("Application\Entity\Db\ServiceReferentiel", 's2')
                ->join("s2.intervenant", "i")
                ->join("s2.volumeHoraireReferentiel", 'vh')
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :ctvh")->setParameter('ctvh', $typeVolumeHoraire->getCode())
                ->join("s2.structure", 'strref')
                ->join("s2.fonction", 'f')
                ->andWhere("NOT EXISTS ($dqlNotExists)")
                ->addOrderBy("strref.libelleCourt", 'asc')
                ->addOrderBy("s2.histoModification", 'asc');

        if ($intervenant) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if ($structureRef) {
            $qb->andWhere("strref = :structureRef")->setParameter('structureRef', $structureRef);
        }

//        print_r($qb->getQuery()->getSQL());

        return $qb;
    }

    /**
     *
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param TypeValidationEntity $validation
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureRef
     * @param StructureEntity $structureValidation
     * @return QueryBuilder
     */
    public function finderReferentielsValides(
            TypeVolumeHoraireEntity $typeVolumeHoraire,
            ValidationEntity $validation = null,
            IntervenantEntity $intervenant = null,
            StructureEntity $structureRef = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select("s, i, vh, f, strref")
                ->from("Application\Entity\Db\ServiceReferentiel", 's')
                ->join("s.intervenant", "i")
                ->join("s.volumeHoraireReferentiel", 'vh')
                ->join("s.structure", 'strref')
                ->join("s.fonction", 'f')
                ->join("vh.validation", "v")
                ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :ctvh")->setParameter('ctvh', $typeVolumeHoraire->getCode())
                ->join("v.typeValidation", 'tv')
                ->join("v.structure", 'str') // validés par la structure spécifiée
                ->orderBy("v.histoModification", 'desc')
                ->addOrderBy("strref.libelleCourt", 'asc');

        if ($validation) {
            $qb->andWhere("v = :validation")->setParameter('validation', $validation);
        }
        if ($intervenant) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if ($structureRef) {
            $qb->andWhere("strref = :structureRef")->setParameter('structureRef', $structureRef);
        }
        return $qb;
    }

    public function setRealisesFromPrevus( ServiceReferentielEntity $service )
    {
        $prevus = $service
                    ->getVolumeHoraireReferentielListe()->getChild()
                    ->setTypeVolumeHoraire( $this->getServiceTypeVolumeHoraire()->getPrevu() )
                    ->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getValide() );

        $realises = $service
                    ->getVolumeHoraireReferentielListe()->getChild()
                    ->setTypeVolumeHoraire( $this->getServiceTypeVolumeHoraire()->getRealise() )
                    ->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getSaisi() );

        foreach( $realises->get() as $vh ){
            /* @var $vh \Application\Entity\Db\VolumeHoraire */
            $vh->setRemove(true);
        }

        foreach( $prevus->get() as $vh ){
            $nvh = new \Application\Entity\Db\VolumeHoraireReferentiel;
            $nvh->setTypeVolumeHoraire  ( $this->getServiceTypeVolumeHoraire()->getRealise() );
            $nvh->setServiceReferentiel ( $service                   );
            $nvh->setHeures             ( $vh->getHeures()           );
            $service->addVolumeHoraireReferentiel($nvh);
        }
        $this->save($service);
    }

    /**
     * @return Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }

    /**
     * @return Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return FonctionReferentiel
     */
    protected function getServiceFonctionReferentiel()
    {
        return $this->getServiceLocator()->get('applicationFonctionReferentiel');
    }

    /**
     * @return TypeVolumeHoraire
     */
    protected function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationTypeVolumeHoraire');
    }

    /**
     * @return EtatVolumeHoraire
     */
    protected function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
    }
}