<?php declare(strict_types=1);

namespace tests\TblContrat;

use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;

final class CalculTypeServiceTest extends TblContratTestCase
{
    /**
     * Test pour vérifier que pour un contrat global sans avenant existant,
     * le numéro d'aveant sera bien 1
     *
     * @return void
     */

    public function testContratMission(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        $volumeHoraire            = new VolumeHoraire();
        $volumeHoraire->missionId = 1;
        $contrat->volumesHoraires = [$volumeHoraire];

        $this->process->calculTypeService($contrat);
        $this->assertEquals(true, $contrat->isMission);

    }



    public function testContratEnseignement(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        $volumeHoraire            = new VolumeHoraire();
        $volumeHoraire->serviceId = 1;
        $contrat->volumesHoraires = [$volumeHoraire];

        $this->process->calculTypeService($contrat);
        $this->assertEquals(false, $contrat->isMission);

    }



    public function testContratReferentiel(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        $volumeHoraire                       = new VolumeHoraire();
        $volumeHoraire->serviceReferentielId = 1;
        $contrat->volumesHoraires            = [$volumeHoraire];

        $this->process->calculTypeService($contrat);
        $this->assertEquals(false, $contrat->isMission);

    }



    public function testContratSansHeure(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];


        $this->process->calculTypeService($contrat);
        $this->assertEquals(false, $contrat->isMission);

    }



    public function testAvenantSansHeureSurMission(): void
    {

        /* Contrat initial */
        $contrat     = new Contrat();
        $contrat->id = 1;

        $volumeHoraire            = new VolumeHoraire();
        $volumeHoraire->missionId = 1;
        $contrat->volumesHoraires = [$volumeHoraire];


        $avenant                = new Contrat();
        $avenant->edite         = true;
        $avenant->parent        = $contrat;
        $avenant->numeroAvenant = 1;
        $avenant->avenants      = [];

        $contrat->avenants[] = $avenant;

        $this->process->calculTypeService($avenant);
        $this->assertEquals(true, $avenant->isMission);

    }



    public function testAvenantSansHeureSurEnseignement(): void
    {

        /* Contrat initial */
        $contrat = new Contrat();

        $avenant                = new Contrat();
        $avenant->id            = 1;
        $avenant->edite         = true;
        $avenant->parent        = $contrat;
        $avenant->numeroAvenant = 1;
        $avenant->avenants      = [];

        $contrat->avenants[] = $avenant;

        $volumeHoraire            = new VolumeHoraire();
        $volumeHoraire->serviceId = 1;
        $contrat->volumesHoraires = [$volumeHoraire];

        $this->process->calculTypeService($avenant);
        $this->assertEquals(false, $avenant->isMission);

    }
}
