<?php

namespace Enseignement\Processus;

use Application\Entity\Db\Intervenant;
use Application\Processus\AbstractProcessus;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Service\Service\EtatVolumeHoraireService;
use Application\Service\IntervenantService;
use Application\Service\MotifNonPaiementService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireServiceAwareTrait;
use Application\Service\TypeInterventionService;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of EnseignementProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EnseignementProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;
    use ServiceServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;


    /**
     * @param Recherche $recherche
     *
     * @return array|Service[]
     */
    public function getEnseignements(Recherche $recherche): array
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($role->getIntervenant()) {
            $intervenant = $role->getIntervenant();
        } else {
            $intervenant = $recherche->getIntervenant();
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
            ->leftJoin( $elementPedagogiqueService,     $qb, 'elementPedagogique',  ['id', 'code', 'sourceCode', 'libelle', 'histoDestruction', 'fi', 'fc', 'fa', 'tauxFi', 'tauxFc', 'tauxFa', 'tauxFoad'] )
            ->leftjoin( $volumeHoraireService,          $qb, 'volumeHoraire',       ['id', 'heures', 'autoValidation', 'horaireDebut', 'horaireFin'] );

        $elementPedagogiqueService
            ->leftJoin( $structureService,              $qb, 'structure',           ['id', 'libelleCourt'] )
            ->leftJoin( $etapeService,                  $qb, 'etape',               ['id', 'code', 'libelle', 'niveau', 'histoDestruction', 'sourceCode'] )
            ->leftJoin( $periodeService,                $qb, 'periode',             ['id', 'code', 'libelleLong', 'libelleCourt', 'ordre'] )
            ->leftJoin( TypeInterventionService::class,  $qb, 'typeIntervention',    ['id', 'code', 'libelle', 'ordre'] );

        $volumeHoraireService
            ->leftJoin( MotifNonPaiementService::class,  $qb, 'motifNonPaiement',    ['id', 'libelleCourt', 'libelleLong'] )
            ->leftJoin( EtatVolumeHoraireService::class, $qb, 'etatVolumeHoraire',   ['id','code','libelle','ordre'] );

        //@formatter:on

        $service->finderByContext($qb);
        $service->finderByFilterObject($recherche, new \Laminas\Hydrator\ClassMethodsHydrator(false), $qb, null, ['typeVolumeHoraire', 'etatVolumeHoraire']);

        if ($intervenant) {
            $service->finderByIntervenant($intervenant, $qb);
        }
        $structureService->orderBy($qb);
        $etapeService->orderBy($qb);
        $periodeService->orderBy($qb);
        $qb->orderBy($elementPedagogiqueService->getAlias() . '.code');
        $this->getServiceVolumeHoraire()->orderBy($qb);
        $this->getServiceTypeIntervention()->orderBy($qb);

        if (!$intervenant && $role->getStructure()) {
            $service->finderByComposante($role->getStructure(), $qb);
        }

        $services = $service->getList($qb);

        return $services;
    }



    public function initializeRealise(Intervenant $intervenant): ?TypeVolumeHoraire
    {
        $constatationServiceTvh = $this->getServiceParametres()->get('constatation_realise');
        $typeVolumeHoraire      = $this->getServiceTypeVolumeHoraire()->getByCode($constatationServiceTvh);

        if (!$typeVolumeHoraire) return null;

        $sql = "SELECT valide FROM tbl_service WHERE intervenant_id = :intervenant AND type_volume_horaire_id = :typeVolumeHoraire AND valide > 0";
        $res = $this->getEntityManager()->getConnection()->fetchOne($sql, [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
        ]);

        if ($res) {
            return $typeVolumeHoraire;
        } else {
            return null;
        }
    }



    public function initializePrevu(Intervenant $intervenant): ?TypeVolumeHoraire
    {
        $reportServiceTvh  = $this->getServiceParametres()->get('report_service');
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($reportServiceTvh);

        if (!$typeVolumeHoraire) return null;

        $intervenant = $this->getServiceIntervenant()->getPrecedent($intervenant);
        if (!$intervenant) return null;

        $sql = "SELECT valide FROM tbl_service WHERE intervenant_id = :intervenant AND type_volume_horaire_id = :typeVolumeHoraire AND valide > 0";
        $res = $this->getEntityManager()->getConnection()->fetchOne($sql, [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
        ]);

        if ($res) {
            return $typeVolumeHoraire;
        } else {
            return null;
        }
    }

}