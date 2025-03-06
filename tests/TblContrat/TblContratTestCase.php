<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\ContratProcess;
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

        $c = \AppAdmin::container()->get(TableauBordService::class);

        $ptbl          = $c->getTableauBord('contrat');
        $this->process = $ptbl->getProcess();
        $this->process->setServiceTauxRemu(TauxRemuMock::create($this));
        $this->process->setServiceParametres(ParametreMock::create($this, $defaultParametres));
    }



    protected function useParametres(array $parametres)
    {
        $parametres = ParametreMock::parametresFormat($parametres);

        $this->process->getServiceParametres()->expects($this->any())
            ->method('get')
            ->willReturnMap($parametres);

        $this->process->init();

    }

}
