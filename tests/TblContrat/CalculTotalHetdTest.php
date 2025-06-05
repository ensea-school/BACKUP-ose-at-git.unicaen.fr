<?php declare(strict_types=1);

namespace tests\TblContrat;

use Administration\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;

final class CalculTotalHetdTest extends TblContratTestCase
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
        $contrat1                  = new Contrat();
        $contrat1->id              = 1;
        $contrat1->edite           = true;
        $contrat1->totalGlobalHetd = 1.5;
        $contrat1->isMission       = false;


        $volumeHoraire1                  = new VolumeHoraire();
        $volumeHoraire1->structureId     = 495;
        $volumeHoraire1->serviceId       = 316066;
        $volumeHoraire1->volumeHoraireId = 748185;
        $volumeHoraire1->cm              = 1;
        $volumeHoraire1->heures          = 1;
        $volumeHoraire1->hetd            = 1.5;
        $volumeHoraire1->tauxRemuId      = 1;
        $contrat1->volumesHoraires[]     = $volumeHoraire1;


        $contrat2              = new Contrat();
        $contrat2->structureId = 594;
        $contrat2->parent      = $contrat1;

        $volumeHoraire5                  = new VolumeHoraire();
        $volumeHoraire5->structureId     = 594;
        $volumeHoraire5->serviceId       = 316065;
        $volumeHoraire5->volumeHoraireId = 748183;
        $volumeHoraire5->cm              = 2;
        $volumeHoraire5->heures          = 2;
        $volumeHoraire5->hetd            = 3;

        $volumeHoraire6                  = new VolumeHoraire();
        $volumeHoraire6->structureId     = 594;
        $volumeHoraire6->serviceId       = 316065;
        $volumeHoraire6->volumeHoraireId = 748184;
        $volumeHoraire6->td              = 2;
        $volumeHoraire6->heures          = 2;
        $volumeHoraire6->hetd            = 2;

        $contrat2->volumesHoraires = [$volumeHoraire5, $volumeHoraire6];
        $contrat1->avenants        = [$contrat2];

        $this->process->calculTotalHETD($contrat1);
        $this->process->calculTotalHETD($contrat2);

        self::assertEquals(1.5, $contrat1->totalHetd);
        self::assertEquals(1.5, $contrat1->totalGlobalHetd);


        self::assertEquals(5.0, $contrat2->totalHetd);
        self::assertEquals(6.5, $contrat2->totalGlobalHetd);
    }


}
