<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Etape as EtapeEntity;
use Application\Entity\Db\Service as ServiceEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\EtatVolumeHoraire as EtatVolumeHoraireEntity;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeIntervention as TypeInterventionEntity;
use Application\Entity\Db\Validation as ValidationEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\NiveauEtape as NiveauEtapeEntity;
use Application\Entity\Service\Recherche;
use Zend\Session\Container as SessionContainer;
use Application\Form\Service\RechercheHydrator;

/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Service extends AbstractEntityService
{
    /**
     *
     * @var SessionContainer
     */
    private $rechercheSessionContainer;

    /**
     *
     * @var Recherche
     */
    private $recherche;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Service';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 's';
    }

    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return ServiceEntity
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        $entity->setAnnee( $this->getContextProvider()->getGlobalContext()->getAnnee() );
        $entity->setValiditeDebut(new \DateTime );
        if ($this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $entity->setIntervenant( $this->getContextProvider()->getGlobalContext()->getIntervenant() );
        }
        return $entity;
    }


    /**
     *
     * @return SessionContainer
     */
    protected function getRechercheSessionContainer()
    {
        if (null === $this->rechercheSessionContainer) {
            $this->rechercheSessionContainer = new SessionContainer(get_class($this).'_Recherche');
        }
        return $this->rechercheSessionContainer;
    }

    /**
     * 
     * @return RechercheHydrator
     */
    protected function getRechercheHydrator()
    {
        return $this->getServiceLocator()->get('ServiceRechercheHydrator');
    }

    /**
     * Les paramètres de recherche sont également remplis à l'aide du contexte local
     *
     * @return Recherche
     */
    public function loadRecherche()
    {
        if (null === $this->recherche){
            $this->recherche = new Recherche;
            $session = $this->getRechercheSessionContainer();
            if ($session->offsetExists('data')){
                $this->getRechercheHydrator()->hydrate($session->data, $this->recherche);
            }
        }

        if (! $this->recherche->getTypeVolumeHoraire()){
            $serviceTypeVolumehoraire = $this->getServiceLocator()->get('applicationTypeVolumeHoraire');
            /* @var $serviceTypeVolumehoraire TypeVolumeHoraire */
            $this->recherche->setTypeVolumeHoraire( $serviceTypeVolumehoraire->getPrevu() );
        }

        if (! $this->recherche->getEtatVolumeHoraire()){
            $serviceEtatVolumehoraire = $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
            /* @var $serviceEtatVolumehoraire EtatVolumeHoraire */
            $this->recherche->setEtatVolumeHoraire( $serviceEtatVolumehoraire->getSaisi() );
        }

        $this->recherche->setIntervenant        ( $this->getContextProvider ()->getLocalContext ()->getIntervenant()        );
        $this->recherche->setStructureEns       ( $this->getContextProvider ()->getLocalContext ()->getStructure()          );
        $this->recherche->setNiveauEtape        ( $this->getContextProvider ()->getLocalContext ()->getNiveau()             );
        $this->recherche->setEtape              ( $this->getContextProvider ()->getLocalContext ()->getEtape()              );
        $this->recherche->setElementPedagogique ( $this->getContextProvider ()->getLocalContext ()->getElementPedagogique() );
        return $this->recherche;
    }

    /**
     * Les paramètres de recherche sont également sauvegardés dans le contexte local
     *
     * @param Recherche $recherche
     * @return self
     */
    public function saveRecherche( Recherche $recherche )
    {
        if ($recherche !== $this->recherche){
            $this->recherche = $recherche;
        }
        $data = $this->getRechercheHydrator()->extract($recherche);
        $session = $this->getRechercheSessionContainer();
        $session->data = $data;

        $this->getContextProvider ()->getLocalContext ()->setIntervenant(       $recherche->getIntervenant()        );
        $this->getContextProvider ()->getLocalContext ()->setStructure(         $recherche->getStructureEns()       );
        $this->getContextProvider ()->getLocalContext ()->setNiveau(            $recherche->getNiveauEtape()        );
        $this->getContextProvider ()->getLocalContext ()->setEtape(             $recherche->getEtape()              );
        $this->getContextProvider ()->getLocalContext ()->setElementPedagogique($recherche->getElementPedagogique() );
        return $this;
    }


    /**
     * Sauvegarde une entité
     *
     * @param ServiceEntity $entity
     * @throws \Common\Exception\RuntimeException
     */
    public function save($entity)
    {
        if (! $entity->getEtablissement()){
            $entity->setEtablissement( $this->getContextProvider()->getGlobalContext()->getEtablissement() );
        }
        if (! $entity->getIntervenant() && $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $entity->setIntervenant( $this->getContextProvider()->getGlobalContext()->getIntervenant() );
        }
        if (! $this->getAuthorize()->isAllowed($entity, $entity->getId() ? 'update' : 'create')){
            throw new \BjyAuthorize\Exception\UnAuthorizedException('Saisie interdite');
        }
        $result = parent::save($entity);
        /* Sauvegarde automatique des volumes horaires associés */
        $serviceVolumeHoraire = $this->getServiceLocator()->get('applicationVolumeHoraire');
        /* @var $serviceVolumeHoraire VolumeHoraire */
        foreach( $entity->getVolumeHoraire() as $volumeHoraire ){
            if ($volumeHoraire->getRemove()){
                $serviceVolumeHoraire->delete($volumeHoraire);
            }else{
                $serviceVolumeHoraire->save( $volumeHoraire );
            }
        }
        return $result;
    }

    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param EtapeEntity $etape
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByEtape( EtapeEntity $etape, QueryBuilder $qb=null, $alias=null )
    {
        $serviceElement = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $serviceElement Element */

        list($qb,$alias) = $this->initQuery($qb, $alias);
        $this->leftJoin( $serviceElement, $qb, 'elementPedagogique');
        $serviceElement->finderByEtape($etape, $qb);
        return $qb;
    }

    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param EtapeEntity $etape
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByNiveauEtape(NiveauEtapeEntity $niveauEtape, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if ($niveauEtape && $niveauEtape->getId() !== '-'){
            $serviceElement = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $serviceElement Element */
            $serviceEtape   = $this->getServiceLocator()->get('applicationEtape');              /* @var $serviceEtape   Etape */

            $this->leftJoin( $serviceElement, $qb, 'elementPedagogique');
            $serviceElement->join( $serviceEtape, $qb, 'etape' );
            $serviceEtape->finderByNiveau($niveauEtape, $qb);
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
            $serviceVolumeHoraire = $this->getServiceLocator()->get('applicationVolumeHoraire'); /* @var $serviceVolumeHoraire VolumeHoraire */

            $this->join( $serviceVolumeHoraire, $qb, 'volumeHoraire' );
            $serviceVolumeHoraire->finderByTypeVolumeHoraire( $typeVolumeHoraire, $qb );
        }
        return $qb;
    }

    /**
     *
     * @param EtatVolumeHoraireEntity $etatVolumeHoraire
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByEtatVolumeHoraire(EtatVolumeHoraireEntity $etatVolumeHoraire, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire){
            $serviceVolumeHoraire = $this->getServiceLocator()->get('applicationVolumeHoraire'); /* @var $serviceVolumeHoraire VolumeHoraire */

            $this->join( $serviceVolumeHoraire, $qb, 'volumeHoraire' );
            $serviceVolumeHoraire->finderByEtatVolumeHoraire( $etatVolumeHoraire, $qb );
        }
        return $qb;
    }

    /**
     * Retourne le query builder permettant de rechercher les services prévisionnels
     * selon la composante spécifiée.
     * 
     * Càd les services prévisionnels satisfaisant au moins l'un des critères suivants :
     * - la structure d'enseignement (champ 'structure_ens') est la structure spécifiée;
     * - la structure d'affectation (champ 'structure_aff')  est la structure spécifiée;
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByComposante(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $serviceStructure   = $this->getServiceStructure();
        $serviceIntervenant = $this->getServiceIntervenant();
        $iAlias             = $serviceIntervenant->getAlias();

        $this->join( $serviceIntervenant, $qb, 'intervenant' );
        $this->join( $serviceStructure, $qb, 'structureAff', false, null, 's_aff' );
        $this->leftJoin( $serviceStructure, $qb, 'structureEns', false, null, 's_ens' );

        $filter = "($iAlias.structure = :composante OR s_aff = :composante OR s_ens = :composante)";
        $qb->andWhere($filter)->setParameter('composante', $structure);

        return $qb;
    }

    /**
     * Filtre la liste des services selon lecontexte courant
     * 
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return QueryBuilder
     */
    public function finderByContext( QueryBuilder $qb=null, $alias=null )
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this->finderByAnnee( $context->getannee(), $qb, $alias ); // Filtre d'année obligatoire

        if ($role instanceof \Application\Acl\IntervenantRole){ // Si c'est un intervenant
            $this->finderByIntervenant( $role->getIntervenant(), $qb, $alias );
        }

        return $qb;
    }

    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param string $statutInterv "Application\Entity\Db\IntervenantPermanent" ou "Application\Entity\Db\IntervenantExterieur"
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
     * Retourne la liste des services dont les volumes horaires sont validés ou non.
     *
     * @param boolean|\Application\Entity\Db\Validation $validation <code>true</code>, <code>false</code> ou 
     * bien une Validation précise
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByValidation($validation, QueryBuilder $qb = null, $alias = null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb     ->addSelect('vhv')
                ->join("$alias.volumeHoraire", 'vhv');
                
        if ($validation instanceof \Application\Entity\Db\Validation) {
            $qb
                    ->join("vhv.validation", "v")
                    ->andWhere("v = :validation")->setParameter('validation', $validation);
        }
        else {
            $value = $validation ? 'is not null' : 'is null';
            $qb     ->leftJoin("vhv.validation", 'vv')
                    ->andWhere("vv $value");
        }
        
        return $qb;
    }
    
    /**
     * Recherche par type 
     *
     * @param TypeValidation|string $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByTypeValidation($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $type = $this->getServiceLocator()->get('ApplicationValidation')->normalizeTypeValidation($type);
        
        $qb
                ->join("$alias.volumeHoraire", 'tvvh')
                ->join("tvvh.validation", "tvv")
                ->join("tvv.typeValidation", 'tvtv')
                ->andWhere("tvtv = :tvtv")->setParameter('tvtv', $type);

        return $qb;
    }

    /**
     * Retourne la liste des services dont les volumes horaires ont été validés par une structure.
     *
     * @param \Application\Entity\Db\Structure $structure 
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByStructureValidation(\Application\Entity\Db\Structure $structure, QueryBuilder $qb = null, $alias = null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb     ->addSelect("vhs, vs")
                ->join("$alias.volumeHoraire", 'vhs')
                ->join("vhs.validation", "vs")
                ->andWhere("vs.structure = :structurev")->setParameter('structurev', $structure);
        
        return $qb;
    }

    /**
     * Retourne la liste des services dont les volumes horaires ont fait ou non l'objet d'un contrat/avenant.
     *
     * @param boolean|\Application\Entity\Db\Contrat $contrat <code>true</code>, <code>false</code> ou 
     * bien un Contrat précis
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContrat($contrat, QueryBuilder $qb = null, $alias = null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb     ->addSelect("vhc")
                ->join("$alias.volumeHoraire", 'vhc');
                
        if ($contrat instanceof \Application\Entity\Db\Contrat) {
            $qb     ->addSelect("c")
                    ->join("vhc.contrat", "c")
                    ->andWhere("c = :contrat")->setParameter('contrat', $contrat);
        }
        else {
            $value = $contrat ? 'is not null' : 'is null';
            $qb->andWhere("vhc.contrat $value");
        }
        
        return $qb;
    }
    
    /**
     * 
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureEns
     * @return QueryBuilder
     */
    public function finderServicesNonValides(
            IntervenantEntity $intervenant = null,
            StructureEntity $structureEns = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select("s2, i, vh, strens, strens2")
                ->from("Application\Entity\Db\Service", 's2')
                ->join("s2.intervenant", "i")
                ->join("s2.volumeHoraire", 'vh')
                ->join("s2.structureEns", 'strens')
                ->join("strens.structureNiv2", 'strens2')
                ->andWhere('NOT EXISTS (SELECT sv FROM Application\Entity\Db\VServiceValide sv WHERE sv.volumeHoraire = vh)')
                ->addOrderBy("strens.libelleCourt", 'asc')
                ->addOrderBy("s2.histoModification", 'asc');
        
        if ($intervenant) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if ($structureEns) {
            $qb->andWhere("strens = :structureEns OR strens2 = :structureEns")->setParameter('structureEns', $structureEns);
        }
        
//        var_dump($qb->getQuery()->getSQL());
        
        return $qb;
    }
    
    /**
     * 
     * @param TypeValidationEntity $validation
     * @param IntervenantEntity $intervenant
     * @param StructureEntity $structureEns
     * @param StructureEntity $structureValidation
     * @return QueryBuilder
     */
    public function finderServicesValides(
            ValidationEntity $validation = null, 
            IntervenantEntity $intervenant = null, 
            StructureEntity $structureEns = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select("s, i, vh, strens, strens2")
                ->from("Application\Entity\Db\Service", 's')
                ->join("s.intervenant", "i")
                ->join("s.volumeHoraire", 'vh')
                ->join("s.structureEns", 'strens')
                ->join("strens.structureNiv2", 'strens2')
                ->join("vh.validation", "v")
                ->join("v.typeValidation", 'tv')
                ->join("v.structure", 'str') // validés par la structure spécifiée
                ->orderBy("v.histoModification", 'desc')
                ->addOrderBy("strens.libelleCourt", 'asc');
        
        if ($validation) {
            $qb->andWhere("v = :validation")->setParameter('validation', $validation);
        }
        if ($intervenant) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if ($structureEns) {
            $qb->andWhere("strens = :structureEns OR strens2 = :structureEns")->setParameter('structureEns', $structureEns);
        }
        return $qb;
    }

    /**
     * Retourne les données du TBL des services en fonction des critères de recherche transmis
     *
     * @param Recherche $recherche
     * @param array $typesIntervention
     * @return array
     */
    public function getTableauBordExport( Recherche $recherche )
    {
        $res = [];
        $resInt = [];
        $typesIntervention = [];

        $conditions = [
            'annee_id = '.$this->getContextProvider()->getGlobalContext()->getAnnee()->getId()
        ];
        if ($c1 = $recherche->getTypeVolumeHoraire()  ) $conditions['type_volume_horaire_id'] = '(type_volume_horaire_id IS NULL OR type_volume_horaire_id = ' . $c1->getId().')';
        if ($c2 = $recherche->getEtatVolumeHoraire()  ) $conditions['etat_volume_horaire_id'] = '(etat_volume_horaire_id IS NULL OR etat_volume_horaire_id = ' . $c2->getId().')';
        if ($c3 = $recherche->getTypeIntervenant()    ) $conditions['type_intervenant_id']    = '(type_intervenant_id IS NULL OR type_intervenant_id = '    . $c3->getId().')';
        if ($c4 = $recherche->getIntervenant()        ) $conditions['intervenant_id']         = '(intervenant_id IS NULL OR intervenant_id = '         . $c4->getId().')';
        //if ($c5 = $recherche->getNiveauFormation()    ) $conditions['niveau_formation_id']    = '(niveau_formation_id IS NULL OR niveau_formation_id = '    . $c5->getId().')';
        if ($c6 = $recherche->getEtape()              ) $conditions['etape_id']               = '(etape_id IS NULL OR etape_id = '               . $c6->getId().')';
        if ($c7 = $recherche->getElementPedagogique() ) $conditions['element_pedagogique_id'] = '(element_pedagogique_id IS NULL OR element_pedagogique_id = ' . $c7->getId().')';
        if ($c8 = $recherche->getStructureAff()       ) $conditions['structure_aff_id']       = '(structure_aff_id IS NULL OR structure_aff_id = '       . $c8->getId().')';
        if ($c9 = $recherche->getStructureEns()       ) $conditions['structure_ens_id']       = '(structure_ens_id IS NULL OR structure_ens_id = '       . $c9->getId().')';

        $sql = 'SELECT * FROM V_TBL_SERVICE_EXPORT WHERE '.implode( ' AND ', $conditions ).' '
              .'ORDER BY INTERVENANT_NOM, SERVICE_STRUCTURE_AFF_LIBELLE, SERVICE_STRUCTURE_ENS_LIBELLE, ETAPE_LIBELLE, ELEMENT_LIBELLE';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while( $d = $stmt->fetch()){
            $sid = $d['SERVICE_ID'];
            $iid = $d['INTERVENANT_ID'];
            $res[$sid] = [

                'intervenant-code'              =>          $d['INTERVENANT_CODE'],
                'intervenant-nom'               =>          $d['INTERVENANT_NOM'],
                'intervenant-statut-libelle'    =>          $d['INTERVENANT_STATUT_LIBELLE'],
                'intervenant-type-libelle'      =>          $d['INTERVENANT_TYPE_LIBELLE'],
                'service-structure-aff-libelle' =>          $d['SERVICE_STRUCTURE_AFF_LIBELLE'],

                'service-structure-ens-libelle' =>          $d['SERVICE_STRUCTURE_ENS_LIBELLE'],
                'etablissement-libelle'         =>          $d['ETABLISSEMENT_LIBELLE'],
                'etape-code'                    =>          $d['ETAPE_CODE'],
                'etape-libelle'                 =>          $d['ETAPE_LIBELLE'],
                'element-code'                  =>          $d['ELEMENT_CODE'],
                'element-libelle'               =>          $d['ELEMENT_LIBELLE'],
                'element-periode-libelle'       =>          $d['ELEMENT_PERIODE_LIBELLE'],
                'element-ponderation-compl'     => $d['ELEMENT_PONDERATION_COMPL'] === null ? null : (float)$d['ELEMENT_PONDERATION_COMPL'],
                'element-source-libelle'        =>          $d['ELEMENT_SOURCE_LIBELLE'],
                'commentaires'                  =>          $d['COMMENTAIRES'],

                'heures-service-statutaire'     => (float)  $d['HEURES_SERVICE_STATUTAIRE'],
                'heures-service-du-modifie'     => (float)  $d['HEURES_SERVICE_DU_MODIFIE'],
                'heures-reelles'                => (float)  $d['HEURES_REFERENTIEL'], // Attention : le référentiel est à compter dans les heures!!
                'heures-assurees'               => (float)  $d['HEURES_ASSUREES'],
                'heures-compl'                  => (float)  $d['HEURES_COMPL'],
                'heures-sous-service'           => (float)  $d['HEURES_SOUS_SERVICE'],
                'heures-non-payees'             => (float)  $d['HEURES_NON_PAYEES'],
                'heures-referentiel'            => (float)  $d['HEURES_REFERENTIEL'],

                'types-intervention'            => [],
            ];
            if (! isset($resInt[$iid])) $resInt[$iid] = [];
            $resInt[$iid][] = $sid;
        }

        if ($c2 = $recherche->getEtatVolumeHoraire()  ) $conditions['etat_volume_horaire_id'] = 'etat_volume_horaire_ordre >= ' . $c2->getId();
        $sql = 'SELECT * FROM V_TBL_SERVICE_EXPORT_VH WHERE '.implode( ' AND ', $conditions );
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while( $d = $stmt->fetch()){
            $sid = $d['SERVICE_ID'];
            $tid = $d['TYPE_INTERVENTION_ID'];
            if (! isset($res[$sid]['types-intervention'][$tid])){
                $res[$sid]['types-intervention'][$tid] = 0;
            }
            if (! isset($typesIntervention[$tid])){
                $typesIntervention[$tid] = $this->getServiceTypeIntervention()->get($tid);
            }if (! isset($res[$sid]['heures-reelles'])){var_dump($d);}
            $res[$sid]['types-intervention'][$tid] += (float)$d['HEURES'];
            $res[$sid]['heures-reelles'] += (float)$d['HEURES'];
        }
        usort( $typesIntervention, function($ti1,$ti2){
            return $ti1->getOrdre() > $ti2->getOrdre();
        } );

        return [
            'data'                   => $res,
            'types-intervention'     => $typesIntervention,
        ];
    }

    /**
     * 
     * @param Recherche $filter
     * @return array
     */
    public function getResumeService(Recherche $recherche)
    {
        $role           = $this->getContextProvider()->getSelectedIdentityRole();
        if ($role instanceof \Application\Acl\ComposanteRole){
            $structureEnsId = $role->getStructure()->getId();
        }elseif ($recherche->getStructureEns()) {
            $structureEnsId = $recherche->getStructureEns()->getId();
        }else{
            $structureEnsId = null;
        }

        $whereFilter          = array();
        $filtreOffreFormation = false;
        if ($recherche->getIntervenant()) {
            $whereFilter[] = 'INTERVENANT_ID = ' . $recherche->getIntervenant()->getId();
        }
        if (isset($filter->structureEns)) {
            $whereFilter[] = 'STRUCTURE_ENS_ID = ' . $filter->structureEns->getId();
        }
        if (isset($filter->statutInterv)) {
            $e = new TypeIntervenantEntity();
            $whereFilter[] = "TYPE_INTERVENANT_CODE = '" . $e->classToCode[$filter->statutInterv] . "'";
        }
        if (isset($filter->etape)) {
            $whereFilter[] = 'ETAPE_ID = ' . $filter->etape->getId();
            $filtreOffreFormation = true;
        }
        if (isset($filter->elementPedagogique)) {
            $whereFilter[] = 'ELEMENT_PEDAGOGIQUE_ID = ' . $filter->elementPedagogique->getId();
            $filtreOffreFormation = true;
        }
        $whereFilter = $whereFilter ? ' AND ' . implode(' AND ', $whereFilter) : null;

        if ($structureEnsId){
            $structureFilter = "(v.STRUCTURE_ENS_ID = $structureEnsId or v.STRUCTURE_AFF_ID = $structureEnsId)";
        }else{
            $structureFilter = "1=1";
        }

        $queryServices = <<<EOS
select
  
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE ,
  TYPE_INTERVENTION_ID ,
  sum(TOTAL_HEURES) TOTAL_HEURES,
  v.service_du SERVICE_DU,
  v.heures_comp HEURES_COMP
from V_RESUME_SERVICE v
where $structureFilter
  $whereFilter
group by 
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE ,
  TYPE_INTERVENTION_ID ,
  v.service_du,
  v.heures_comp
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($queryServices);
        $data = $stmt->fetchAll();
        $dataService = array();
        foreach ($data as $r) {
            $intervenantId = $r['INTERVENANT_ID'];
            $typeId        = $r['TYPE_INTERVENTION_ID'];
            $dataService[$intervenantId]['intervenant'] = $r;
            if ($typeId) {
                $dataService[$intervenantId]['service'][$typeId] = $r['TOTAL_HEURES'];
            }
        }
//        var_dump($queryServices, $dataService);die;
        
        $whereFilter = array();
        if (isset($filter->intervenant)) {
            $whereFilter[] = 'INTERVENANT_ID = ' . $filter->intervenant->getId();
        }
        if (isset($filter->structureEns)) {
            $whereFilter[] = 'STRUCTURE_ENS_ID = ' . $filter->structureEns->getId();
        }
        if (isset($filter->statutInterv)) {
            $e = new TypeIntervenantEntity();
            $whereFilter[] = "TYPE_INTERVENANT_CODE = '" . $e->classToCode[$filter->statutInterv] . "'";
        }
        $whereFilter = $whereFilter ? ' AND ' . implode(' AND ', $whereFilter) : null;
        
        $queryReferentiel = <<<EOS
select 
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE ,
  sum(TOTAL_HEURES) TOTAL_HEURES
from V_RESUME_REFERENTIEL v
where $structureFilter
  $whereFilter
group by 
  NOM_USUEL ,
  PRENOM ,
  INTERVENANT_ID ,
  SOURCE_CODE ,
  TYPE_INTERVENANT_CODE 
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($queryReferentiel);
        $data = $stmt->fetchAll();
        $dataReferentiel = array();
        foreach ($data as $r) {
            $intervenantId = $r['INTERVENANT_ID'];
            // NB: si un filtre 'etape' ou 'elementPedagogique' est spécifié, cela signifie que l'on recherche des 
            // services prévisionnels. Pour chaque intervenant, on ne conserve donc une ligne de référentiel que s'il 
            // existe du service prévisionnel pour ce même intervenant.
            if ($filtreOffreFormation && !isset($dataService[$intervenantId])) {
                continue;
            }
            $dataReferentiel[$intervenantId] = array(
                'intervenant' => $r,
                'referentiel' => $r['TOTAL_HEURES'],
            );
        }

        $data = \Zend\Stdlib\ArrayUtils::merge($dataReferentiel, $dataService, true);

        uasort($data, function($a, $b) { 
            return strcmp(
                    $a['intervenant']['NOM_USUEL'] . $a['intervenant']['PRENOM'], 
                    $b['intervenant']['NOM_USUEL'] . $b['intervenant']['PRENOM']);
        });
        
        return $data;
    }
    
    /**
     * Retourne le total des heures RÉELLES de référentiel et de service d'un intervenant.
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return float
     */
    public function getTotalHeuresReelles(\Application\Entity\Db\Intervenant $intervenant)
    {
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        
        /**
         * Service
         */
        $sql = <<<EOS
select sum(v.TOTAL_HEURES) as TOTAL_HEURES
from V_RESUME_SERVICE v
where v.INTERVENANT_ID = :intervenant and v.ANNEE_ID = :annee
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, array(
            'intervenant' => $intervenant->getId(),
            'annee' => $annee->getId()));
        $r = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $totalService = isset($r[0]) ? (float)$r[0] : 0.0;
        
        /**
         * Référentiel
         */
        $sql = <<<EOS
select sum(v.TOTAL_HEURES) as TOTAL_HEURES
from V_RESUME_REFERENTIEL v
where v.INTERVENANT_ID = :intervenant and v.ANNEE_ID = :annee
EOS;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, array(
            'intervenant' => $intervenant->getId(),
            'annee' => $annee->getId()));
        $r = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $totalRef = isset($r[0]) ? (float)$r[0] : 0.0;

        return $totalService + $totalRef;
    }

    /**
     * Détermine si un service est assuré localement (c'est-à-dire dans l'université) ou sur un autre établissement
     *
     * @param \Application\Entity\Db\Service $service
     * @return boolean
     */
    public function isLocal( ServiceEntity $service )
    {
        if (! $service->getEtablissement()) return true; // par défaut
        if ($service->getEtablissement() === $this->getContextProvider()->getGlobalContext()->getEtablissement()) return true;
        return false;
    }

    /**
     * Retourne la période courante d'un service
     * @param \Application\Entity\Db\Service $service
     * @return \Application\Entity\Db\Periode
     */
    public function getPeriode(ServiceEntity $service)
    {
        if (! $this->isLocal($service)) return null;
        if (! $service->getElementPedagogique()) return null;
        if (! $service->getElementPedagogique()->getPeriode()) return null;
        return $service->getElementPedagogique()->getPeriode();
    }

    /**
     *
     * @param \Application\Entity\Db\Service $service
     * @return \Application\Entity\Db\Periode[]
     */
    public function getPeriodes(ServiceEntity $service)
    {
        $p = $this->getPeriode($service);
        if (null === $p){
            $periodeService = $this->getServiceLocator()->get('applicationPeriode'); /* @var $periodeService Periode */
            // Pas de période donc toutes les périodes sont autorisées
            return $periodeService->getEnseignement();
        }else{
            return [$p->getId() => $p];
        }
    }

    /**
     *
     * @param ServiceEntity|ServiceEntity[] $services
     * @return TypeInterventionEntity[]
     */
    public function getTypesIntervention($services)
    {
        if ($services instanceof ServiceEntity) $services = [$services];
        $typesIntervention = [];
        foreach( $services as $service ){
            if (! $service instanceof ServiceEntity){
                throw new \Common\Exception\LogicException('Seules des entités Service doivent être passées en paramètre');
            }
            if ($ep = $service->getElementPedagogique()){
                foreach( $ep->getTypeIntervention() as $typeIntervention ){
                    $typesIntervention[$typeIntervention->getId()] = $typeIntervention;
                }
            }
        }
        usort( $typesIntervention, function($ti1,$ti2){
            return $ti1->getOrdre() > $ti2->getOrdre();
        } );
        return $typesIntervention;
    }

    public function canHaveMotifNonPaiement(ServiceEntity $service, $runEx = false)
    {
        if ($service->getIntervenant() instanceof \Application\Entity\Db\IntervenantExterieur){
            return $this->cannotDoThat("Un intervenant vacataire ne peut pas avoir de motif de non paiement", $runEx);
        }
        if ($this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            return $this->cannotDoThat("Les intervenants n'ont pas le droit de visualiser ou modifier les motifs de non paiement", $runEx);
        }
        return true;
    }

    /**
     * Détermine si on peut ajouter un nouveau service ou non
     *
     * @param \Application\Entity\Db\Intervenant $intervenant Eventuel intervenant concerné
     * @deprecated
     * @return boolean
     */
    public function canAdd($intervenant = null, $runEx = false)
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        if (!$intervenant) {
            return $this->cannotDoThat("Anomalie : aucun intervenant spécifié.", $runEx);
        }else{
            if ($intervenant->getStatut()->getSourceCode() == \Application\Entity\Db\StatutIntervenant::NON_AUTORISE){
                return $this->cannotDoThat("Votre statut ne vous autorise pas à assurer des enseignements");
            }
        }
                
        $rulesEvaluator = new \Application\Rule\Service\SaisieServiceRulesEvaluator($intervenant);
        if (!$rulesEvaluator->execute()) {
            $message = "?";
            if ($role instanceof \Application\Acl\IntervenantRole) {
                $message = "Vous ne pouvez pas saisir de service. ";
            }
            elseif ($role instanceof \Application\Acl\ComposanteRole) {
                $message = "Vous ne pouvez pas saisir de service pour $intervenant. ";
            }
            return $this->cannotDoThat($message . $rulesEvaluator->getMessage(), $runEx);
        }
        
        return true;
    }

    /**
     *
     * @param ServiceEntity[] $services
     * @param TypeVolumeHoraireEntity $typeVolumehoraire
     */
    public function setTypeVolumehoraire($services, TypeVolumeHoraireEntity $typeVolumeHoraire)
    {
        foreach( $services as $service ){
            $service->setTypeVolumeHoraire($typeVolumeHoraire);
        }
    }

    /**
     * @return TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->get('applicationTypeIntervention');
    }

    /**
     * @return Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }
}
