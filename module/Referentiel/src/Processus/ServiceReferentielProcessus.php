<?php

namespace Referentiel\Processus;

use Application\Processus\AbstractProcessus;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Service\IntervenantService;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Service\ServiceReferentielServiceAwareTrait;
use Referentiel\Service\VolumeHoraireReferentielServiceAwareTrait;
use Service\Entity\Recherche;
use Service\Service\EtatVolumeHoraireService;

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
     * @param Recherche $recherche
     *
     * @return ServiceReferentiel[]|array
     */
    public function getReferentiels(Recherche $recherche): array
    {
        $serviceReferentiel = $this->getServiceServiceReferentiel();
        $volumeHoraireReferentielService = $this->getServiceVolumeHoraireReferentiel();

        $qb = $serviceReferentiel->initQuery()[0];

        $serviceReferentiel
            ->join(IntervenantService::class, $qb, 'intervenant', ['id', 'nomUsuel', 'prenom', 'sourceCode'])
            ->join($volumeHoraireReferentielService, $qb, 'volumeHoraireReferentiel', ['id', 'heures', 'autoValidation']);

        $volumeHoraireReferentielService->leftJoin(EtatVolumeHoraireService::class, $qb, 'etatVolumeHoraireReferentiel', ['id', 'code', 'libelle', 'ordre']);

        $serviceReferentiel->finderByContext($qb);
        $serviceReferentiel->finderByFilterObject($recherche, new \Laminas\Hydrator\ClassMethodsHydrator(false), $qb, null, ['typeVolumeHoraire', 'etatVolumeHoraire']);

        if ($recherche->getIntervenant()) {
            $serviceReferentiel->finderByIntervenant($recherche->getIntervenant(), $qb);
        }
        if (!$recherche->getIntervenant() && $this->getServiceContext()->getStructure()) {
            $serviceReferentiel->finderByStructure($this->getServiceContext()->getStructure(), $qb);
        }

        $services = $serviceReferentiel->getList($qb);
        /* @var $services ServiceReferentiel[] */

        foreach ($services as $k => $service) {
            $service->setTypeVolumeHoraire($recherche->getTypeVolumehoraire());
        }

        
        return $services;
    }
}