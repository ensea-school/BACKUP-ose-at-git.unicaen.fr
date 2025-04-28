<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;

final class CalculActifTest extends TblContratTestCase
{
    public function testParametresCaenMission()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);


        /* Jeu de données initial */
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = true;
        $contrat1->parent    = null;

        $avenant            = new Contrat();
        $avenant->id        = 1;
        $avenant->edite     = true;
        $avenant->isMission = true;
        $avenant->parent    = $contrat1;

        $avenant2            = new Contrat();
        $avenant2->isMission = true;
        $avenant2->parent    = $contrat1;

        $this->process->calculActif($contrat1);
        $this->process->calculActif($avenant);
        $this->process->calculActif($avenant2);

        self::assertTrue($contrat1->actif);
        self::assertTrue($avenant->actif);
        self::assertTrue($avenant2->actif);
    }

    public function testParametresCaenEnseignement()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);


        /* Jeu de données initial */
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = false;
        $contrat1->parent    = null;

        $avenant            = new Contrat();
        $avenant->id        = 1;
        $avenant->edite     = true;
        $avenant->isMission = false;
        $avenant->parent    = $contrat1;

        $avenant2            = new Contrat();
        $avenant2->isMission = false;
        $avenant2->parent    = $contrat1;

        $this->process->calculActif($contrat1);
        $this->process->calculActif($avenant);
        $this->process->calculActif($avenant2);

        self::assertTrue($contrat1->actif);
        self::assertTrue($avenant->actif);
        self::assertTrue($avenant2->actif);
    }

    public function testParametresAvenantDesactiveEnseignement()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_DESACTIVE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);


        /* Jeu de données initial */
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = false;
        $contrat1->parent    = null;

        $avenant            = new Contrat();
        $avenant->id        = 1;
        $avenant->edite     = true;
        $avenant->isMission = false;
        $avenant->parent    = $contrat1;

        $avenant2            = new Contrat();
        $avenant2->isMission = false;
        $avenant2->parent    = $contrat1;

        $this->process->calculActif($contrat1);
        $this->process->calculActif($avenant);
        $this->process->calculActif($avenant2);

        self::assertTrue($contrat1->actif);
        self::assertTrue($avenant->actif);
        self::assertFalse($avenant2->actif);
    }

    public function testParametresAvenantDesactiveMission()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_DESACTIVE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);


        /* Jeu de données initial */
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = false;
        $contrat1->parent    = null;

        $avenant            = new Contrat();
        $avenant->id        = 1;
        $avenant->edite     = true;
        $avenant->isMission = false;
        $avenant->parent    = $contrat1;

        $avenant2            = new Contrat();
        $avenant2->isMission = false;
        $avenant2->parent    = $contrat1;

        $this->process->calculActif($contrat1);
        $this->process->calculActif($avenant);
        $this->process->calculActif($avenant2);

        self::assertTrue($contrat1->actif);
        self::assertTrue($avenant->actif);
        self::assertFalse($avenant2->actif);
    }

}
