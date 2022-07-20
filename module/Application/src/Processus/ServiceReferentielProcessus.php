<?php

namespace Application\Processus;

use Referentiel\Entity\Db\ServiceReferentiel;
use Service\Entity\Recherche;
use Service\Service\EtatVolumeHoraireService;
use Application\Service\IntervenantService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielServiceAwareTrait;

/**
 * Description of ServiceReferentielProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;
    use ServiceReferentielServiceAwareTrait;
    use VolumeHoraireReferentielServiceAwareTrait;


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
            ->join(IntervenantService::class, $qb, 'intervenant', ['id', 'nomUsuel', 'prenom', 'sourceCode'])
            ->join($volumeHoraireReferentielService, $qb, 'volumeHoraireReferentiel', ['id', 'heures', 'autoValidation']);

        $volumeHoraireReferentielService->leftJoin(EtatVolumeHoraireService::class, $qb, 'etatVolumeHoraireReferentiel', ['id', 'code', 'libelle', 'ordre']);

        $serviceReferentiel->finderByContext($qb);
        $serviceReferentiel->finderByFilterObject($recherche, new \Laminas\Hydrator\ClassMethodsHydrator(false), $qb, null, ['typeVolumeHoraire', 'etatVolumeHoraire']);

        if ($intervenant) {
            $serviceReferentiel->finderByIntervenant($intervenant, $qb);
        }
        if (!$intervenant && $role->getStructure()) {
            $serviceReferentiel->finderByStructure($role->getStructure(), $qb);
        }

        $services = $serviceReferentiel->getList($qb);
        /* @var $services ServiceReferentiel[] */

        foreach ($services as $k => $service) {
            $service->setTypeVolumeHoraire($recherche->getTypeVolumehoraire());
        }

        return $services;
    }
}