<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;

final class CalculTauxCongesPayesTest extends TblContratTestCase
{
    public function testMission()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES => 0.3,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->isMission   = true;

        $this->process->calculTauxCongesPayes($contrat1);

        self::assertEquals($parametres[Parametre::TAUX_CONGES_PAYES],$contrat1->tauxCongesPayes);
    }

    public function testEnseignements()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES => 0.3,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->isMission   = false;

        $this->process->calculTauxCongesPayes($contrat1);

        self::assertEquals(0,$contrat1->tauxCongesPayes);
    }


}
