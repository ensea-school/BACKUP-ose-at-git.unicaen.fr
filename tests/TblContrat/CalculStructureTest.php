<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;

final class CalculStructureTest extends TblContratTestCase
{

    public function testCasSimple(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        $contrat1              = new Contrat();
        $contrat1->id          = 38054;
        $contrat1->structureId = 477;
        $contrat1->edite       = true;
        $contrat1->totalHetd   = 2.1;

        $volumeHoraire1                  = new VolumeHoraire();
        $volumeHoraire1->structureId     = 477;
        $volumeHoraire1->serviceId       = 296429;
        $volumeHoraire1->volumeHoraireId = 715748;
        $volumeHoraire1->cm              = 1.4;
        $volumeHoraire1->heures          = 1.4;
        $volumeHoraire1->hetd            = 2.1;

        $contrat1->volumesHoraires = [$volumeHoraire1];

        $avenant1              = new Contrat();
        $avenant1->id          = 40343;
        $avenant1->structureId = 76;
        $avenant1->setParent($contrat1);
        $avenant1->numeroAvenant = 1;
        $avenant1->totalHetd     = 2.1;

        $this->process->calculStructure($contrat1);
        $this->process->calculStructure($avenant1);

        self::assertEquals(76, $avenant1->structureId);
        self::assertEquals(477, $contrat1->structureId);
    }

}
