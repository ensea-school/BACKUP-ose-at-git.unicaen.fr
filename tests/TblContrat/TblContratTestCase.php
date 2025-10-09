<?php declare(strict_types=1);

namespace tests\TblContrat;

use Administration\Entity\Db\Parametre;
use Application\Provider\Tbl\TblProvider;
use Contrat\Tbl\Process\ContratProcess;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Strategy\VolumeHoraireStrategy;
use Laminas\Hydrator\ObjectPropertyHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use tests\Mocks\ParametreMock;
use tests\Mocks\TauxRemuMock;
use tests\OseTestCase;
use UnicaenTbl\Service\TableauBordService;

abstract class TblContratTestCase extends OseTestCase
{

    protected ContratProcess $process;



    protected function setUp(): void
    {
        // Initialisation des paramètres afin d'être sûr de ne pas avoir de problèmes
        // Les taux sont initialisés directement dans le Mock
        $defaultParametres = [
            Parametre::AVENANT                      => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_DIRECT               => Parametre::CONTRAT_DIRECT_DESACTIVE,
            Parametre::CONTRAT_MIS                  => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS                  => Parametre::CONTRAT_ENS_COMPOSANTE,
            Parametre::CONTRAT_REGLE_FRANCHISSEMENT => Parametre::CONTRAT_FRANCHI_DATE_RETOUR,
            Parametre::TAUX_REMU                    => 1,
            Parametre::TAUX_CONGES_PAYES            => 0.1,
        ];

        $c =\Unicaen\Framework\Application\Application::getInstance()->container()->get(TableauBordService::class);

        $ptbl          = $c->getTableauBord(TblProvider::CONTRAT);
        $this->process = $ptbl->getProcess();
        $this->process->setServiceTauxRemu(TauxRemuMock::create($this));
        $this->process->setServiceParametres(new ParametreMock($defaultParametres));
    }



    protected function useParametres(array $parametres)
    {
        $this->process->getServiceParametres()->setParametres($parametres);
        $this->process->init();
    }



    protected function hydrateContrat(array $data): Contrat
    {

        $hydrator              = new ObjectPropertyHydrator();
        $strategyDate          = new DateTimeFormatterStrategy('Y-m-d');
        $strategyVolumeHoraire = new VolumeHoraireStrategy();
        $hydrator->addStrategy('anneeDateDebut', $strategyDate);
        $hydrator->addStrategy('debutValidite', $strategyDate);
        $hydrator->addStrategy('finValidite', $strategyDate);
        $hydrator->addStrategy('histoCreation', $strategyDate);
        $hydrator->addStrategy('tauxRemuDate', $strategyDate);
        $hydrator->addStrategy('volumesHoraires', $strategyVolumeHoraire);

        $contrat = new Contrat();
        $hydrator->hydrate($data, $contrat);

        return $contrat;

    }



    protected function extractContrat(Contrat $contrat): array
    {
        $hydrator              = new ObjectPropertyHydrator();
        $strategyDate          = new DateTimeFormatterStrategy('Y-m-d');
        $strategyVolumeHoraire = new VolumeHoraireStrategy();
        $hydrator->addStrategy('anneeDateDebut', $strategyDate);
        $hydrator->addStrategy('debutValidite', $strategyDate);
        $hydrator->addStrategy('finValidite', $strategyDate);
        $hydrator->addStrategy('histoCreation', $strategyDate);
        $hydrator->addStrategy('tauxRemuDate', $strategyDate);
        $hydrator->addStrategy('volumesHoraires', $strategyVolumeHoraire);

        $data = $hydrator->extract($contrat);

        return $data;

    }

}
