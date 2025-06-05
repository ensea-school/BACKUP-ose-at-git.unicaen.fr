<?php declare(strict_types=1);

namespace tests\TblContrat;

use Administration\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;

final class CalculTotalHeuresTest extends TblContratTestCase
{
    public function testParametresCaen()
    {

        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = true;


        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->structureId = 495;
        $volumeHoraire1->heures      = 50;
        $contrat1->volumesHoraires[] = $volumeHoraire1;


        $contrat2              = new Contrat();
        $contrat2->structureId = 594;
        $contrat2->parent      = $contrat1;

        $volumeHoraire5         = new VolumeHoraire();
        $volumeHoraire5->heures = 10;

        $volumeHoraire6         = new VolumeHoraire();
        $volumeHoraire6->heures = 20;

        $contrat2->volumesHoraires = [$volumeHoraire5, $volumeHoraire6];
        $contrat1->avenants        = [$contrat2];

        $this->process->calculTotalHeures($contrat1);
        $this->process->calculTotalHeures($contrat2);

        self::assertEquals(50.0, $contrat1->totalHeures);
        self::assertEquals(30.0, $contrat2->totalHeures);
    }



    public function testMission()
    {

        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = false;


        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->structureId = 495;
        $volumeHoraire1->heures      = 40;
        $volumeHoraire1->hetd        = 60;
        $contrat1->volumesHoraires[] = $volumeHoraire1;


        $contrat2              = new Contrat();
        $contrat2->structureId = 594;
        $contrat2->parent      = $contrat1;

        $volumeHoraire5         = new VolumeHoraire();
        $volumeHoraire5->heures = 10;
        $volumeHoraire5->hetd   = 15;

        $volumeHoraire6         = new VolumeHoraire();
        $volumeHoraire6->heures = 20;
        $volumeHoraire6->hetd   = 20;

        $contrat2->volumesHoraires = [$volumeHoraire5, $volumeHoraire6];
        $contrat1->avenants        = [$contrat2];

        $this->process->calculTotalHeures($contrat1);
        $this->process->calculTotalHeures($contrat2);

        self::assertEquals(40.0, $contrat1->totalHeures);
        self::assertEquals(30.0, $contrat2->totalHeures);
    }

}
