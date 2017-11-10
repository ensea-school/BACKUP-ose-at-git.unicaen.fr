<?php

namespace Application\Processus;

use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Service\Recherche;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielAwareTrait;

/**
 * Description of ServiceReferentielProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;
    use ServiceReferentielAwareTrait;
    use VolumeHoraireReferentielAwareTrait;



    /**
     *
     * @param Intervenant|null $intervenant
     * @param Recherche        $recherche
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getServices($intervenant, Recherche $recherche)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $serviceReferentiel              = $this->getServiceServiceReferentiel();
        $volumeHoraireReferentielService = $this->getServiceVolumeHoraireReferentiel();

        $qb = $serviceReferentiel->initQuery()[0];

        $serviceReferentiel
            ->join('applicationIntervenant', $qb, 'intervenant', ['id', 'nomUsuel', 'prenom', 'sourceCode'])
            ->join($volumeHoraireReferentielService, $qb, 'volumeHoraireReferentiel', ['id', 'heures']);

        $volumeHoraireReferentielService->leftJoin('applicationEtatVolumeHoraire', $qb, 'etatVolumeHoraireReferentiel', ['id', 'code', 'libelle', 'ordre']);

        $serviceReferentiel->finderByContext($qb);
        $serviceReferentiel->finderByFilterObject($recherche, new \Zend\Stdlib\Hydrator\ClassMethods(false), $qb, null, ['typeVolumeHoraire', 'etatVolumeHoraire']);

        if ($intervenant) {
            $serviceReferentiel->finderByIntervenant($intervenant, $qb);
        }
        if (!$intervenant && $role->getStructure()) {
            $serviceReferentiel->finderByStructure($role->getStructure(), $qb);
        }

        $services = $serviceReferentiel->getList($qb);
        /* @var $services ServiceReferentiel[]  */

        foreach( $services as $k => $service ){
            $service->setTypeVolumeHoraire( $recherche->getTypeVolumehoraire() );
        }

        return $services;
    }
}