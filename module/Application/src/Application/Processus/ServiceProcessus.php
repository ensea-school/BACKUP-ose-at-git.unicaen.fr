<?php

namespace Application\Processus;

use Application\Entity\Db\Intervenant;
use Application\Entity\Service\Recherche;
use Application\Service\EtatVolumeHoraireService;
use Application\Service\IntervenantService;
use Application\Service\MotifNonPaiementService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireServiceAwareTrait;
use Application\Service\TypeInterventionService;


/**
 * Description of ServiceProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ServiceProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use PeriodeServiceAwareTrait;



    /**
     *
     * @param Intervenant|null $intervenant
     * @param Recherche        $recherche
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getServices($intervenant, $recherche)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($role->getIntervenant()) {
            $intervenant = $role->getIntervenant();
        }

        $service                   = $this->getServiceService();
        $volumeHoraireService      = $this->getServiceVolumeHoraire();
        $elementPedagogiqueService = $this->getServiceElementPedagogique();
        $structureService          = $this->getServiceStructure();
        $etapeService              = $this->getServiceEtape();
        $periodeService            = $this->getServicePeriode();

        $qb = $service->initQuery()[0];
        /* @var $qb \Doctrine\ORM\QueryBuilder */

        //@formatter:off
        $service
            ->join(     IntervenantService::class,      $qb, 'intervenant',         ['id', 'nomUsuel', 'prenom','sourceCode'] )
            ->leftJoin( $elementPedagogiqueService,     $qb, 'elementPedagogique',  ['id', 'sourceCode', 'libelle', 'histoDestruction', 'fi', 'fc', 'fa', 'tauxFi', 'tauxFc', 'tauxFa', 'tauxFoad'] )
            ->leftjoin( $volumeHoraireService,          $qb, 'volumeHoraire',       ['id', 'heures', 'autoValidation'] );

        $elementPedagogiqueService
            ->leftJoin( $structureService,              $qb, 'structure',           ['id', 'libelleCourt'] )
            ->leftJoin( $etapeService,                  $qb, 'etape',               ['id', 'libelle', 'niveau', 'histoDestruction', 'sourceCode'] )
            ->leftJoin( $periodeService,                $qb, 'periode',             ['id', 'code', 'libelleLong', 'libelleCourt', 'ordre'] )
            ->leftJoin( TypeInterventionService::class,  $qb, 'typeIntervention',    ['id', 'code', 'libelle', 'ordre'] );

        $volumeHoraireService
            ->leftJoin( MotifNonPaiementService::class,  $qb, 'motifNonPaiement',    ['id', 'libelleCourt', 'libelleLong'] )
            ->leftJoin( EtatVolumeHoraireService::class, $qb, 'etatVolumeHoraire',   ['id','code','libelle','ordre'] );

        //@formatter:on

        $service->finderByContext($qb);
        $service->finderByFilterObject($recherche, new \Zend\Stdlib\Hydrator\ClassMethods(false), $qb, null, ['typeVolumeHoraire', 'etatVolumeHoraire']);

        if ($intervenant) {
            $service->finderByIntervenant($intervenant, $qb);
        }

        $qb
            ->addOrderBy($structureService->getAlias() . '.libelleCourt')
            ->addOrderBy($etapeService->getAlias() . '.libelle')
            ->addOrderBy($periodeService->getAlias() . '.libelleCourt')
            ->addOrderBy($elementPedagogiqueService->getAlias() . '.sourceCode');

        if (!$intervenant && $role->getStructure()) {
            $service->finderByComposante($role->getStructure(), $qb);
        }

        $services = $service->getList($qb);

        return $services;
    }

}