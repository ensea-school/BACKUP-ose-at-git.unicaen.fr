<?php

namespace Application\Service;

use Application\Entity\Db\ElementPedagogique as ElementPedagogiqueEntity;
use Application\Entity\Db\Etablissement as EtablissementEntity;
use Application\Entity\Db\Etape as EtapeEntity;
use Application\Entity\Db\EtatVolumeHoraire as EtatVolumeHoraireEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Service as ServiceEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeIntervention as TypeInterventionEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\Validation as ValidationEntity;
use Application\Entity\NiveauEtape as NiveauEtapeEntity;
use Application\Entity\Service\Recherche;
use Application\Form\Service\RechercheHydrator;
use Application\Hydrator\Service\Traits\RechercheHydratorAwareTrait;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\LocalContextAwareTrait;
use Application\Service\Traits\PeriodeAwareTrait;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TypeIntervenantAwareTrait;
use Application\Service\Traits\TypeInterventionAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\VolumeHoraireAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Zend\Session\Container as SessionContainer;

/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceService extends AbstractEntityService
{
    use ElementPedagogiqueAwareTrait;
    use EtapeAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use TypeInterventionAwareTrait;
    use EtatVolumeHoraireAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use VolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;
    use PeriodeAwareTrait;
    use LocalContextAwareTrait;
    use RechercheHydratorAwareTrait;
    use ValidationAwareTrait;
    use StatutIntervenantAwareTrait;

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
        return ServiceEntity::class;
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

        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($intervenant = $role->getIntervenant()) {
            $entity->setIntervenant($intervenant);
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
            $this->rechercheSessionContainer = new SessionContainer(get_class($this) . '_Recherche');
        }

        return $this->rechercheSessionContainer;
    }



    /**
     * Les paramètres de recherche sont également remplis à l'aide du contexte local
     *
     * @return Recherche
     */
    public function loadRecherche()
    {
        if (null === $this->recherche) {
            $this->recherche = new Recherche;
            $session         = $this->getRechercheSessionContainer();
            if ($session->offsetExists('data')) {
                $this->getHydratorServiceRecherche()->hydrate($session->data, $this->recherche);
            }
        }

        if (!$this->recherche->getTypeVolumeHoraire()) {
            $this->recherche->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
        }

        if (!$this->recherche->getEtatVolumeHoraire()) {
            $this->recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
        }

        $localContext = $this->getServiceLocalContext();

        $this->recherche->setIntervenant($localContext->getIntervenant());
        $this->recherche->setStructureEns($localContext->getStructure());
        $this->recherche->setNiveauEtape($localContext->getNiveau());
        $this->recherche->setEtape($localContext->getEtape());
        $this->recherche->setElementPedagogique($localContext->getElementPedagogique());

        return $this->recherche;
    }



    /**
     * Les paramètres de recherche sont également sauvegardés dans le contexte local
     *
     * @param Recherche $recherche
     *
     * @return self
     */
    public function saveRecherche(Recherche $recherche)
    {
        if ($recherche !== $this->recherche) {
            $this->recherche = $recherche;
        }
        $data          = $this->getHydratorServiceRecherche()->extract($recherche);
        $session       = $this->getRechercheSessionContainer();
        $session->data = $data;

        $localContext = $this->getServiceLocalContext();

        $localContext->setIntervenant($recherche->getIntervenant());
        $localContext->setStructure($recherche->getStructureEns());
        $localContext->setNiveau($recherche->getNiveauEtape());
        $localContext->setEtape($recherche->getEtape());
        $localContext->setElementPedagogique($recherche->getElementPedagogique());

        return $this;
    }



    /**
     * Retourne un service unique selon ses critères précis
     *
     * @param IntervenantEntity        $intervenant
     * @param ElementPedagogiqueEntity $elementPedagogique
     * @param EtablissementEntity      $etablissement
     *
     * @return null|ServiceEntity
     */
    public function getBy(
        IntervenantEntity $intervenant,
        $elementPedagogique,
        EtablissementEntity $etablissement
    )
    {
        $result = $this->getRepo()->findBy([
            'intervenant'        => $intervenant,
            'elementPedagogique' => $elementPedagogique,
            'etablissement'      => $etablissement,
        ]);

        if (count($result) > 1) {
            foreach ($result as $sr) {
                /* @var $sr \Application\Entity\Db\Service */
                if ($sr->estNonHistorise()) return $sr;
            }

            return $result[0]; // sinon retourne le premier trouvé...
        } elseif (isset($result[0])) {
            return $result[0];
        } else {
            return null;
        }
    }



    /**
     * Sauvegarde une entité
     *
     * @param ServiceEntity $entity
     *
     * @throws Exception
     * @return ServiceEntity
     */
    public function save($entity, $plafondControl = true)
    {
        $tvhs = [];

        $this->getEntityManager()->getConnection()->beginTransaction();
        try {
            $role = $this->getServiceContext()->getSelectedIdentityRole();

            if (!$entity->getEtablissement()) {
                $entity->setEtablissement($this->getServiceContext()->getEtablissement());
            }
            if (!$entity->getIntervenant() && $intervenant = $role->getIntervenant()) {
                $entity->setIntervenant($intervenant);
            }
            if (!$this->getAuthorize()->isAllowed($entity, $entity->getId() ? 'update' : 'create')) {
                throw new \BjyAuthorize\Exception\UnAuthorizedException('Saisie interdite');
            }

            $serviceAllreadyExists = null;
            if (!$entity->getId()) { // uniquement pour les nouveaux services!!
                $serviceAllreadyExists = $this->getRepo()->findOneBy([
                    'intervenant'        => $entity->getIntervenant(),
                    'elementPedagogique' => $entity->getElementPedagogique(),
                    'etablissement'      => $entity->getEtablissement(),
                ]);
            }
            if ($serviceAllreadyExists) {
                $result = $serviceAllreadyExists;
            } else {
                $result = parent::save($entity);
            }

            /* Sauvegarde automatique des volumes horaires associés */
            $serviceVolumeHoraire = $this->getServiceVolumeHoraire();
            foreach ($entity->getVolumeHoraire() as $volumeHoraire) {
                /* @var $volumeHoraire \Application\Entity\Db\Volumehoraire */
                if ($volumeHoraire->getTemPlafondFcMaj() !== 1) {
                    $tvhs[] = $volumeHoraire->getTypeVolumeHoraire();
                }
                if ($result !== $entity) $volumeHoraire->setService($result);
                if ($volumeHoraire->getRemove()) {
                    $serviceVolumeHoraire->delete($volumeHoraire);
                } else {
                    $serviceVolumeHoraire->save($volumeHoraire, false); // pas de contrôle de plafond sur le VH ! ! !
                }
            }
            $this->getEntityManager()->getConnection()->commit();
        } catch (Exception $e) {
            $this->getEntityManager()->getConnection()->rollBack();
            throw $e;
        }
        if ($plafondControl) {
            foreach ($tvhs as $typeVolumeHoraire) {
                $this->controlePlafondFcMaj($entity->getIntervenant(), $typeVolumeHoraire);
            }
        }

        return $result;
    }



    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param EtapeEntity       $etape
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByEtape(EtapeEntity $etape, QueryBuilder $qb = null, $alias = null)
    {
        $serviceElement = $this->getServiceElementPedagogique();

        list($qb, $alias) = $this->initQuery($qb, $alias);
        $this->leftJoin($serviceElement, $qb, 'elementPedagogique');
        $serviceElement->finderByEtape($etape, $qb);

        return $qb;
    }



    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param EtapeEntity       $etape
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByNiveauEtape(NiveauEtapeEntity $niveauEtape, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($niveauEtape && $niveauEtape->getId() !== '-') {
            $serviceElement = $this->getServiceElementPedagogique();
            $serviceEtape   = $this->getServiceEtape();

            $this->leftJoin($serviceElement, $qb, 'elementPedagogique');
            $serviceElement->join($serviceEtape, $qb, 'etape');
            $serviceEtape->finderByNiveau($niveauEtape, $qb);
        }

        return $qb;
    }



    /**
     *
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param QueryBuilder            $qb
     * @param string                  $alias
     *
     * @return QueryBuilder
     */
    public function finderByTypeVolumeHoraire(TypeVolumeHoraireEntity $typeVolumeHoraire, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($typeVolumeHoraire) {
            $serviceVolumeHoraire = $this->getServiceVolumeHoraire();

            $this->join($serviceVolumeHoraire, $qb, 'volumeHoraire');
            $serviceVolumeHoraire->finderByTypeVolumeHoraire($typeVolumeHoraire, $qb);
        }

        return $qb;
    }



    /**
     *
     * @param EtatVolumeHoraireEntity $etatVolumeHoraire
     * @param QueryBuilder            $qb
     * @param string                  $alias
     *
     * @return QueryBuilder
     */
    public function finderByEtatVolumeHoraire(EtatVolumeHoraireEntity $etatVolumeHoraire, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($etatVolumeHoraire) {
            $serviceVolumeHoraire = $this->getServiceVolumeHoraire();

            $this->join($serviceVolumeHoraire, $qb, 'volumeHoraire');
            $serviceVolumeHoraire->finderByEtatVolumeHoraire($etatVolumeHoraire, $qb);
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
     * @param StructureEntity   $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByComposante(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $serviceStructure          = $this->getServiceStructure();
        $serviceIntervenant        = $this->getServiceIntervenant();
        $serviceElementPedagogique = $this->getServiceElementPedagogique();
        $serviceStatutIntervenant  = $this->getServiceStatutIntervenant();
        $iAlias                    = $serviceIntervenant->getAlias();
        $sAlias                    = $serviceStatutIntervenant->getAlias();

        $this->join($serviceIntervenant, $qb, 'intervenant', false, $alias);
        $serviceIntervenant->join($serviceStatutIntervenant, $qb, 'statut', false);
        $this->leftJoin($serviceElementPedagogique, $qb, 'elementPedagogique', false, $alias);
        $serviceElementPedagogique->leftJoin($serviceStructure, $qb, 'structure', false, null, 's_ens');

        $filter = "(($sAlias.typeIntervenant = :typeIntervenantPermanent AND $iAlias.structure = :composante) OR s_ens = :composante)";
        $qb->andWhere($filter)->setParameter('composante', $structure);
        $qb->setParameter('typeIntervenantPermanent', $this->getServiceTypeIntervenant()->getPermanent());

        return $qb;
    }



    /**
     *
     * @param StructureEntity   $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStructureAff(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $serviceIntervenant = $this->getServiceIntervenant();

        $this->join($serviceIntervenant, $qb, 'intervenant', false, $alias);
        $serviceIntervenant->finderByStructure($structure, $qb);
        $serviceIntervenant->finderByType($this->getServiceTypeIntervenant()->getPermanent(), $qb);

        return $qb;
    }



    /**
     * Filtre la liste des services selon lecontexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join($this->getServiceIntervenant(), $qb, 'intervenant', false, $alias);
        $this->getServiceIntervenant()->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        if ($intervenant = $role->getIntervenant()) { // Si c'est un intervenant
            $this->finderByIntervenant($intervenant, $qb, $alias);
        }

        return $qb;
    }



    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param TypeIntervenantEntity $typeIntervenant
     * @param QueryBuilder|null     $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByTypeIntervenant(TypeIntervenantEntity $typeIntervenant = null, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($typeIntervenant) {
            $this->join($this->getServiceIntervenant(), $qb, 'intervenant', false, $alias);
            $this->getServiceIntervenant()->finderByType($typeIntervenant, $qb);
        }

        return $qb;
    }



    /**
     * Retourne la liste des services dont les volumes horaires sont validés ou non.
     *
     * @param boolean|\Application\Entity\Db\Validation $validation <code>true</code>, <code>false</code> ou
     *                                                              bien une Validation précise
     * @param QueryBuilder|null                         $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByValidation($validation, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addSelect('vhv')
            ->join("$alias.volumeHoraire", 'vhv');

        if ($validation instanceof \Application\Entity\Db\Validation) {
            $qb
                ->join("vhv.validation", "v")
                ->andWhere("v = :validation")->setParameter('validation', $validation);
        } else {
            $value = $validation ? 'is not null' : 'is null';
            $qb->leftJoin("vhv.validation", 'vv')
                ->andWhere("vv $value");
        }

        return $qb;
    }



    /**
     * Recherche par type
     *
     * @param TypeValidation|string $type
     * @param QueryBuilder|null     $qb
     *
     * @return QueryBuilder
     */
    public function finderByTypeValidation($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $type = $this->getServiceValidation()->normalizeTypeValidation($type);

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
     * @param QueryBuilder|null                $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStructureValidation(\Application\Entity\Db\Structure $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addSelect("vhs, vs")
            ->join("$alias.volumeHoraire", 'vhs')
            ->join("vhs.validation", "vs")
            ->andWhere("vs.structure = :structurev")->setParameter('structurev', $structure);

        return $qb;
    }



    /**
     * Retourne la liste des services dont les volumes horaires ont fait ou non l'objet d'un contrat/avenant.
     *
     * @param boolean|\Application\Entity\Db\Contrat $contrat <code>true</code>, <code>false</code> ou
     *                                                        bien un Contrat précis
     * @param QueryBuilder|null                      $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByContrat($contrat, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addSelect("vhc")
            ->join("$alias.volumeHoraire", 'vhc');

        if ($contrat instanceof \Application\Entity\Db\Contrat) {
            $qb->addSelect("c")
                ->join("vhc.contrat", "c")
                ->andWhere("c = :contrat")->setParameter('contrat', $contrat);
        } else {
            $value = $contrat ? 'is not null' : 'is null';
            $qb->andWhere("vhc.contrat $value");
        }

        return $qb;
    }



    /**
     * Recherche des services (et volumes horaires) validables.
     *
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param IntervenantEntity       $intervenant
     * @param                         StructureEntity [array|null $structureEns
     *
     * @return array
     */
    public function fetchServicesDisposPourValidation(
        TypeVolumeHoraireEntity $typeVolumeHoraire,
        IntervenantEntity $intervenant,
        $structureEns = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select("s2, i, vh, tvh, ep, strens")
            ->from("Application\Entity\Db\Service", 's2')
            ->join("s2.intervenant", "i", Join::WITH, "s2.intervenant = :intervenant")
            ->join("s2.volumeHoraire", 'vh')
            ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
            ->leftJoin("s2.elementPedagogique", "ep")
            ->leftJoin("ep.structure", 'strens')
            ->addOrderBy("strens.libelleCourt", 'asc')
            ->addOrderBy("s2.histoModification", 'asc')
            ->setParameter('intervenant', $intervenant)
            ->setParameter('tvh', $typeVolumeHoraire);

        /**
         * Les volumes horaires du type spécifié ne doivent pas être validés.
         */
        $qb
            ->leftJoin("vh.validation", "val")
            ->andWhere("val.id IS NULL");

        /**
         * Filtrage éventuel par composante d'intervention.
         */
        if (null !== $structureEns) {
            $structureEns = (array)$structureEns;
            $whereStr     = [];
            if (array_key_exists(ServiceEntity::HORS_ETABLISSEMENT, $structureEns)) {
                $whereStr[] = "ep.structure IS NULL";
            }
            $structureEns = array_filter($structureEns);
            foreach ($structureEns as $s) {
                $paramName  = uniqid("str");
                $whereStr[] = "strens = :" . $paramName;
                $qb->setParameter($paramName, $s);
            }
            if ($whereStr) {
                $qb->andWhere(implode(' OR ', $whereStr));
            }
        }

        return $qb->getQuery()->getResult();
    }



    /**
     *
     * @param TypeVolumeHoraireEntity    $typeVolumeHoraire
     * @param TypeValidationEntity       $validation
     * @param IntervenantEntity          $intervenant
     * @param StructureEntity|array|null $structureEns
     * @param StructureEntity            $structureValidation
     *
     * @return QueryBuilder
     */
    public function finderServicesValides(
        TypeVolumeHoraireEntity $typeVolumeHoraire,
        ValidationEntity $validation = null,
        IntervenantEntity $intervenant = null,
        $structureEns = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select("s, i, vh, ep, strens")
            ->from("Application\Entity\Db\Service", 's')
            ->join("s.intervenant", "i")
            ->join("s.volumeHoraire", 'vh')
            ->leftJoin("s.elementPedagogique", 'ep')
            ->leftJoin("ep.structure", 'strens')
            ->join("vh.validation", "v")
            ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh.code = :ctvh")->setParameter('ctvh', $typeVolumeHoraire->getCode())
            ->join("v.typeValidation", 'tv')
            ->join("v.structure", 'str')// validés par la structure spécifiée
            ->orderBy("v.histoModification", 'desc')
            ->addOrderBy("strens.libelleCourt", 'asc');

        if ($validation) {
            $qb->andWhere("v = :validation")->setParameter('validation', $validation);
        }
        if ($intervenant) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $intervenant);
        }
        if (null !== $structureEns) {
            $structureEns = (array)$structureEns;
            $whereStr     = [];
            if (array_key_exists(ServiceEntity::HORS_ETABLISSEMENT, $structureEns)) {
                $whereStr[] = "ep.structure IS NULL";
            }
            $structureEns = array_filter($structureEns);
            foreach ($structureEns as $s) {
                $paramName  = uniqid("str");
                $whereStr[] = "strens = :" . $paramName;
                $qb->setParameter($paramName, $s);
            }
            if ($whereStr) {
                $qb->andWhere(implode(' OR ', $whereStr));
            }
        }

        return $qb;
    }



    /**
     * Prend les services d'un intervenant, année n-1, et reporte ces services (et les volumes horaires associés)
     * sur l'année n
     *
     * @param IntervenantEntity $intervenant
     *
     */
    public function setPrevusFromPrevus(IntervenantEntity $intervenant, $plafondControl = true)
    {
        $old               = $this->getPrevusFromPrevusData($intervenant);
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();

        // Enregistrement des services trouvés dans la nouvelle année
        foreach ($old as $o) {
            $service = $o['service'];
            if (!$service) {
                $service = $this->newEntity();
                $service->setIntervenant($intervenant);
                $service->setElementPedagogique($o['element-pedagogique']);
                $service->setEtablissement($o['etablissement']);
            }
            foreach ($o['heures'] as $heures) {
                $volumeHoraire = $this->getServiceVolumeHoraire()->newEntity();
                //@formatter:off
                $volumeHoraire->setTypeVolumeHoraire( $heures['type-volume-horaire'] );
                $volumeHoraire->setPeriode          ( $heures['periode']             );
                $volumeHoraire->setTypeIntervention ( $heures['type-intervention']   );
                $volumeHoraire->setHeures           ( $heures['heures']              );
                //@formatter:on
                $volumeHoraire->setService($service);
                $service->addVolumeHoraire($volumeHoraire);
            }
            $service->setHistoDestructeur(null); // restauration du service si besoin!!
            $service->setHistoDestruction(null);
            $this->save($service, false);
        }
        if ($plafondControl) {
            $this->controlePlafondFcMaj($intervenant, $typeVolumeHoraire);
        }
    }



    public function getPrevusFromPrevusData(IntervenantEntity $intervenant)
    {
        $tvhPrevu  = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $evhValide = $this->getServiceEtatVolumeHoraire()->getValide();

        $intervenantPrec = $this->getServiceIntervenant()->getBySourceCode(
            $intervenant->getSourceCode(),
            $this->getServiceContext()->getAnneePrecedente()
        );

        $sVolumeHoraire = $this->getServiceVolumeHoraire();

        $qb = $this->select(['id', 'elementPedagogique', 'etablissement']);
        //@formatter:off
        $this->join(    'applicationEtablissement',                     $qb, 'etablissement',           true);//['id', 'sourceCode']);
        $this->leftJoin('applicationElementPedagogique',                $qb, 'elementPedagogique',      true);//['id', 'sourceCode']);
        $this->leftJoin('applicationFormuleService',                    $qb, 'formuleService',          true);//['id']);
        $this->leftJoin($sVolumeHoraire,                                $qb, 'volumeHoraire',           true);//['id', 'periode', 'typeIntervention', 'heures']);
        $sVolumeHoraire->leftJoin('applicationFormuleVolumeHoraire',    $qb, 'formuleVolumeHoraire',    true);//['id']);
        $sVolumeHoraire->leftJoin('applicationPeriode',                 $qb, 'periode',                 true);//['id']);
        $sVolumeHoraire->leftJoin('applicationTypeIntervention',        $qb, 'typeIntervention',        true);//['id']);
        //@formatter:on

        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenantPrec, $qb);
        $sVolumeHoraire->finderByHistorique($qb);
        $sVolumeHoraire->finderByTypeVolumeHoraire($tvhPrevu, $qb);
        $sVolumeHoraire->finderByEtatVolumeHoraire($evhValide, $qb);

        $s = $this->getList($qb);

        $old = [];
        foreach ($s as $service) {

            /* @var $service \Application\Entity\Db\Service */
            $oldElement = $service->getElementPedagogique();
            $newElement = $oldElement ? $this->getServiceElementPedagogique()->getBySourceCode(
                $oldElement->getSourceCode(),
                $this->getServiceContext()->getAnnee()
            ) : null;

            if ($newElement && !$newElement->estNonHistorise()) {
                $newElement = null; // s'il n'est pas actif alors on ne reporte pas
            }

            $newPeriode = $newElement ? $newElement->getPeriode() : null;

            if (empty($oldElement) || !empty($newElement)) {
                $o  = [
                    'element-pedagogique' => $newElement,
                    'etablissement'       => $service->getEtablissement(),
                    'heures'              => [],
                ];
                $id = $newElement ? $newElement->getSourceCode() : '';
                $id .= '-';
                $id .= $service->getEtablissement()->getSourceCode();

                $vhl = $service->getVolumeHoraireListe();

                $periodes          = $vhl->getPeriodes();
                $typesIntervention = $vhl->getTypesIntervention();
                foreach ($periodes as $periode) {
                    /* @var $periode \Application\Entity\Db\Periode */
                    if (empty($newPeriode) || $periode === $newPeriode) { // pas de mauvaise période!!!
                        foreach ($typesIntervention as $typeIntervention) {
                            /* @var $typeIntervention TypeIntervention */
                            $heures = $vhl->setPeriode($periode)->setTypeIntervention($typeIntervention)->getHeures();
                            if ($heures > 0) {
                                $o['heures'][] = [
                                    'periode'             => $periode,
                                    'type-intervention'   => $typeIntervention,
                                    'type-volume-horaire' => $tvhPrevu,
                                    'heures'              => $heures,
                                ];
                            }
                        }
                    }
                }

                if (!empty($o['heures'])) {
                    $newService = $this->getBy($intervenant, $newElement, $service->getEtablissement());
                    if ($newService && $newService->estNonHistorise()) {
                        $newHeures = $newService->getVolumeHoraireListe()->setTypeVolumeHoraire($tvhPrevu)->getHeures();
                    } else {
                        $newHeures = 0;
                    }
                    if ($newHeures == 0) { // on n'insère pas le service si des heures ont déjà été saisies!!
                        $o['service'] = $newService;
                        $old[$id]     = $o;
                    }
                }
            }
        }

        return $old;
    }



    public function setRealisesFromPrevus(ServiceEntity $service, $plafondControl = true)
    {
        $prevus = $service
            ->getVolumeHoraireListe()->getChild()
            ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
            ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getValide());

        $realises = $service
            ->getVolumeHoraireListe()->getChild()
            ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getRealise())
            ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());

        $typesIntervention = $prevus->getTypesIntervention() + $realises->getTypesIntervention();
        $periodes          = $prevus->getPeriodes() + $realises->getPeriodes();

        foreach ($periodes as $periode) {
            $prevus->setPeriode($periode);
            $realises->setPeriode($periode);
            foreach ($typesIntervention as $typeIntervention) {
                $prevus->setTypeIntervention($typeIntervention);
                $realises->setTypeIntervention($typeIntervention);

                $realises->setHeures($prevus->getHeures());
            }
        }
        $this->save($service, $plafondControl);
    }



    /**
     * Retourne les données du TBL des services en fonction des critères de recherche transmis
     *
     * @param Recherche $recherche
     *
     * @return array
     */
    public function getTableauBord(Recherche $recherche, array $options = [])
    {
        // initialisation
        $defaultOptions    = [
            'tri'              => 'intervenant',   // [intervenant, hetd]
            'columns'          => [],              // Liste des colonnes utiles, hors colonnes liées aux types d'intervention!!
            'ignored-columns'  => [],              // Liste des colonnes à ne pas récupérer, hors colonnes liées aux types d'intervention!!
            'isoler-non-payes' => true,            // boolean
            'regroupement'     => 'service',       // [service, intervenant]
            'composante'       => null,            // Composante qui en fait la demande
        ];
        $options           = array_merge($defaultOptions, $options);
        $annee             = $this->getServiceContext()->getAnnee();
        $data              = [];
        $shown             = [];
        $typesIntervention = [];
        $invertTi          = [];
        $numericColunms    = [
            'service-statutaire',
            'service-du-modifie',
            'heures-non-payees',
            'heures-ref',
            'service-fi',
            'service-fa',
            'service-fc',
            'heures-compl-fi',
            'heures-compl-fa',
            'heures-compl-fc',
            'heures-compl-fc-majorees',
            'heures-compl-referentiel',
            'total',
            'solde',
        ];
        $addableColumns    = [
            '__total__',
            'heures-non-payees',
            'heures-ref',
            'service-fi',
            'service-fa',
            'service-fc',
            'heures-compl-fi',
            'heures-compl-fa',
            'heures-compl-fc',
            'heures-compl-fc-majorees',
            'heures-compl-referentiel',
            'total',
        ];

        // requêtage
        $conditions = [
            'annee_id = ' . $annee->getId(),
        ];
        if ($c1 = $recherche->getTypeVolumeHoraire()) $conditions['type_volume_horaire_id'] = '(type_volume_horaire_id = -1 OR type_volume_horaire_id = ' . $c1->getId() . ')';
        if ($c2 = $recherche->getEtatVolumeHoraire()) $conditions['etat_volume_horaire_id'] = '(etat_volume_horaire_id = -1 OR etat_volume_horaire_id = ' . $c2->getId() . ')';
        if ($c3 = $recherche->getTypeIntervenant()) $conditions['type_intervenant_id'] = '(type_intervenant_id = -1 OR type_intervenant_id = ' . $c3->getId() . ')';
        if ($c4 = $recherche->getIntervenant()) $conditions['intervenant_id'] = '(intervenant_id = -1 OR intervenant_id = ' . $c4->getId() . ')';
        //if ($c5 = $recherche->getNiveauFormation()    ) $conditions['niveau_formation_id']    = '(niveau_formation_id = -1 OR niveau_formation_id = '    . $c5->getId().')';
        if ($c6 = $recherche->getEtape()) $conditions['etape_id'] = '(etape_id = -1 OR etape_id = ' . $c6->getId() . ')';
        if ($c7 = $recherche->getElementPedagogique()) $conditions['element_pedagogique_id'] = '(element_pedagogique_id = -1 OR element_pedagogique_id = ' . $c7->getId() . ')';
        if ($c8 = $recherche->getStructureAff()) $conditions['structure_aff_id'] = '(structure_aff_id = -1 OR structure_aff_id = ' . $c8->getId() . ')';
        if ($c9 = $recherche->getStructureEns()) $conditions['structure_ens_id'] = '(structure_ens_id = -1 OR structure_ens_id = ' . $c9->getId() . ')';

        if ($options['composante'] instanceof StructureEntity) {
            $id                       = (int)$options['composante']->getId();
            $conditions['composante'] = "(structure_aff_id = -1 OR structure_aff_id = $id OR structure_ens_id = -1 OR structure_ens_id = $id)";
        }

        switch ($options['tri']) {
            case 'intervenant':
                $orderBy = 'INTERVENANT_NOM, SERVICE_STRUCTURE_AFF_LIBELLE, SERVICE_STRUCTURE_ENS_LIBELLE, ETAPE_LIBELLE, ELEMENT_LIBELLE';
            break;
            case 'hetd':
                $orderBy = 'SOLDE, INTERVENANT_NOM, SERVICE_STRUCTURE_AFF_LIBELLE, SERVICE_STRUCTURE_ENS_LIBELLE, ETAPE_LIBELLE, ELEMENT_LIBELLE';
            break;
            default:
                $orderBy = 'INTERVENANT_NOM, SERVICE_STRUCTURE_AFF_LIBELLE, SERVICE_STRUCTURE_ENS_LIBELLE, ETAPE_LIBELLE, ELEMENT_LIBELLE';
            break;
        }

        $sql  = 'SELECT * FROM V_TBL_SERVICE WHERE ' . implode(' AND ', $conditions) . ' '
            . 'ORDER BY ' . $orderBy;
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        $dateExtraction = new \DateTime();

        // récupération des données
        while ($d = $stmt->fetch()) {

            if ($options['isoler-non-payes'] && (int)$d['HEURES_NON_PAYEES'] === 1) {
                $d['HEURES_NON_PAYEES'] = $d['HEURES'];
                $d['HEURES']            = 0;
            }

            if ('intervenant' === $options['regroupement']) {
                $sid = $d['INTERVENANT_ID'];
            } else {
                $sid = $d['SERVICE_ID'] ? $d['SERVICE_ID'] . '_' . $d['PERIODE_ID'] : $d['ID'];
            }
            $ds = [
                '__total__'     => (float)$d['HEURES'] + (float)$d['HEURES_NON_PAYEES'] + (float)$d['HEURES_REF'] + (float)$d['TOTAL'],
                'type-etat'     => $d['TYPE_ETAT'],
                'date'          => $dateExtraction,
                'annee-libelle' => (string)$annee,

                'intervenant-code'               => $d['INTERVENANT_CODE'],
                'intervenant-nom'                => $d['INTERVENANT_NOM'],
                'intervenant-date-naissance'     => new \DateTime($d['INTERVENANT_DATE_NAISSANCE']),
                'intervenant-statut-libelle'     => $d['INTERVENANT_STATUT_LIBELLE'],
                'intervenant-type-code'          => $d['INTERVENANT_TYPE_CODE'],
                'intervenant-type-libelle'       => $d['INTERVENANT_TYPE_LIBELLE'],
                'intervenant-grade-code'         => $d['INTERVENANT_GRADE_CODE'],
                'intervenant-grade-libelle'      => $d['INTERVENANT_GRADE_LIBELLE'],
                'intervenant-discipline-code'    => $d['INTERVENANT_DISCIPLINE_CODE'],
                'intervenant-discipline-libelle' => $d['INTERVENANT_DISCIPLINE_LIBELLE'],
                'heures-service-statutaire'      => (float)$d['SERVICE_STATUTAIRE'],
                'heures-service-du-modifie'      => (float)$d['SERVICE_DU_MODIFIE'],
                'service-structure-aff-libelle'  => $d['SERVICE_STRUCTURE_AFF_LIBELLE'],

                'service-structure-ens-libelle' => $d['SERVICE_STRUCTURE_ENS_LIBELLE'],
                'groupe-type-formation-libelle' => $d['GROUPE_TYPE_FORMATION_LIBELLE'],
                'type-formation-libelle'        => $d['TYPE_FORMATION_LIBELLE'],
                'etape-niveau'                  => empty($d['ETAPE_NIVEAU']) ? null : (int)$d['ETAPE_NIVEAU'],
                'etape-code'                    => $d['ETAPE_CODE'],
                'etape-etablissement-libelle'   => $d['ETAPE_LIBELLE'] ? $d['ETAPE_LIBELLE'] : $d['ETABLISSEMENT_LIBELLE'],
                'element-code'                  => $d['ELEMENT_CODE'],
                'element-fonction-libelle'      => $d['ELEMENT_LIBELLE'] ? $d['ELEMENT_LIBELLE'] : $d['FONCTION_REFERENTIEL_LIBELLE'],
                'element-discipline-code'       => $d['ELEMENT_DISCIPLINE_CODE'],
                'element-discipline-libelle'    => $d['ELEMENT_DISCIPLINE_LIBELLE'],
                'element-taux-fi'               => (float)$d['ELEMENT_TAUX_FI'],
                'element-taux-fc'               => (float)$d['ELEMENT_TAUX_FC'],
                'element-taux-fa'               => (float)$d['ELEMENT_TAUX_FA'],
                'commentaires'                  => $d['COMMENTAIRES'],
                'element-ponderation-compl'     => $d['ELEMENT_PONDERATION_COMPL'] === null ? null : (float)$d['ELEMENT_PONDERATION_COMPL'],
                'element-source-libelle'        => $d['ELEMENT_SOURCE_LIBELLE'],

                'periode-libelle'              => $d['PERIODE_LIBELLE'],
                'heures-non-payees'            => (float)$d['HEURES_NON_PAYEES'],
                // types d'intervention traités en aval
                'heures-ref'                   => (float)$d['HEURES_REF'],
                'service-fi'                   => (float)$d['SERVICE_FI'],
                'service-fa'                   => (float)$d['SERVICE_FA'],
                'service-fc'                   => (float)$d['SERVICE_FC'],
                'service-referentiel'          => (float)$d['SERVICE_REFERENTIEL'],
                'heures-compl-fi'              => (float)$d['HEURES_COMPL_FI'],
                'heures-compl-fa'              => (float)$d['HEURES_COMPL_FA'],
                'heures-compl-fc'              => (float)$d['HEURES_COMPL_FC'],
                'heures-compl-fc-majorees'     => (float)$d['HEURES_COMPL_FC_MAJOREES'],
                'heures-compl-referentiel'     => (float)$d['HEURES_COMPL_REFERENTIEL'],
                'total'                        => (float)$d['TOTAL'],
                'solde'                        => (float)$d['SOLDE'],
                'date-cloture-service-realise' => empty($d['DATE_CLOTURE_REALISE']) ? null : \DateTime::createFromFormat('Y-m-d', substr($d['DATE_CLOTURE_REALISE'], 0, 10)),
            ];

            if (
                $ds['heures-service-statutaire'] > 0
                && $ds['heures-service-statutaire'] + $ds['heures-service-du-modifie'] == 0
                && empty($ds['etape-code'])
            ) {
                $ds['__total__']++; // pour que le cas spécifique des décharges totales soit pris en compte
            }

            if ($d['TYPE_INTERVENTION_ID'] != null) {
                $tid = $d['TYPE_INTERVENTION_ID'];
                if (!isset($typesIntervention[$tid])) {
                    $typesIntervention[$tid] = $this->getServiceTypeIntervention()->get($tid);
                }
                $typeIntervention                                              = $typesIntervention[$tid];
                $invertTi['type-intervention-' . $typeIntervention->getCode()] = $typeIntervention->getId();
                $ds['type-intervention-' . $typeIntervention->getCode()]       = (float)$d['HEURES'];
            }
            foreach ($ds as $column => $value) {
                if (empty($options['columns']) || in_array($column, $options['columns']) || 0 === strpos($column, 'type-intervention-')) {
                    if (!isset($shown[$column])) $shown[$column] = 0;
                    if (is_float($value)) {
                        $shown[$column] += $value;
                    } else {
                        $shown[$column] += empty($value) ? 0 : 1;
                    }
                }
                if (in_array($column, $options['ignored-columns'])) {
                    $shown[$column] = 0;
                }
            }
            if (!isset($data[$sid])) {
                $data[$sid] = $ds;
            } else {
                foreach ($ds as $column => $value) {
                    if (empty($options['columns']) || in_array($column, $options['columns']) || 0 === strpos($column, 'type-intervention-')) {
                        if (in_array($column, $addableColumns) || 0 === strpos($column, 'type-intervention-')) {
                            if (!isset($data[$sid][$column])) {
                                $data[$sid][$column] = $value;
                            } // pour les types d'intervention no initialisés
                            else $data[$sid][$column] += $value;
                        } elseif ($value != $data[$sid][$column]) {
                            $data[$sid][$column] = null;
                        }
                    }
                }
            }
        }

        // tri et préparation des entêtes
        $head = [
            'type-etat'     => 'Type État',
            'date'          => 'Date d\'extraction',
            'annee-libelle' => 'Année universitaire',

            'intervenant-code'               => 'Code intervenant',
            'intervenant-nom'                => 'Intervenant',
            'intervenant-date-naissance'     => 'Date de naissance',
            'intervenant-statut-libelle'     => 'Statut intervenant',
            'intervenant-type-code'          => 'Type d\'intervenant (Code)',
            'intervenant-type-libelle'       => 'Type d\'intervenant',
            'intervenant-grade-code'         => 'Grade (Code)',
            'intervenant-grade-libelle'      => 'Grade',
            'intervenant-discipline-code'    => 'Discipline intervenant (Code)',
            'intervenant-discipline-libelle' => 'Discipline intervenant',
            'heures-service-statutaire'      => 'Service statutaire',
            'heures-service-du-modifie'      => 'Modification de service du',
            'service-structure-aff-libelle'  => 'Structure d\'affectation',

            'service-structure-ens-libelle' => 'Structure d\'enseignement',
            'groupe-type-formation-libelle' => 'Groupe de type de formation',
            'type-formation-libelle'        => 'Type de formation',
            'etape-niveau'                  => 'Niveau',
            'etape-code'                    => 'Code formation',
            'etape-etablissement-libelle'   => 'Formation ou établissement',
            'element-code'                  => 'Code enseignement',
            'element-fonction-libelle'      => 'Enseignement ou fonction référentielle',
            'element-discipline-code'       => 'Discipline ens. (Code)',
            'element-discipline-libelle'    => 'Discipline ens.',
            'element-taux-fi'               => 'Taux FI',
            'element-taux-fc'               => 'Taux FC',
            'element-taux-fa'               => 'Taux FA',
            'commentaires'                  => 'Commentaires',
            'element-ponderation-compl'     => 'Majoration',
            'element-source-libelle'        => 'Source enseignement',
            'periode-libelle'               => 'Période',
            'heures-non-payees'             => 'Heures non payées',
        ];
        uasort($typesIntervention, function ($ti1, $ti2) {
            return $ti1->getOrdre() > $ti2->getOrdre();
        });
        foreach ($typesIntervention as $typeIntervention) {
            /* @var $typeIntervention \Application\Entity\Db\TypeIntervention */
            $head['type-intervention-' . $typeIntervention->getCode()] = $typeIntervention->getCode();
        }
        $head['heures-ref']                   = 'Référentiel';
        $head['service-fi']                   = 'HETD Service FI';
        $head['service-fa']                   = 'HETD Service FA';
        $head['service-fc']                   = 'HETD Service FC';
        $head['service-referentiel']          = 'HETD Service Référentiel';
        $head['heures-compl-fi']              = 'HETD Compl. FI';
        $head['heures-compl-fa']              = 'HETD Compl. FA';
        $head['heures-compl-fc']              = 'HETD Compl. FC';
        $head['heures-compl-fc-majorees']     = 'HETD Compl. FC D714-60';
        $head['heures-compl-referentiel']     = 'HETD Compl. référentiel';
        $head['total']                        = 'Total HETD';
        $head['solde']                        = 'Solde HETD';
        $head['date-cloture-service-realise'] = 'Clôture du service réalisé';

        // suppression des informations superflues
        foreach ($shown as $column => $visibility) {
            if (isset($head[$column]) && empty($visibility)) {
                unset($head[$column]);
                if (isset($invertTi[$column])) {
                    unset($typesIntervention[$invertTi[$column]]);
                }
            }
        }
        $columns = array_keys($head);
        foreach ($data as $sid => $d) {
            if (0 == $d['__total__']) {
                unset($data[$sid]); // pas d'affichage pour quelqu'un qui n'a rien
            } else {
                $data[$sid] = [];
                foreach ($columns as $column) {
                    $value = isset($d[$column]) ? $d[$column] : null;
                    if (null === $value && (in_array($column, $numericColunms) || 0 === strpos($column, 'type-intervention-'))) {
                        $value = 0;
                    }
                    $data[$sid][$column] = $value;
                }
            }
        }

        return [
            'head'               => $head,
            'data'               => $data,
            'types-intervention' => $typesIntervention,
        ];
    }



    /**
     * Détermine si un service est assuré localement (c'est-à-dire dans l'université) ou sur un autre établissement
     *
     * @param \Application\Entity\Db\Service $service
     *
     * @return boolean
     */
    public function isLocal(ServiceEntity $service)
    {
        if (!$service->getEtablissement()) return true; // par défaut
        if ($service->getEtablissement() === $this->getServiceContext()->getEtablissement()) return true;

        return false;
    }



    /**
     * Retourne la période courante d'un service
     *
     * @param \Application\Entity\Db\Service $service
     *
     * @return \Application\Entity\Db\Periode
     */
    public function getPeriode(ServiceEntity $service)
    {
        if (!$this->isLocal($service)) return null;
        if (!$service->getElementPedagogique()) return null;
        if (!$service->getElementPedagogique()->getPeriode()) return null;

        return $service->getElementPedagogique()->getPeriode();
    }



    /**
     *
     * @param \Application\Entity\Db\Service $service
     *
     * @return \Application\Entity\Db\Periode[]
     */
    public function getPeriodes(ServiceEntity $service)
    {
        $p = $this->getPeriode($service);
        if (null === $p) {
            // Pas de période donc toutes les périodes sont autorisées
            return $this->getServicePeriode()->getEnseignement();
        } else {
            return [$p->getId() => $p];
        }
    }



    /**
     *
     * @param ServiceEntity|ServiceEntity[] $services
     *
     * @return TypeInterventionEntity[]
     */
    public function getTypesIntervention($services)
    {
        if ($services instanceof ServiceEntity) $services = [$services];
        $typesIntervention = [];
        foreach ($services as $service) {
            if (!$service instanceof ServiceEntity) {
                throw new \LogicException('Seules des entités Service doivent être passées en paramètre');
            }
            if ($ep = $service->getElementPedagogique()) {
                foreach ($ep->getTypeIntervention() as $typeIntervention) {
                    $typesIntervention[$typeIntervention->getId()] = $typeIntervention;
                }
            }
        }
        usort($typesIntervention, function ($ti1, $ti2) {
            return $ti1->getOrdre() > $ti2->getOrdre();
        });

        return $typesIntervention;
    }



    public function canHaveMotifNonPaiement(ServiceEntity $service, $runEx = false)
    {
        if (!$service->getIntervenant()->estPermanent()) {
            return $this->cannotDoThat("Un intervenant vacataire ne peut pas avoir de motif de non paiement", $runEx);
        }
        if ($this->getServiceContext()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole) {
            return $this->cannotDoThat("Les intervenants n'ont pas le droit de visualiser ou modifier les motifs de non paiement", $runEx);
        }

        return true;
    }



    /**
     * Retourne le total HETD des enseignements (prévisionnels et validés) d'un intervenant.
     *
     * @prama IntervenantEntity $intervenant
     * @return float
     */
    public function getTotalHetdIntervenant(IntervenantEntity $intervenant)
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();

        $fr = $intervenant->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return $fr->getServiceDu() + $fr->getSolde();
    }



    /**
     * Détermine si on peut ajouter un nouveau service ou non
     *
     * @param \Application\Entity\Db\Intervenant $intervenant Eventuel intervenant concerné
     *
     * @deprecated
     * @return boolean
     */
    public function canAdd($intervenant = null, $runEx = false)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        if (!$intervenant) {
            return $this->cannotDoThat("Anomalie : aucun intervenant spécifié.", $runEx);
        } else {
            if ($intervenant->getStatut()->getSourceCode() == \Application\Entity\Db\StatutIntervenant::NON_AUTORISE) {
                return $this->cannotDoThat("Votre statut ne vous autorise pas à assurer des enseignements");
            }
        }

        $rulesEvaluator = new \Application\Rule\Service\SaisieServiceRulesEvaluator($intervenant);
        if (!$rulesEvaluator->execute()) {
            $message = "?";
            if ($role instanceof \Application\Acl\IntervenantRole) {
                $message = "Vous ne pouvez pas saisir de service. ";
            } elseif ($role instanceof \Application\Acl\ComposanteRole) {
                $message = "Vous ne pouvez pas saisir de service pour $intervenant. ";
            }

            return $this->cannotDoThat($message . $rulesEvaluator->getMessage(), $runEx);
        }

        return true;
    }



    /**
     * Contrôle du plafond des heures FC (D714-60) après saisie.
     *
     * @param IntervenantEntity       $intervenant
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     *
     * @return $this
     */
    public function controlePlafondFcMaj(IntervenantEntity $intervenant, TypeVolumeHoraireEntity $typeVolumeHoraire)
    {
        $intervenantId = (int)$intervenant->getId();
        $tvhId         = (int)$typeVolumeHoraire->getId();

        $sql = "BEGIN ose_service.controle_plafond_fc_maj($intervenantId,$tvhId); END;";
        $this->getEntityManager()->getConnection()->exec($sql);

        return $this;
    }



    /**
     *
     * @param ServiceEntity[]         $services
     * @param TypeVolumeHoraireEntity $typeVolumehoraire
     */
    public function setTypeVolumehoraire($services, TypeVolumeHoraireEntity $typeVolumeHoraire)
    {
        foreach ($services as $service) {
            $service->setTypeVolumeHoraire($typeVolumeHoraire);
        }
    }
}
