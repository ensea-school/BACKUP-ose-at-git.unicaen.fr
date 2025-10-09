<?php

namespace Enseignement\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Periode;
use Application\Service\AbstractEntityService;
use Application\Service\PeriodeService;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\VolumeHoraireListe;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Lieu\Entity\Db\Etablissement;
use Lieu\Entity\Db\Structure;
use Lieu\Service\EtablissementService;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Entity\NiveauEtape;
use OffreFormation\Service\ElementPedagogiqueService;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use OffreFormation\Service\TypeInterventionService;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;

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
    use ValidationServiceAwareTrait;
    use StatutServiceAwareTrait;
    use SourceServiceAwareTrait;
    use ParametresServiceAwareTrait;


    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Service::class;
    }



    /**
     * Retourne la liste des services selon l'étape donnée
     *
     * @param Etape             $etape
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByEtape(Etape $etape, ?QueryBuilder $qb = null, $alias = null)
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
    public function finderByNiveauEtape(NiveauEtape $niveauEtape, ?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        if ($niveauEtape && $niveauEtape->getId() !== '|') {
            $serviceElement = $this->getServiceElementPedagogique();
            $serviceEtape   = $this->getServiceEtape();

            $this->leftJoin($serviceElement, $qb, 'elementPedagogique');
            $serviceElement->join($serviceEtape, $qb, 'etape');
            $serviceEtape->finderByNiveau($niveauEtape, $qb);
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
    public function finderByComposante(Structure $structure, ?QueryBuilder $qb = null, $alias = null)
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
        $serviceIntervenant->leftJoin($serviceStructure, $qb, 'structure', false, null, 'i_ens');
        $this->leftJoin($serviceElementPedagogique, $qb, 'elementPedagogique', false, $alias);
        $serviceElementPedagogique->leftJoin($serviceStructure, $qb, 'structure', false, null, 's_ens');

        $filter = "(($sAlias.typeIntervenant = :typeIntervenantPermanent AND i_ens.ids LIKE :composante) OR s_ens.ids LIKE :composante)";
        $qb->andWhere($filter)->setParameter('composante', $structure->idsFilter());
        $qb->setParameter('typeIntervenantPermanent', $this->getServiceTypeIntervenant()->getPermanent());

        return $qb;
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
     * Utile pour la recherche de services
     *
     * @param Structure         $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStructureAff(Structure $structure, ?QueryBuilder $qb = null, $alias = null)
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
    public function finderByStructureEns(Structure $structure, ?QueryBuilder $qb = null, $alias = null)
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
    public function finderByContext(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($this->getServiceIntervenant(), $qb, 'intervenant', false, $alias);
        $this->getServiceIntervenant()->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        if ($intervenant = $this->getServiceContext()->getIntervenant()) { // Si c'est un intervenant
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
    public function finderByTypeIntervenant(?TypeIntervenant $typeIntervenant = null, ?QueryBuilder $qb = null, $alias = null)
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
                $volumeHoraire->setTypeVolumeHoraire($typeVolumeHoraire);
                $volumeHoraire->setPeriode($heures['periode']);
                $volumeHoraire->setTypeIntervention($heures['type-intervention']);
                $volumeHoraire->setHeures($heures['heures']);
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

        $sVolumeHoraire      = $this->getServiceVolumeHoraire();
        $sElementPedagogique = $this->getServiceElementPedagogique();

        $qb = $this->select(['id', 'elementPedagogique', 'etablissement']);
        //@formatter:off
        $this->join(EtablissementService::class, $qb, 'etablissement', true);//['id', 'sourceCode']);
        $this->leftJoin(ElementPedagogiqueService::class, $qb, 'elementPedagogique', true);//['id', 'sourceCode']);
        $this->leftJoin($sVolumeHoraire, $qb, 'volumeHoraire', true);//['id', 'periode', 'typeIntervention', 'heures']);
        $sVolumeHoraire->leftJoin(PeriodeService::class, $qb, 'periode', true);//['id']);
        $sVolumeHoraire->leftJoin(TypeInterventionService::class, $qb, 'typeIntervention', true);//['id']);
        //@formatter:on

        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenantPrec, $qb);
        $sVolumeHoraire->finderByHistorique($qb);
        $sVolumeHoraire->finderByTypeVolumeHoraire($tvhSource, $qb);
        $sVolumeHoraire->finderByEtatVolumeHoraire($evhValide, $qb);

        if ($structure = $this->getServiceContext()->getStructure()) {
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



    /**
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param QueryBuilder      $qb
     * @param string            $alias
     *
     * @return QueryBuilder
     */
    public function finderByTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire, ?QueryBuilder $qb = null, $alias = null)
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
    public function finderByEtatVolumeHoraire(EtatVolumeHoraire $etatVolumeHoraire, ?QueryBuilder $qb = null, $alias = null)
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
     * Retourne un service unique selon ses critères précis
     *
     * @param Intervenant        $intervenant
     * @param ElementPedagogique $elementPedagogique
     * @param Etablissement      $etablissement
     *
     * @return null|Service
     */
    public function getBy(
        Intervenant   $intervenant,
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
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return Service
     */
    public function newEntity(): Service
    {
        /** @var Service $entity */
        $entity = parent::newEntity();

        if ($intervenant = $this->getServiceContext()->getIntervenant()) {
            $entity->setIntervenant($intervenant);
        }
        $entity->setEtablissement($this->getServiceContext()->getEtablissement());

        return $entity;
    }



    /**
     * Sauvegarde une entité
     *
     * @param Service $entity
     *
     * @return Service
     */
    public function save($entity)
    {
        if (!$entity->getEtablissement()) {
            $entity->setEtablissement($this->getServiceContext()->getEtablissement());
            if (!$entity->getEtablissement()) {
                throw new \LogicException('L\'établissement n\'est pas renseigné dans les paramétrages généraux de OSE');
            }
        }
        if (!$entity->getIntervenant() && $intervenant = $this->getServiceContext()->getIntervenant()) {
            $entity->setIntervenant($intervenant);
        }
        if (!$this->getAuthorize()->isAllowed($entity, $entity->getTypeVolumeHoraire()->getPrivilegeEnseignementEdition())) {
            throw new \Unicaen\Framework\Authorize\UnAuthorizedException('Saisie interdite');
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



    public function setRealisesFromPrevus(Service $service)
    {
        $rStructure = $this->getServiceContext()->getStructure();
        $sStructure = $service->getElementPedagogique() ? $service->getElementPedagogique()->getStructure() : null;

        if ($rStructure && $sStructure && $rStructure != $sStructure) {
            $intervenant = $service->getIntervenant();
            if (!($intervenant->getStatut()->estPermanent() && $intervenant->getStructure() == $rStructure)) {
                return; // on ne reporte pas de service si l'utilisateur est d'une composante différente de celle du service
            }
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
            VolumeHoraireListe::FILTRE_TAG,
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
     * Retourne le total HETD des enseignements (réalisés et validés) d'un intervenant.
     */
    public function getTotalHetdIntervenant(Intervenant $intervenant)
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();

        $fr = $intervenant->getFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return $fr->getTotal();
    }
}
