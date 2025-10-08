<?php

namespace Referentiel\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureService;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\Db\MotifNonPaiement;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Entity\Db\ServiceReferentiel;
use Service\Entity\Db\Tag;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TagServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of ServiceReferentiel
 *
 * @method ServiceReferentiel get($id)
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielService extends AbstractEntityService
{
    use IntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;
    use TagServiceAwareTrait;
    use FonctionReferentielServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use VolumeHoraireReferentielServiceAwareTrait;
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
        return ServiceReferentiel::class;
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
    public function initQuery(?QueryBuilder $qb = null, $alias = null, array $fields = []): array
    {
        [$qb, $alias] = parent::initQuery($qb, $alias, $fields);

        $this
            ->join($this->getServiceStructure(), $qb, 'structure', true, $alias)
            ->join($this->getServiceFonctionReferentiel(), $qb, 'fonctionReferentiel', true, $alias)
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
            $this->join($this->getServiceVolumeHoraireReferentiel(), $qb, 'volumeHoraireReferentiel');
            $this->getServiceVolumeHoraireReferentiel()->finderByTypeVolumeHoraire($typeVolumeHoraire, $qb);
        }

        return $qb;
    }



    /**
     * Retourne un service unique selon ses critères précis
     *
     * @param Intervenant         $intervenant
     * @param FonctionReferentiel $fonction
     * @param Structure           $structure
     * @param string              $commentaires
     *
     * @return null|ServiceReferentiel
     */
    public function getBy(
        Intervenant         $intervenant,
        FonctionReferentiel $fonction,
        Structure           $structure,
        ?Tag                $tag,
        ?MotifNonPaiement   $motifNonPaiement,
                            $commentaires = null
    )
    {
        $result = $this->getRepo()->findBy([
                                               'intervenant'         => $intervenant,
                                               'fonctionReferentiel' => $fonction,
                                               'structure'           => $structure,
                                               'tag'                 => $tag,
                                               'motifNonPaiement'    => $motifNonPaiement,
                                           ]);

        /* Retourne le premier NON historisé */
        foreach ($result as $sr) {
            /* @var $sr ServiceReferentiel */
            if ($sr->estNonHistorise() && $sr->getCommentaires() == $commentaires) return $sr;
        }

        /* Sinon retourne le premier trouvé */
        foreach ($result as $sr) {
            /* @var $sr ServiceReferentiel */
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
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb
            ->addOrderBy($this->getServiceIntervenant()->getAlias() . '.nomUsuel')
            ->addOrderBy($this->getServiceStructure()->getAlias() . '.libelleCourt')
            ->addOrderBy($this->getServiceFonctionReferentiel()->getAlias() . '.libelleCourt');

        return $qb;
    }



    /**
     *
     * @param ServiceReferentiel[] $servicesReferentiels
     * @param TypeVolumeHoraire    $typeVolumeHoraire
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
     * @return ServiceReferentiel
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        if ($intervenant = $this->getServiceContext()->getIntervenant()) {
            $entity->setIntervenant($intervenant);
        }

        $entity->setSource($this->getServiceSource()->getOse());
        $entity->setSourceCode(uniqid('ose-'));

        return $entity;
    }



    /**
     * Sauvegarde une entité
     *
     * @param ServiceReferentiel $entity
     *
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        if (!$entity->getIntervenant() && $intervenant = $this->getServiceContext()->getIntervenant()) {
            $entity->setIntervenant($intervenant);
        }
        if (!$this->getAuthorize()->isAllowed($entity, $entity->getTypeVolumeHoraire()->getPrivilegeReferentielEdition())) {
            throw new \Framework\Authorize\UnAuthorizedException('Saisie interdite');
        }

        $serviceAllreadyExists = null;
        if (!$entity->getId()) { // uniquement pour les nouveaux services!!
            $serviceAllreadyExists = $this->getBy(
                $entity->getIntervenant(),
                $entity->getFonctionReferentiel(),
                $entity->getStructure(),
                $entity->getTag(),
                $entity->getMotifNonPaiement(),
                $entity->getCommentaires()
            );
        }

        // Enregistrement en BDD
        if ($serviceAllreadyExists) {
            // on déplace les nouveaux volumes horaires sur l'ancien
            foreach ($entity->getVolumeHoraireReferentiel() as $vhr) {
                $vhr->setServiceReferentiel($serviceAllreadyExists);
                $this->getEntityManager()->persist($vhr);
            }
            // l'ancien remplace le nouveau
            $entity = $serviceAllreadyExists;
        }

        $entity = parent::save($entity);
        foreach ($entity->getVolumeHoraireReferentiel() as $volumeHoraire) {
            if ($volumeHoraire->getRemove()) {
                $this->getServiceVolumeHoraireReferentiel()->delete($volumeHoraire);
            } else {
                $this->getServiceVolumeHoraireReferentiel()->save($volumeHoraire);
            }
        }

        return $entity;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param ServiceReferentiel $entity Entité à détruire
     * @param bool               $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if ($softDelete) {
            $vhListe = $entity->getVolumeHoraireReferentielListe();
            $listes  = $vhListe->getSousListes([$vhListe::FILTRE_HORAIRE_DEBUT, $vhListe::FILTRE_HORAIRE_FIN]);
            foreach ($listes as $liste) {
                $liste->setHeures(0);
            }
        }


        $vhrl = $entity->getVolumeHoraireReferentiel();

        $delete = true;
        foreach ($vhrl as $volumeHoraire) {
            if ($volumeHoraire->getRemove() || !$volumeHoraire->estNonHistorise()) {
                $this->getServiceVolumeHoraireReferentiel()->delete($volumeHoraire, $softDelete);
                $vhrl->removeElement($volumeHoraire);
            } else {
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

                $annee                 = $this->getServiceContext()->getAnnee()->getId();
                $fonctionAnneeCourante = $this->getServiceFonctionReferentiel()->getFonctionByCodeAndAnnee($o['fonction']->getCode(), $annee);
                $service->setFonctionReferentiel($fonctionAnneeCourante);

                $service->setStructure($o['structure']);
                $service->setCommentaires($o['commentaires']);
                $service->setTypeVolumeHoraire($o['type-volume-horaire']);
            }
            $volumeHoraire = $this->getServiceVolumeHoraireReferentiel()->newEntity();
            //@formatter:off
            $volumeHoraire->setTypeVolumeHoraire($o['type-volume-horaire']);
            if(!$service->getTypeVolumeHoraire()){
                $service->setTypeVolumeHoraire($o['type-volume-horaire']);
            }
            $volumeHoraire->setHeures($o['heures']);
            //@formatter:on
            $volumeHoraire->setServiceReferentiel($service);
            $service->addVolumeHoraireReferentiel($volumeHoraire);

            $service->setHistoDestructeur(null); // restauration du service si besoin!!
            $this->save($service);
        }
    }



    public function getPrevusFromPrevusData(Intervenant $intervenant)
    {
        $tvhPrevu  = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $tvhSource = $this->getServiceTypeVolumeHoraire()->getByCode($this->getServiceParametres()->get('report_service'));
        $evhValide = $this->getServiceEtatVolumeHoraire()->getSaisi();

        $intervenantPrec = $this->getServiceIntervenant()->getPrecedent($intervenant);

        $sVolumeHoraireReferentiel = $this->getServiceVolumeHoraireReferentiel();

        $qb = $this->select(['id', 'fonctionReferentiel', 'structure', 'commentaires']);
        //@formatter:off
        $this->join(FonctionReferentielService::class, $qb, 'fonctionReferentiel', true);
        $this->Join(StructureService::class, $qb, 'structure', true);
        $this->Join($sVolumeHoraireReferentiel, $qb, 'volumeHoraireReferentiel', true);
        //@formatter:on

        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenantPrec, $qb);
        $this->getServiceFonctionReferentiel()->finderByHistorique($qb); // pour éviter que des fonctions devenues historiques ne soient reconduites
        $this->getServiceStructure()->finderByHistorique($qb);           // idem pour les structures anciennes!!
        $sVolumeHoraireReferentiel->finderByHistorique($qb);
        $sVolumeHoraireReferentiel->finderByTypeVolumeHoraire($tvhSource, $qb);
        $sVolumeHoraireReferentiel->finderByEtatVolumeHoraire($evhValide, $qb);

        $s = $this->getList($qb);

        $old = [];
        foreach ($s as $service) {

            /* @var $service ServiceReferentiel */

            $ok = $service->getFonctionReferentiel()->estNonHistorise()
                && $service->getStructure()->estNonHistorise();

            if ($ok) {
                $o = [
                    'type-volume-horaire' => $tvhPrevu,
                    'fonction'            => $service->getFonctionReferentiel(),
                    'structure'           => $service->getStructure(),
                    'commentaires'        => $service->getCommentaires(),
                    'heures'              => $service->getVolumeHoraireReferentielListe()->getHeures(),
                    'service'             => $this->getBy(
                        $intervenant,
                        $service->getFonctionReferentiel(),
                        $service->getStructure(),
                        $service->getTag(),
                        $service->getMotifNonPaiement()
                    ),
                ];

                $newService = $o['service'];
                /* @var $newService ServiceReferentiel */

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



    public function setRealisesFromPrevus(ServiceReferentiel $service)
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