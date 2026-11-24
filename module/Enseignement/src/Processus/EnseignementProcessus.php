<?php

namespace Enseignement\Processus;

use Application\Processus\AbstractProcessus;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Enseignement\Service\ServiceServiceAwareTrait;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantService;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use OffreFormation\Service\TypeInterventionService;
use Paiement\Service\MotifNonPaiementService;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Service\Service\EtatVolumeHoraireService;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of EnseignementProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EnseignementProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use ServiceServiceAwareTrait;


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
        $intervenantService        = $this->getServiceIntervenant();

        $qb = $service->initQuery()[0];
        /* @var $qb \Doctrine\ORM\QueryBuilder */

        //@formatter:off
        $service
            ->join(     IntervenantService::class,  $qb, 'intervenant',        ['id', 'nomUsuel', 'prenom', 'sourceCode'] )
            ->leftJoin( $elementPedagogiqueService, $qb, 'elementPedagogique', ['id', 'code', 'sourceCode', 'libelle', 'histoDestruction', 'fi', 'fc', 'fa', 'tauxFi', 'tauxFc', 'tauxFa', 'tauxFoad'] )
            ->leftJoin( $volumeHoraireService,      $qb, 'volumeHoraire',      ['id', 'heures', 'autoValidation', 'horaireDebut', 'horaireFin', 'histoDestruction'] );

        $elementPedagogiqueService
            ->leftJoin( $structureService, $qb, 'structure', ['id', 'libelleCourt'] )
            ->leftJoin( $etapeService,     $qb, 'etape',     ['id', 'code', 'libelle', 'niveau', 'histoDestruction', 'sourceCode'] );

        $volumeHoraireService
            ->leftJoin( $periodeService,                 $qb, 'periode',          ['id', 'code', 'libelleLong', 'libelleCourt', 'ordre'] )
            ->leftJoin( TypeInterventionService::class,  $qb, 'typeIntervention', ['id', 'code', 'libelle', 'ordre'] )
            ->leftJoin( MotifNonPaiementService::class,  $qb, 'motifNonPaiement', ['id', 'libelleCourt', 'libelleLong'] )
            ->leftJoin( EtatVolumeHoraireService::class, $qb, 'etatVolumeHoraire',['id', 'code', 'libelle', 'ordre'] );
        //@formatter:on

        $aServ  = $service->getAlias();
        $aVh    = $volumeHoraireService->getAlias();
        $aInt   = $intervenantService->getAlias();
        $aEtp   = $etapeService->getAlias();

        // Étape portée directement par le service (prioritaire, comme dans la vue)
        $qb->leftJoin("$aServ.etape", 'etpServ')
            ->addSelect('partial etpServ.{id, code, libelle, niveau, histoDestruction, sourceCode}');

        // Filtres d'historique (comme la vue)
        $qb->andWhere("$aServ.histoDestruction IS NULL");
        $qb->andWhere("$aInt.histoDestruction IS NULL");
        $qb->andWhere("($aVh.id IS NULL OR $aVh.histoDestruction IS NULL)");

        $service->finderByContext($qb);
        $service->finderByFilterObject(
            $recherche,
            new \Laminas\Hydrator\ClassMethodsHydrator(false),
            $qb,
            null,
            ['typeVolumeHoraire', 'etatVolumeHoraire', 'etape']   // 'etape' géré à la main ci-dessous
        );

        // Type de volume horaire (prévu / réalisé)
        if ($tvh = $recherche->getTypeVolumeHoraire()) {
            $qb->andWhere("$aVh.typeVolumeHoraire = :tvh")
                ->setParameter('tvh', $tvh);
        }

        // État : un VH validé compte aussi comme saisi
        if ($evh = $recherche->getEtatVolumeHoraire()) {
            $qb->andWhere('etatvh.ordre >= :ordreEtat')
                ->setParameter('ordreEtat', $evh->getOrdre());
        }

        // Étape : service.etape prioritaire, sinon étape de l'élément
        if ($etape = $recherche->getEtape()) {
            $qb->andWhere("COALESCE(etpServ.id, $aEtp.id) = :etapeId")
                ->setParameter('etapeId', $etape->getId());
        }

        if ($intervenant) {
            $service->finderByIntervenant($intervenant, $qb);
        }

        $structureService->orderBy($qb);
        $etapeService->orderBy($qb);
        $periodeService->orderBy($qb);
        $qb->orderBy($elementPedagogiqueService->getAlias() . '.code');
        $volumeHoraireService->orderBy($qb);
        $this->getServiceTypeIntervention()->orderBy($qb);

        if (!$intervenant && $role->getStructure()) {
            $service->finderByComposante($role->getStructure(), $qb);
        }

        return $service->getList($qb);
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
