<?php

namespace Application\Service;

use Application\Entity\Db\FonctionReferentiel as FonctionReferentielEntity;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\FonctionReferentielAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\ServiceReferentiel as ServiceReferentielEntity;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;


/**
 * Description of ServiceReferentiel
 *
 * @method ServiceReferentielEntity get($id)
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentiel extends AbstractEntityService
{
    use IntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;
    use FonctionReferentielAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use VolumeHoraireReferentielAwareTrait;



    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return ServiceReferentielEntity::class;
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
     * @param QueryBuilder|null $qb    Générateur de requêtes
     * @param string|null       $alias Alias d'entité
     *
     * @return array
     */
    public function initQuery(QueryBuilder $qb = null, $alias = null, array $fields = [])
    {
        list($qb, $alias) = parent::initQuery($qb, $alias, $fields);

        $this
            ->join($this->getServiceStructure(), $qb, 'structure', true, $alias)
            ->join($this->getServiceFonctionReferentiel(), $qb, 'fonction', true, $alias)
            ->join($this->getServiceIntervenant(), $qb, 'intervenant', true, $alias);

        return [$qb, $alias];
    }



    /**
     * Retourne la liste des services selon le contexte donné
     *
     * @param QueryBuilder|null $queryBuilder
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
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param QueryBuilder      $qb
     * @param string            $alias
     *
     * @return QueryBuilder
     */
    public function finderByTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($typeVolumeHoraire) {
            $this->join($this->getServiceVolumeHoraireReferentiel(), $qb, 'volumeHoraireReferentiel');
            $this->getServiceVolumeHoraireReferentiel()->finderByTypeVolumeHoraire($typeVolumeHoraire, $qb);
        }

        return $qb;
    }



    /**
     * Retourne un service unique selon ses critères précis
     *
     * @param Intervenant               $intervenant
     * @param FonctionReferentielEntity $fonction
     * @param Structure                 $structure
     * @param string                    $commentaires
     *
     * @return null|\Application\Entity\Db\ServiceReferentiel
     */
    public function getBy(
        Intervenant $intervenant,
        FonctionReferentielEntity $fonction,
        Structure $structure,
        $commentaires = null
    )
    {
        $result = $this->getRepo()->findBy([
            'intervenant' => $intervenant,
            'fonction'    => $fonction,
            'structure'   => $structure,
        ]);

        /* Retourne le premier NON historisé */
        foreach ($result as $sr) {
            /* @var $sr \Application\Entity\Db\ServiceReferentiel */
            if ($sr->estNonHistorise() && $sr->getCommentaires() == $commentaires) return $sr;
        }

        /* Sinon retourne le premier trouvé */
        foreach ($result as $sr) {
            /* @var $sr \Application\Entity\Db\ServiceReferentiel */
            if ($sr->getCommentaires() == $commentaires) return $sr;
        }

        /* Sinon ne retourne rien */

        return null;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
            ->addOrderBy($this->getServiceIntervenant()->getAlias() . '.nomUsuel')
            ->addOrderBy($this->getServiceStructure()->getAlias() . '.libelleCourt')
            ->addOrderBy($this->getServiceFonctionReferentiel()->getAlias() . '.libelleCourt');

        return $qb;
    }



    /**
     *
     * @param ServiceReferentielEntity[] $servicesReferentiels
     * @param TypeVolumeHoraire          $typeVolumeHoraire
     */
    public function setTypeVolumeHoraire($servicesReferentiels, TypeVolumeHoraire $typeVolumeHoraire)
    {
        foreach ($servicesReferentiels as $serviceReferentiel) {
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
        $role   = $this->getServiceContext()->getSelectedIdentityRole();
        if ($intervenant = $role->getIntervenant()) {
            $entity->setIntervenant($intervenant);
        }

        return $entity;
    }



    /**
     * Sauvegarde une entité
     *
     * @param ServiceReferentielEntity $entity
     *
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->getEntityManager()->getConnection()->beginTransaction();
        try {
            if (!$entity->getIntervenant() && $intervenant = $role->getIntervenant()) {
                $entity->setIntervenant($intervenant);
            }
            if (!$this->getAuthorize()->isAllowed($entity, Privileges::REFERENTIEL_EDITION)) {
                throw new \BjyAuthorize\Exception\UnAuthorizedException('Saisie interdite');
            }

            $serviceAllreadyExists = null;
            if (!$entity->getId()) { // uniquement pour les nouveaux services!!
                $serviceAllreadyExists = $this->getBy(
                    $entity->getIntervenant(),
                    $entity->getFonction(),
                    $entity->getStructure(),
                    $entity->getCommentaires()
                );
            }
            if ($serviceAllreadyExists) {
                $result = $serviceAllreadyExists;
            } else {
                $result = parent::save($entity);
            }

            /* Sauvegarde automatique des volumes horaires associés */
            $serviceVolumeHoraire = $this->getServiceVolumeHoraireReferentiel();

            foreach ($entity->getVolumeHoraireReferentiel() as $volumeHoraire) {
                if ($result !== $entity) $volumeHoraire->setServiceReferentiel($result);
                if ($volumeHoraire->getRemove()) {
                    $serviceVolumeHoraire->delete($volumeHoraire);
                } else {
                    $serviceVolumeHoraire->save($volumeHoraire);
                }
            }
            $this->getEntityManager()->getConnection()->commit();
        } catch (Exception $e) {
            $this->getEntityManager()->getConnection()->rollBack();
            throw $e;
        }

        return $result;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param \Application\Entity\Db\ServiceReferentiel $entity Entité à détruire
     * @param bool                                      $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if ($softDelete) {
            $vhListe = $entity->getVolumeHoraireReferentielListe();
            $vhListe->setHeures(0); // aucune heure (SI une heure est validée alors un nouveau VHR sera créé!!
        }

        $vhrl = $entity->getVolumeHoraireReferentiel();

        $delete = true;
        foreach ($vhrl as $volumeHoraire) {
            if ($volumeHoraire->getRemove() || !$volumeHoraire->estNonHistorise()) {
                $this->getServiceVolumeHoraireReferentiel()->delete($volumeHoraire, $softDelete);
                $vhrl->removeElement($volumeHoraire);
            } elseif ($volumeHoraire->getId()) {
                $delete = false;
                $this->getServiceVolumeHoraireReferentiel()->save($volumeHoraire);
            }
        }

        if ($delete) {
            parent::delete($entity, $softDelete);
        }

        return $this;
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
        $old = $this->getPrevusFromPrevusData($intervenant);

        // Enregistrement des services trouvés dans la nouvelle année
        foreach ($old as $o) {
            $service = $o['service'];
            if (!$service) {
                $service = $this->newEntity();
                $service->setIntervenant($intervenant);
                $service->setFonction($o['fonction']);
                $service->setStructure($o['structure']);
                $service->setCommentaires($o['commentaires']);
                $service->setTypeVolumeHoraire($o['type-volume-horaire']);
            }
            $volumeHoraire = $this->getServiceVolumeHoraireReferentiel()->newEntity();
            //@formatter:off
            $volumeHoraire->setTypeVolumeHoraire( $o['type-volume-horaire'] );
            $volumeHoraire->setHeures           ( $o['heures']              );
            //@formatter:on
            $volumeHoraire->setServiceReferentiel($service);
            $service->addVolumeHoraireReferentiel($volumeHoraire);

            $service->setHistoDestructeur(null); // restauration du service si besoin!!
            $service->setHistoDestruction(null);
            $this->save($service);
        }
    }



    public function getPrevusFromPrevusData(Intervenant $intervenant)
    {
        $tvhPrevu  = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $evhValide = $this->getServiceEtatVolumeHoraire()->getSaisi();

        $intervenantPrec = $this->getServiceIntervenant()->getBySourceCode(
            $intervenant->getSourceCode(),
            $this->getServiceContext()->getAnneePrecedente(),
            false
        );

        $sVolumeHoraireReferentiel = $this->getServiceVolumeHoraireReferentiel();

        $qb = $this->select(['id', 'fonction', 'structure', 'commentaires']);
        //@formatter:off
        $this->join('applicationFonctionReferentiel',   $qb, 'fonctionReferentiel',     true);
        $this->Join(StructureService::class,             $qb, 'structure',               true);
        $this->Join($sVolumeHoraireReferentiel,         $qb, 'volumeHoraireReferentiel',true);
        //@formatter:on

        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenantPrec, $qb);
        $this->getServiceFonctionReferentiel()->finderByHistorique($qb); // pour éviter que des fonctions devenues historiques ne soient reconduites
        $this->getServiceStructure()->finderByHistorique($qb); // idem pour les structures anciennes!!
        $sVolumeHoraireReferentiel->finderByHistorique($qb);
        $sVolumeHoraireReferentiel->finderByTypeVolumeHoraire($tvhPrevu, $qb);
        $sVolumeHoraireReferentiel->finderByEtatVolumeHoraire($evhValide, $qb);

        $s = $this->getList($qb);

        $old = [];
        foreach ($s as $service) {

            /* @var $service \Application\Entity\Db\ServiceReferentiel */

            $ok = $service->getFonction()->estNonHistorise()
                && $service->getStructure()->estNonHistorise();

            if ($ok) {
                $o = [
                    'type-volume-horaire' => $tvhPrevu,
                    'fonction'            => $service->getFonction(),
                    'structure'           => $service->getStructure(),
                    'commentaires'        => $service->getCommentaires(),
                    'heures'              => $service->getVolumeHoraireReferentielListe()->getHeures(),
                    'service'             => $this->getBy(
                        $intervenant,
                        $service->getFonction(),
                        $service->getStructure()
                    ),
                ];

                $newService = $o['service'];
                /* @var $newService \Application\Entity\Db\ServiceReferentiel */

                // pour ne pas écraser les serices précédemment saisis avec des heures
                if (
                !(
                    $newService
                    && $newService->estNonHistorise()
                    && $newService->getVolumeHoraireReferentielListe()->getHeures() > 0
                )
                ) {
                    $old[] = $o;
                }
            }
        }

        return $old;
    }



    public function setRealisesFromPrevus(ServiceReferentielEntity $service)
    {
        $prevus = $service
            ->getVolumeHoraireReferentielListe()->getChild()
            ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
            ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getValide());

        $realises = $service
            ->getVolumeHoraireReferentielListe()->getChild()
            ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getRealise())
            ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());

        $realises->setHeures($prevus->getHeures());
        $this->save($service);
    }
}