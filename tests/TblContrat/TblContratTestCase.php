<?php declare(strict_types=1);

namespace tests\TblContrat;

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
        $c = \AppAdmin::container()->get(TableauBordService::class);

        $ptbl     = $c->getTableauBord('contrat');
        $this->process = $ptbl->getProcess();

        $this->process->setServiceTauxRemu(TauxRemuMock::create($this));
        $this->process->setServiceParametres(ParametreMock::create($this));

    }



    protected function useParametres(array $parametres)
    {
        $this->process->getServiceParametres()->expects($this->any())
            ->method('get')
            ->willReturnMap($parametres);
    }

}
