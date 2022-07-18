<?php

namespace Enseignement\Service;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Etape;
use Application\Service\AbstractEntityService;
use Service\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Intervenant;
use Enseignement\Entity\Db\Service;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervention;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Entity\NiveauEtape;
use Service\Entity\Recherche;
use Enseignement\Entity\VolumeHoraireListe;
use Enseignement\Hydrator\RechercheHydrator;
use Enseignement\Hydrator\RechercheHydratorAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Laminas\Session\Container as SessionContainer;

/**
 * Description of Service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Service get($id)
 * @method Service[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 *
 */
class ServiceService extends AbstractEntityService
{
    use ElementPedagogiqueServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use RechercheHydratorAwareTrait;
    use ValidationServiceAwareTrait;
    use StatutServiceAwareTrait;
    use SourceServiceAwareTrait;
    use ParametresServiceAwareTrait;

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
        return Service::class;
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
     * @return Service
     */
    public function newEntity()
    {
        /** @var Service $entity */
        $entity = parent::newEntity();

        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($intervenant = $role->getIntervenant()) {
            $entity->setIntervenant($intervenant);
        }
        $entity->setEtablissement($this->getServiceContext()->getEtablissement());

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
     * @param Intervenant        $intervenant
     * @param ElementPedagogique $elementPedagogique
     * @param Etablissement      $etablissement
     *
     * @return null|Service
     */
    public function getBy(
        Intervenant $intervenant,
        $elementPedagogique,
        Etablissement $etablissement
    )
    {
        $result = $this->getRepo()->findBy([
            'intervenant'        => $intervenant,
            'elementPedagogique' => $elementPedagogique,
            'etablissement'      => $etablissement,
        ]);

        if (count($result) > 1) {
            foreach ($result as $sr) {
                /* @var $sr \Enseignement\Entity\Db\Service */
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
     * @param Service $entity
     *
     * @return Service
     * @throws Exception
     */
    public function save($entity)
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $role = $this->getServiceContext()->getSelectedIdentityRole();

            if (!$entity->getEtablissement()) {
                $entity->setEtablissement($this->getServiceContext()->getEtablissement());
                if (!$entity->getEtablissement()) {
                    throw new \LogicException('L\'établissement n\'est pas renseigné dans les paramétrages généraux de OSE');
                }
            }
            if (!$entity->getIntervenant() && $intervenant = $role->getIntervenant()) {
                $entity->setIntervenant($intervenant);
            }
            if (!$this->getAuthorize()->isAllowed($entity, $entity->getTypeVolumeHoraire()->getPrivilegeEnseignementEdition())) {
                throw new \BjyAuthorize\Exception\UnAuthorizedException('Saisie interdite');
            }

            $serviceAllreadyExists = null;
            if (!$entity->getId()) { // uniquement pour les nouveaux services!!
                $serviceAllreadyExists = $this->getBy(
                    $entity->getIntervenant(),
                    $entity->getElementPedagogique(),
                    $entity->getEtablissement()
                );
            }
            if ($serviceAllreadyExists) {
                $result = $serviceAllreadyExists;
            } else {
                if ($entity->hasChanged()) {
                    $sourceOse = $this->getServiceSource()->getOse();
                    if (!$entity->getSource()) {
                        $entity->setSource($sourceOse);
                    }
                    if (!$entity->getSourceCode()) {
                        $entity->setSourceCode(uniqid('ose-'));
                    }
                    foreach ($entity->getVolumeHoraire() as $vh) {
                        if (!$vh->getSource()) {
                            $vh->setSource($sourceOse);
                        }
                        if (!$vh->getSourceCode()) {
                            $vh->setSourceCode(uniqid('ose-'));
                        }
                    }

                    $result = parent::save($entity);
                    $entity->setChanged(false);
                } else {
                    $result = $entity;
                }
            }

            /* Sauvegarde automatique des volumes horaires associés */
            $serviceVolumeHoraire = $this->getServiceVolumeHoraire();
            foreach ($entity->getVolumeHoraire() as $volumeHoraire) {
                /* @var $volumeHoraire VolumeHoraire */
                if ($result !== $entity) $volumeHoraire->setService($result);
                if ($volumeHoraire->getRemove()) {
                    if ($volumeHoraire->getId()) {
                        $serviceVolumeHoraire->delete($volumeHoraire);
                    } else {
                        $entity->removeVolumeHoraire($volumeHoraire);
                    }
                } else {
                    $serviceVolumeHoraire->save($volumeHoraire, false); // pas de contrôle de plafond sur le VH ! ! !
                }
            }
            $this->getEntityManager()->commit();
        } catch (Exception $e) {
            $this->getEntityManager()->rollBack();
            throw $e;
        }

        return $result;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param Service $entity Entité à détruire
     * @param bool    $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if ($softDelete) {
            $vhListe = $entity->getVolumeHoraireListe();
            $listes  = $vhListe->getSousListes([$vhListe::FILTRE_PERIODE, $vhListe::FILTRE_TYPE_INTERVENTION, $vhListe::FILTRE_HORAIRE_DEBUT, $vhListe::FILTRE_HORAIRE_FIN]);
            foreach ($listes as $liste) {
                $liste->setHeures(0);
            }
        }

        $vhl = $entity->getVolumeHoraire();

        $delete = true;
        /** @var VolumeHoraireService $volumeHoraire */
        foreach ($vhl as $volumeHoraire) {
            if ($volumeHoraire->getRemove() || !$volumeHoraire->estNonHistorise()) {
                $this->getServiceVolumeHoraire()->delete($volumeHoraire, $softDelete);
                $vhl->removeElement($volumeHoraire);
            } else {
                $delete = false;
                $this->getServiceVolumeHoraire()->save($volumeHoraire);
            }
        }

        if ($delete) {
            parent::delete($entity, $softDelete);
        }

        return $this;
    }



    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param Etape             $etape
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByEtape(Etape $etape, QueryBuilder $qb = null, $alias = null)
    {
        $serviceElement = $this->getServiceElementPedagogique();

        [$qb, $alias] = $this->initQuery($qb, $alias);
        $this->leftJoin($serviceElement, $qb, 'elementPedagogique');
        $serviceElement->finderByEtape($etape, $qb);

        return $qb;
    }



    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param Etape             $etape
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByNiveauEtape(NiveauEtape $niveauEtape, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
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
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param QueryBuilder      $qb
     * @param string            $alias
     *
     * @return QueryBuilder
     */
    public function finderByTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        if ($typeVolumeHoraire) {
            $serviceVolumeHoraire = $this->getServiceVolumeHoraire();

            $this->join($serviceVolumeHoraire, $qb, 'volumeHoraire');
            $serviceVolumeHoraire->finderByTypeVolumeHoraire($typeVolumeHoraire, $qb);
        }

        return $qb;
    }



    /**
     *
     * @param EtatVolumeHoraire $etatVolumeHoraire
     * @param QueryBuilder      $qb
     * @param string            $alias
     *
     * @return QueryBuilder
     */
    public function finderByEtatVolumeHoraire(EtatVolumeHoraire $etatVolumeHoraire, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
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
     * @param Structure         $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByComposante(Structure $structure, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $serviceStructure          = $this->getServiceStructure();
        $serviceIntervenant        = $this->getServiceIntervenant();
        $serviceElementPedagogique = $this->getServiceElementPedagogique();
        $serviceStatut             = $this->getServiceStatut();
        $iAlias                    = $serviceIntervenant->getAlias();
        $sAlias                    = $serviceStatut->getAlias();

        $this->join($serviceIntervenant, $qb, 'intervenant', false, $alias);
        $serviceIntervenant->join($serviceStatut, $qb, 'statut', false);
        $this->leftJoin($serviceElementPedagogique, $qb, 'elementPedagogique', false, $alias);
        $serviceElementPedagogique->leftJoin($serviceStructure, $qb, 'structure', false, null, 's_ens');

        $filter = "(($sAlias.typeIntervenant = :typeIntervenantPermanent AND $iAlias.structure = :composante) OR s_ens = :composante)";
        $qb->andWhere($filter)->setParameter('composante', $structure);
        $qb->setParameter('typeIntervenantPermanent', $this->getServiceTypeIntervenant()->getPermanent());

        return $qb;
    }



    /**
     * Utile pour la recherche de services
     *
     * @param Structure         $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStructureAff(Structure $structure, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $serviceIntervenant = $this->getServiceIntervenant();

        $this->join($serviceIntervenant, $qb, 'intervenant', false, $alias);
        $serviceIntervenant->finderByStructure($structure, $qb);
        $serviceIntervenant->finderByType($this->getServiceTypeIntervenant()->getPermanent(), $qb);

        return $qb;
    }



    /**
     * Utile pour la recherche de services
     *
     * @param Structure         $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStructureEns(Structure $structure, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $serviceElementPedagogique = $this->getServiceElementPedagogique();
        $this->join($serviceElementPedagogique, $qb, 'elementPedagogique', false, $alias);
        $serviceElementPedagogique->finderByStructure($structure, $qb);

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

        [$qb, $alias] = $this->initQuery($qb, $alias);

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
     * @param TypeIntervenant   $typeIntervenant
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByTypeIntervenant(TypeIntervenant $typeIntervenant = null, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        if ($typeIntervenant) {
            $this->join($this->getServiceIntervenant(), $qb, 'intervenant', false, $alias);
            $this->getServiceIntervenant()->finderByType($typeIntervenant, $qb);
        }

        return $qb;
    }



    /**
     * Prend les services d'un intervenant, année n-1, et reporte ces services (et les volumes horaires associés)
     * sur l'année n
     *
     * @param Intervenant $intervenant
     *
     */
    public function setPrevusFromPrevus(Intervenant $intervenant)
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
                $volumeHoraire->setTypeVolumeHoraire( $typeVolumeHoraire );
                $volumeHoraire->setPeriode          ( $heures['periode']             );
                $volumeHoraire->setTypeIntervention ( $heures['type-intervention']   );
                $volumeHoraire->setHeures           ( $heures['heures']              );
                //@formatter:on
                $volumeHoraire->setService($service);
                $service->addVolumeHoraire($volumeHoraire);
            }
            $service->setHistoDestructeur(null); // restauration du service si besoin!!
            $service->setHistoDestruction(null);
            $service->setTypeVolumeHoraire($typeVolumeHoraire);
            $service->setChanged(true);
            try {
                $this->save($service, false);
            } catch (\Exception $e) {

            }
        }
    }



    public function getPrevusFromPrevusData(Intervenant $intervenant)
    {
        $tvhPrevu  = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $tvhSource = $this->getServiceTypeVolumeHoraire()->getByCode($this->getServiceParametres()->get('report_service'));
        $evhValide = $this->getServiceEtatVolumeHoraire()->getValide();

        $intervenantPrec = $this->getServiceIntervenant()->getPrecedent($intervenant);

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $sVolumeHoraire      = $this->getServiceVolumeHoraire();
        $sElementPedagogique = $this->getServiceElementPedagogique();

        $qb = $this->select(['id', 'elementPedagogique', 'etablissement']);
        //@formatter:off
        $this->join(    EtablissementService::class,                     $qb, 'etablissement',           true);//['id', 'sourceCode']);
        $this->leftJoin(ElementPedagogiqueService::class,                $qb, 'elementPedagogique',      true);//['id', 'sourceCode']);
        $this->leftJoin($sVolumeHoraire,                                $qb, 'volumeHoraire',           true);//['id', 'periode', 'typeIntervention', 'heures']);
        $sVolumeHoraire->leftJoin(PeriodeService::class,                 $qb, 'periode',                 true);//['id']);
        $sVolumeHoraire->leftJoin(TypeInterventionService::class,        $qb, 'typeIntervention',        true);//['id']);
        //@formatter:on

        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenantPrec, $qb);
        $sVolumeHoraire->finderByHistorique($qb);
        $sVolumeHoraire->finderByTypeVolumeHoraire($tvhSource, $qb);
        $sVolumeHoraire->finderByEtatVolumeHoraire($evhValide, $qb);

        if ($structure = $role->getStructure()) {
            $sElementPedagogique->finderByStructure($structure, $qb);
        }

        $s = $this->getList($qb);

        $old = [];
        foreach ($s as $service) {

            /* @var $service Service */
            $service->setTypeVolumeHoraire($tvhSource);
            $oldElement = $service->getElementPedagogique();
            $newElement = $oldElement ? $this->getServiceElementPedagogique()->getByCode(
                $oldElement->getCode(),
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
                            /* @var $typeIntervention TypeInterventionService */
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
                    if ($newService) {
                        $newService->setTypeVolumeHoraire($tvhPrevu);
                    }
                    if ($newService && $newService->estNonHistorise()) {
                        $newHeures = $newService->getVolumeHoraireListe()->getHeures();
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



    public function setRealisesFromPrevus(Service $service)
    {
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $rStructure = $role ? $role->getStructure() : null;
        $sStructure = $service->getElementPedagogique() ? $service->getElementPedagogique()->getStructure() : null;

        if ($rStructure && $sStructure && $rStructure != $sStructure) {
            return; // on ne reporte pas de service si l'utilisateur est d'une composante différente de celle du service
        }

        $prevus = $service
            ->getVolumeHoraireListe()->createChild()
            ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
            ->setValidation(true);

        $realises = $service
            ->getVolumeHoraireListe()->createChild()
            ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getRealise())
            ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());

        $filtres = [
            VolumeHoraireListe::FILTRE_PERIODE,
            VolumeHoraireListe::FILTRE_TYPE_INTERVENTION,
            VolumeHoraireListe::FILTRE_MOTIF_NON_PAIEMENT,
            VolumeHoraireListe::FILTRE_HORAIRE_DEBUT,
            VolumeHoraireListe::FILTRE_HORAIRE_FIN,
        ];

        $listes     = [];
        $prevListes = $prevus->getSousListes($filtres);
        foreach ($prevListes as $id => $prevListe) {
            $listes[$id]['prev'] = $prevListe;
        }
        $realListes = $realises->getSousListes($filtres);
        foreach ($realListes as $id => $realListe) {
            $listes[$id]['real'] = $realListe;
        }

        foreach ($listes as $liste) {
            if (isset($liste['prev'])) {
                $heures = $liste['prev']->getHeures();
            } else {
                $heures = 0;
            }

            if (!isset($liste['real'])) {
                $liste['real'] = $realises->createChild()->filterByVolumeHoraireListe($liste['prev']);
            }

            $liste['real']->setHeures($heures);
        }

        $this->save($service);
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
            'service-referentiel',
            'heures-compl-fi',
            'heures-compl-fa',
            'heures-compl-fc',
            'heures-compl-fc-majorees',
            'heures-compl-referentiel',
            'total',
            'solde',
        ];
        $dateColumns       = [
            'service-date-modification',
            'intervenant-date-naissance',
            'date-cloture-service-realise',
        ];
        $addableColumns    = [
            '__total__',
            'heures-non-payees',
            'heures-ref',
            'service-fi',
            'service-fa',
            'service-fc',
            'service-referentiel',
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
        if ($c8 = $recherche->getStructureAff()) $conditions['structure_aff_id'] = '(structure_aff_id IS NULL OR structure_aff_id = -1 OR structure_aff_id = ' . $c8->getId() . ')';
        if ($c9 = $recherche->getStructureEns()) $conditions['structure_ens_id'] = '(structure_ens_id = -1 OR structure_ens_id = ' . $c9->getId() . ')';

        if ($options['composante'] instanceof Structure) {
            $id                       = (int)$options['composante']->getId();
            $conditions['composante'] = "(structure_aff_id IS NULL OR structure_aff_id = -1 OR structure_aff_id = $id OR structure_ens_id = -1 OR structure_ens_id = $id)";
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

        $sql  = 'SELECT * FROM V_EXPORT_SERVICE WHERE ' . implode(' AND ', $conditions) . ' '
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
                '__total__'                 => (float)$d['HEURES'] + (float)$d['HEURES_NON_PAYEES'] + (float)$d['HEURES_REF'] + (float)$d['TOTAL'],
                'type-etat'                 => $d['TYPE_ETAT'],
                'date'                      => $dateExtraction,
                'service-date-modification' => $d['SERVICE_DATE_MODIFICATION'],
                'annee-libelle'             => (string)$annee,

                'intervenant-code'               => $d['INTERVENANT_CODE'],
                'intervenant-id'                 => $d['INTERVENANT_ID'],
                'intervenant-nom'                => $d['INTERVENANT_NOM'],
                'intervenant-date-naissance'     => $d['INTERVENANT_DATE_NAISSANCE'],
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
                'etape-etablissement-libelle'   => $d['ETAPE_LIBELLE'] ? $d['ETAPE_LIBELLE'] : ($d['SERVICE_REF_FORMATION'] ? $d['SERVICE_REF_FORMATION'] : $d['ETABLISSEMENT_LIBELLE']),
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
                'total'                        => (float)$d['HEURES_COMPL_FI'] + (float)$d['HEURES_COMPL_FA'] + (float)$d['HEURES_COMPL_FC'] + (float)$d['HEURES_COMPL_FC_MAJOREES'] + (float)$d['HEURES_COMPL_REFERENTIEL'],
                'solde'                        => (float)$d['SOLDE'],
                'date-cloture-service-realise' => $d['DATE_CLOTURE_REALISE'],
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
            'type-etat'                 => 'Type État',
            'date'                      => 'Date d\'extraction',
            'annee-libelle'             => 'Année universitaire',
            'service-date-modification' => 'Date de modif. du service',

            'intervenant-code'               => 'Code intervenant',
            'intervenant-id'                 => 'Id intervenant',
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
            return $ti1->getOrdre() - $ti2->getOrdre();
        });
        foreach ($typesIntervention as $typeIntervention) {
            /* @var $typeIntervention TypeIntervention */
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

                    if (in_array($column, $dateColumns)) {
                        if (empty($value)) $value = null; else $value = \DateTime::createFromFormat('Y-m-d', substr($value, 0, 10));
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
     * @param Service $service
     *
     * @return boolean
     */
    protected function isLocal(Service $service)
    {
        if (!$service->getEtablissement()) return true; // par défaut
        if ($service->getEtablissement() === $this->getServiceContext()->getEtablissement()) return true;

        return false;
    }



    /**
     * Retourne la période courante d'un service
     *
     * @param Service $service
     *
     * @return Periode
     */
    public function getPeriode(Service $service)
    {
        if (!$this->isLocal($service)) return null;
        if (!$service->getElementPedagogique()) return null;

        return $service->getElementPedagogique()->getPeriode();
    }



    /**
     *
     * @param Service $service
     *
     * @return Periode[]
     */
    public function getPeriodes(Service $service)
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
     * @param Service|Service[] $services
     *
     * @return TypeIntervention[]
     */
    public function getTypesIntervention($services)
    {
        if ($services instanceof Service) $services = [$services];
        $typesIntervention = [];
        foreach ($services as $service) {
            if (!$service instanceof Service) {
                throw new \LogicException('Seules des entités Service doivent être passées en paramètre');
            }
            if ($ep = $service->getElementPedagogique()) {
                foreach ($ep->getTypeIntervention() as $typeIntervention) {
                    $typesIntervention[$typeIntervention->getId()] = $typeIntervention;
                }
            }
        }
        usort($typesIntervention, function ($ti1, $ti2) {
            return $ti1->getOrdre() - $ti2->getOrdre();
        });

        return $typesIntervention;
    }



    /**
     * Retourne le total HETD des enseignements (réalisés et validés) d'un intervenant.
     *
     * @prama Intervenant $intervenant
     * @return float
     */
    public function getTotalHetdIntervenant(Intervenant $intervenant)
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();

        $fr = $intervenant->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return $fr->getServiceDu() + $fr->getSolde();
    }



    /**
     *
     * @param Service[]         $services
     * @param TypeVolumeHoraire $typeVolumehoraire
     */
    public function setTypeVolumehoraire($services, TypeVolumeHoraire $typeVolumeHoraire)
    {
        foreach ($services as $service) {
            $service->setTypeVolumeHoraire($typeVolumeHoraire);
        }
    }
}
