<?php

namespace Application\Service;

use Application\Entity\Db\FonctionReferentiel as FonctionReferentielEntity;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\FonctionReferentielAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\ServiceReferentiel as ServiceReferentielEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\Validation as ValidationEntity;

/**
 * Description of ServiceReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentiel extends AbstractEntityService
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use FonctionReferentielAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;
    use VolumeHoraireReferentielAwareTrait;



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
            $this->join($this->getServiceIntervenant(), $qb, 'intervenant', $alias);
            $this->getServiceIntervenant()->finderByType($typeIntervenant, $qb);
        }

        return $qb;
    }



    /**
     *
     * @param StructureEntity   $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStructureAff(StructureEntity $structure = null, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($structure) {
            $this->join($this->getServiceIntervenant(), $qb, 'intervenant', $alias);
            $this->getServiceIntervenant()->finderByStructure($structure, $qb);
        }

        return $qb;
    }



    /**
     *
     * @param StructureEntity   $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByStructureEns(StructureEntity $structure = null, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        if ($structure) {
            $this->finderByStructure($structure, $qb, $alias);
        }

        return $qb;
    }



    /**
     * Retourne le query builder permettant de rechercher les services référentiels
     * selon la composante spécifiée.
     *
     * @param StructureEntity   $structure
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByComposante(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $iAlias = $this->getServiceIntervenant()->getAlias();
        $filter = "($iAlias.structure = :composante OR $alias.structure = :composante)";
        $qb->andWhere($filter)->setParameter('composante', $structure);

        return $qb;
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

        if ($role instanceof \Application\Interfaces\IntervenantAwareInterface && $role->getIntervenant()) { // Si c'est un intervenant
            $this->finderByIntervenant($role->getIntervenant(), $qb, $alias);
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
            $serviceVolumeHoraireReferentiel = $this->getServiceLocator()->get('applicationVolumeHoraireReferentiel');
            /* @var $serviceVolumeHoraireReferentiel VolumeHoraireReferentiel */

            $this->join($serviceVolumeHoraireReferentiel, $qb, 'volumeHoraireReferentiel');
            $serviceVolumeHoraireReferentiel->finderByTypeVolumeHoraire($typeVolumeHoraire, $qb);
        }

        return $qb;
    }



    /**
     * Retourne un service unique selon ses critères précis
     *
     * @param IntervenantEntity         $intervenant
     * @param FonctionReferentielEntity $fonction
     * @param StructureEntity           $structure
     *
     * @return null|\Application\Entity\Db\ServiceReferentiel
     */
    public function getBy(
        IntervenantEntity $intervenant,
        FonctionReferentielEntity $fonction,
        StructureEntity $structure
    )
    {
        $result = $this->getRepo()->findBy([
            'intervenant' => $intervenant,
            'fonction'    => $fonction,
            'structure'   => $structure,
        ]);

        if (count($result) > 1){
            foreach( $result as $sr ){
                /* @var $sr \Application\Entity\Db\ServiceReferentiel  */
                if ($sr->estNonHistorise()) return $sr;
            }
            return $sr[0]; // sinon retourne le premier trouvé...
        }elseif(isset($result[0])){
            return $result[0];
        }else{
            return null;
        }
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
     * @param TypeVolumeHoraireEntity    $typeVolumeHoraire
     */
    public function setTypeVolumeHoraire($servicesReferentiels, TypeVolumeHoraireEntity $typeVolumeHoraire)
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
        if ($role instanceof \Application\Interfaces\IntervenantAwareInterface) {
            $entity->setIntervenant($role->getIntervenant());
        }

        return $entity;
    }



    /**
     * Sauvegarde une entité
     *
     * @param ServiceReferentielEntity $entity
     *
     * @throws \Common\Exception\RuntimeException
     */
    public function save($entity)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->getEntityManager()->getConnection()->beginTransaction();
        try {
            if (!$entity->getIntervenant() && $role instanceof \Application\Interfaces\IntervenantAwareInterface && $role->getIntervenant()) {
                $entity->setIntervenant($role->getIntervenant());
            }
            if (!$this->getAuthorize()->isAllowed($entity, $entity->getId() ? 'update' : 'create')) {
                throw new \BjyAuthorize\Exception\UnAuthorizedException('Saisie interdite');
            }

            $serviceAllreadyExists = null;
            if (!$entity->getId()) { // uniquement pour les nouveaux services!!
                $serviceAllreadyExists = $this->getRepo()->findOneBy([
                    'intervenant'  => $entity->getIntervenant(),
                    'structure'    => $entity->getStructure(),
                    'fonction'     => $entity->getFonction(),
                    'commentaires' => $entity->getCommentaires(),
                ]);
            }
            if ($serviceAllreadyExists) {
                $result = $serviceAllreadyExists;
            } else {
                $result = parent::save($entity);
            }

            /* Sauvegarde automatique des volumes horaires associés */
            $serviceVolumeHoraire = $this->getServiceLocator()->get('applicationVolumeHoraireReferentiel');
            /* @var $serviceVolumeHoraire VolumeHoraireReferentiel */
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
     *
     * @param TypeVolumeHoraireEntity $typeVolumeHoraire
     * @param IntervenantEntity       $intervenant
     * @param StructureEntity         $structureRef
     *
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
     * @param TypeValidationEntity    $validation
     * @param IntervenantEntity       $intervenant
     * @param StructureEntity         $structureRef
     * @param StructureEntity         $structureValidation
     *
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
            ->join("v.structure", 'str')// validés par la structure spécifiée
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



    /**
     * Prend les services d'un intervenant, année n-1, et reporte ces services (et les volumes horaires associés)
     * sur l'année n
     *
     * @param IntervenantEntity $intervenant
     *
     */
    public function setPrevusFromPrevus(IntervenantEntity $intervenant)
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



    public function getPrevusFromPrevusData(IntervenantEntity $intervenant)
    {
        $tvhPrevu  = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $evhValide = $this->getServiceEtatVolumeHoraire()->getSaisi();

        $intervenantPrec = $this->getServiceIntervenant()->getBySourceCode(
            $intervenant->getSourceCode(),
            $this->getServiceContext()->getAnneePrecedente()
        );

        $sVolumeHoraireReferentiel = $this->getServiceVolumeHoraireReferentiel();

        $qb = $this->select(['id', 'fonction', 'structure', 'commentaires']);
        //@formatter:off
        $this->join('applicationFonctionReferentiel',   $qb, 'fonctionReferentiel',     true);
        $this->Join('applicationStructure',             $qb, 'structure',               true);
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

        foreach ($realises->get() as $vh) {
            /* @var $vh \Application\Entity\Db\VolumeHoraire */
            $vh->setRemove(true);
        }

        foreach ($prevus->get() as $vh) {
            $nvh = new \Application\Entity\Db\VolumeHoraireReferentiel;
            $nvh->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getRealise());
            $nvh->setServiceReferentiel($service);
            $nvh->setHeures($vh->getHeures());
            $service->addVolumeHoraireReferentiel($nvh);
        }
        $this->save($service);
    }
}