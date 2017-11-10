<?php

namespace Application\Processus;

use Application\Entity\Db\Intervenant;
use Application\Entity\Service\Recherche;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\PeriodeAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\VolumeHoraireAwareTrait;


/**
 * Description of ServiceProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ServiceProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use VolumeHoraireAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use StructureAwareTrait;
    use EtapeAwareTrait;
    use PeriodeAwareTrait;



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
            ->join(     'applicationIntervenant',       $qb, 'intervenant',         ['id', 'nomUsuel', 'prenom','sourceCode'] )
            ->leftJoin( $elementPedagogiqueService,     $qb, 'elementPedagogique',  ['id', 'sourceCode', 'libelle', 'histoDestruction', 'fi', 'fc', 'fa', 'tauxFi', 'tauxFc', 'tauxFa', 'tauxFoad'] )
            ->leftjoin( $volumeHoraireService,          $qb, 'volumeHoraire',       ['id', 'heures'] );

        $elementPedagogiqueService
            ->leftJoin( $structureService,              $qb, 'structure',           ['id', 'libelleCourt'] )
            ->leftJoin( $etapeService,                  $qb, 'etape',               ['id', 'libelle', 'niveau', 'histoDestruction', 'sourceCode'] )
            ->leftJoin( $periodeService,                $qb, 'periode',             ['id', 'code', 'libelleLong', 'libelleCourt', 'ordre'] )
            ->leftJoin( 'applicationTypeIntervention',  $qb, 'typeIntervention',    ['id', 'code', 'libelle', 'ordre'] );

        $volumeHoraireService
            ->leftJoin( 'applicationMotifNonPaiement',  $qb, 'motifNonPaiement',    ['id', 'libelleCourt', 'libelleLong'] )
            ->leftJoin( 'applicationEtatVolumeHoraire', $qb, 'etatVolumeHoraire',   ['id','code','libelle','ordre'] );

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