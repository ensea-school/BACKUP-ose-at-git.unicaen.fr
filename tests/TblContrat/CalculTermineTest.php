<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;

final class CalculTermineTest extends TblContratTestCase
{
    public function testDateRetourContratNonEdite()
    {
        $parametres = [
            Parametre::AVENANT                      => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS                  => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS                  => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES            => 0.3,
            Parametre::CONTRAT_REGLE_FRANCHISSEMENT => Parametre::CONTRAT_FRANCHI_DATE_RETOUR,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1        = new Contrat();
        $contrat1->id    = 1;
        $contrat1->edite = false;
        $contrat1->signe = false;

        $this->process->calculTermine($contrat1);

        self::assertFalse($contrat1->termine);
    }



    public function testDateRetourContratEdite()
    {
        $parametres = [
            Parametre::AVENANT                      => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS                  => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS                  => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES            => 0.3,
            Parametre::CONTRAT_REGLE_FRANCHISSEMENT => Parametre::CONTRAT_FRANCHI_DATE_RETOUR,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1        = new Contrat();
        $contrat1->id    = 1;
        $contrat1->edite = true;
        $contrat1->signe = false;

        $this->process->calculTermine($contrat1);

        self::assertFalse($contrat1->termine);
    }



    public function testDateRetourContratSigne()
    {
        $parametres = [
            Parametre::AVENANT                      => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS                  => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS                  => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES            => 0.3,
            Parametre::CONTRAT_REGLE_FRANCHISSEMENT => Parametre::CONTRAT_FRANCHI_DATE_RETOUR,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1        = new Contrat();
        $contrat1->id    = 1;
        $contrat1->edite = true;
        $contrat1->signe = true;

        $this->process->calculTermine($contrat1);

        self::assertTrue($contrat1->termine);
    }

    public function testValidationContratNonEdite()
    {
        $parametres = [
            Parametre::AVENANT                      => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS                  => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS                  => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES            => 0.3,
            Parametre::CONTRAT_REGLE_FRANCHISSEMENT => Parametre::CONTRAT_FRANCHI_VALIDATION,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1        = new Contrat();
        $contrat1->id    = 1;
        $contrat1->edite = false;
        $contrat1->signe = false;

        $this->process->calculTermine($contrat1);

        self::assertFalse($contrat1->termine);
    }



    public function testValidationContratEdite()
    {
        $parametres = [
            Parametre::AVENANT                      => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS                  => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS                  => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES            => 0.3,
            Parametre::CONTRAT_REGLE_FRANCHISSEMENT => Parametre::CONTRAT_FRANCHI_VALIDATION,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1        = new Contrat();
        $contrat1->id    = 1;
        $contrat1->edite = true;
        $contrat1->signe = false;

        $this->process->calculTermine($contrat1);

        self::assertTrue($contrat1->termine);
    }



    public function testValidationContratSigne()
    {
        $parametres = [
            Parametre::AVENANT                      => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS                  => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS                  => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES            => 0.3,
            Parametre::CONTRAT_REGLE_FRANCHISSEMENT => Parametre::CONTRAT_FRANCHI_VALIDATION,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1        = new Contrat();
        $contrat1->id    = 1;
        $contrat1->edite = true;
        $contrat1->signe = true;

        $this->process->calculTermine($contrat1);

        self::assertTrue($contrat1->termine);
    }
}
