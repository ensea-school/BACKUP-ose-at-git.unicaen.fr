<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;
use DateTime;

/**
 *
 */
final class CalculProlongationMissionTest extends TblContratTestCase
{


    /**
     * @return void
     */
    public function testCasSimple(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_GLOBAL,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */
        $contrat1                = new Contrat();
        $contrat1->intervenantId = 1;
        $contrat1->id            = 1;
        $contrat1->edite         = true;
        $contrat1->isMission     = true;
        $contrat1->finValidite   = new DateTime('2020-02-20');

        $volumeHoraire1                 = new VolumeHoraire();
        $volumeHoraire1->missionId      = 18;
        $volumeHoraire1->dateFinMission = new DateTime('2020-02-21');
        $contrat1->volumesHoraires      = [$volumeHoraire1];

        $contrats = [$contrat1];
        $this->process->contratProlongationMission($contrats);

        self::assertCount(1, $contrat1->avenants);
        self::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);
    }

    /* cas de test a dev :
        Parametre par mission
        contrat  1 éditer et signé sur 3 mission (1,2,3) (date fin 2020-02-20)
        un nouveau volume horaire sur mission 1 date fin 2020-03-20 (donc avenant d'heure)
        une prolongation de date sur mission 3 : date fin 2020-02-24 (donc avenant de prolongation)

        resultat attendu : creation de deux avenant, un d'heure sur mission 1 et un de prolongation sur mission 3 (avenant au contrat sans volume horaire)
    */
    /**
     * @return void
     */
    public function testDoubleAvenantAvecHeureEtProlongationMission()
    {
        $contrat1                = new Contrat();
        $contrat1->intervenantId = 1;
        $contrat1->id            = 1;
        $contrat1->edite         = true;
        $contrat1->isMission     = true;
        $contrat1->finValidite   = new DateTime('2020-02-20');

        $volumeHoraireMission1                 = new VolumeHoraire();
        $volumeHoraireMission1->missionId      = 1;
        $volumeHoraireMission1->dateFinMission = new DateTime('2020-03-20');

        $volumeHoraireMission2                 = new VolumeHoraire();
        $volumeHoraireMission2->missionId      = 2;
        $volumeHoraireMission2->dateFinMission = new DateTime('2020-02-20');

        $volumeHoraireMission3                 = new VolumeHoraire();
        $volumeHoraireMission3->missionId      = 3;
        $volumeHoraireMission3->dateFinMission = new DateTime('2020-02-22');

        $contrat1->volumesHoraires = [$volumeHoraireMission1, $volumeHoraireMission2, $volumeHoraireMission3];


        $avenant                = new Contrat();
        $avenant->id            = 1;
        $avenant->edite         = false;
        $avenant->isMission     = true;
        $avenant->parent        = $contrat1;
        $avenant->finValidite   = new DateTime('2020-03-20');
        $avenant->intervenantId = 1;

        $volumeHoraire2                 = new VolumeHoraire();
        $volumeHoraire2->missionId      = 1;
        $volumeHoraire2->dateFinMission = new DateTime('2020-03-20');
        $avenant->volumesHoraires[]     = $volumeHoraire2;
        $contrat1->avenants             = [$avenant];

        $contrats = [$contrat1, $avenant];
        $this->process->contratProlongationMission($contrats);

        self::assertCount(2, $contrat1->avenants);
        self::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);
        self::assertEquals($contrat1->id, $contrat1->avenants[1]->parent->id);
        self::assertCount(0, $contrat1->avenants[1]->volumesHoraires);


    }



    /*
    Parametre par mission
    contrat  1 éditer et signé sur 1 mission (date fin 2020-02-20)
    un nouveau volume horaire sur mission 1 (donc avenant d'heure)
    une prolongation de date sur mission 1 : date fin 2020-02-22 (donc avenant de prolongation)

    resultat attendu : creation d'un seul avenant d'heure et de prolongation
    */
    public function testCasAvenantHeureEtProlongation(): void
    {
        $contrat1                = new Contrat();
        $contrat1->intervenantId = 1;
        $contrat1->id            = 1;
        $contrat1->edite         = true;
        $contrat1->signe         = true;
        $contrat1->isMission     = true;
        $contrat1->finValidite   = new DateTime('2020-02-23');

        $volumeHoraireMission1                 = new VolumeHoraire();
        $volumeHoraireMission1->missionId      = 1;
        $volumeHoraireMission1->dateFinMission = new DateTime('2020-03-23');
        $contrat1->volumesHoraires             = [$volumeHoraireMission1];


        $avenant                = new Contrat();
        $avenant->id            = null;
        $avenant->edite         = false;
        $avenant->isMission     = true;
        $avenant->parent        = $contrat1;
        $avenant->finValidite   = new DateTime('2020-03-20');
        $avenant->intervenantId = 1;

        $volumeHoraire2                 = new VolumeHoraire();
        $volumeHoraire2->missionId      = 1;
        $volumeHoraire2->dateFinMission = new DateTime('2020-03-23');
        $avenant->volumesHoraires[]     = $volumeHoraire2;
        $contrat1->avenants             = [$avenant];

        $contrats = [$contrat1, $avenant];
        $this->process->contratProlongationMission($contrats);

        self::assertCount(1, $contrat1->avenants);
        self::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);
        self::assertEquals($avenant->finValidite, $volumeHoraire2->dateFinMission);
        self::assertCount(1, $avenant->volumesHoraires);
    }



    /*
    Parametre par mission
    contrat  1 éditer et signé sur 3 mission (1,2,3)
    un nouveau volume horaire sur mission 3 avec prolongation (donc avenant d'heure)
    une prolongation de date sur mission 3 (donc avenant de prolongation)

    resultat attendu : creation d'un seul avenant d'heure et de prolongation
    */
    public function testCasComplique(): void
    {
        $contrat1                = new Contrat();
        $contrat1->intervenantId = 1;
        $contrat1->id            = 1;
        $contrat1->edite         = true;
        $contrat1->signe         = true;
        $contrat1->isMission     = true;
        $contrat1->finValidite   = new DateTime('2020-03-20');

        $volumeHoraireMission1                 = new VolumeHoraire();
        $volumeHoraireMission1->missionId      = 1;
        $volumeHoraireMission1->dateFinMission = new DateTime('2020-03-23');

        $volumeHoraireMission2                 = new VolumeHoraire();
        $volumeHoraireMission2->missionId      = 2;
        $volumeHoraireMission2->dateFinMission = new DateTime('2020-03-15');

        $volumeHoraireMission3                 = new VolumeHoraire();
        $volumeHoraireMission3->missionId      = 3;
        $volumeHoraireMission3->dateFinMission = new DateTime('2020-03-17');
        $contrat1->volumesHoraires             = [$volumeHoraireMission1, $volumeHoraireMission2, $volumeHoraireMission3];


        $avenant                = new Contrat();
        $avenant->id            = null;
        $avenant->edite         = false;
        $avenant->isMission     = true;
        $avenant->parent        = $contrat1;
        $avenant->finValidite   = new DateTime('2020-03-20');
        $avenant->intervenantId = 1;

        $volumeHoraireMission4                 = new VolumeHoraire();
        $volumeHoraireMission4->missionId      = 1;
        $volumeHoraireMission4->dateFinMission = new DateTime('2020-03-23');
        $avenant->volumesHoraires[]            = $volumeHoraireMission4;
        $contrat1->avenants                    = [$avenant];

        $contrats = [$contrat1, $avenant];
        $this->process->contratProlongationMission($contrats);

        self::assertCount(1, $contrat1->avenants);
        self::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);
        self::assertEquals($avenant->finValidite, $volumeHoraireMission4->dateFinMission);
        self::assertCount(1, $avenant->volumesHoraires);
    }



    /* cas de test a dev :
    Parametre par mission
    contrat  1 éditer et signé sur 3 mission (1,2) (date fin 2020-02-20)
    une prolongation de date sur mission 1 : date fin 2020-02-24
    une prolongation de date sur mission 2 : date fin 2020-02-23

    resultat attendu : Création de un avenant avec date fin 2020-02-24
    */
    public function testCasDoubleProlongation(): void
    {
        $contrat1                = new Contrat();
        $contrat1->intervenantId = 1;
        $contrat1->id            = 1;
        $contrat1->edite         = true;
        $contrat1->signe         = true;
        $contrat1->isMission     = true;
        $contrat1->finValidite   = new DateTime('2020-02-20');

        $volumeHoraireMission1                 = new VolumeHoraire();
        $volumeHoraireMission1->missionId      = 1;
        $volumeHoraireMission1->dateFinMission = new DateTime('2020-02-24');

        $volumeHoraireMission2                 = new VolumeHoraire();
        $volumeHoraireMission2->missionId      = 2;
        $volumeHoraireMission2->dateFinMission = new DateTime('2020-02-23');


        $contrat1->volumesHoraires = [$volumeHoraireMission1, $volumeHoraireMission2];

        $contrats = [$contrat1];
        $this->process->contratProlongationMission($contrats);

        self::assertCount(1, $contrat1->avenants);
        self::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);
        self::assertEquals(new DateTime('2020-02-24'), $contrat1->avenants[0]->finValidite);
        self::assertCount(0, $contrat1->avenants[0]->volumesHoraires);

    }



    /* cas de test a dev :
    Parametre par mission
    Contrat 1 sur mission 10 au 2020-02-19
    Contrat 2 sur mission 12 au 2020-02-20
    Prolongation sur Mission 10 au 2020-02-24
    Prolongation sur Mission 12 au 2020-02-26

    resultat attendu : Création de deux avenant de prolongation, un avenant au contrat 1 et un avenant au contrat 2
*/
    public function testDoubleContrat(): void
    {
        $contrat1                = new Contrat();
        $contrat1->intervenantId = 1;
        $contrat1->id            = 1;
        $contrat1->edite         = true;
        $contrat1->signe         = true;
        $contrat1->isMission     = true;
        $contrat1->finValidite   = new DateTime('2020-02-19');

        $volumeHoraireMission1                 = new VolumeHoraire();
        $volumeHoraireMission1->missionId      = 10;
        $volumeHoraireMission1->dateFinMission = new DateTime('2020-02-24');

        $contrat1->volumesHoraires = [$volumeHoraireMission1];

        $contrat2                = new Contrat();
        $contrat2->intervenantId = 1;
        $contrat2->id            = 2;
        $contrat2->edite         = true;
        $contrat2->signe         = true;
        $contrat2->isMission     = true;
        $contrat2->finValidite   = new DateTime('2020-02-20');

        $volumeHoraireMission2                 = new VolumeHoraire();
        $volumeHoraireMission2->missionId      = 12;
        $volumeHoraireMission2->dateFinMission = new DateTime('2020-02-26');

        $contrat2->volumesHoraires = [$volumeHoraireMission2];

        $contrats = [$contrat1, $contrat2];
        $this->process->contratProlongationMission($contrats);

        self::assertCount(1, $contrat1->avenants);
        self::assertCount(1, $contrat2->avenants);
        self::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);
        self::assertEquals($contrat2->id, $contrat2->avenants[0]->parent->id);
        self::assertEquals(new DateTime('2020-02-24'), $contrat1->avenants[0]->finValidite);
        self::assertEquals(new DateTime('2020-02-26'), $contrat2->avenants[0]->finValidite);
        self::assertCount(0, $contrat1->avenants[0]->volumesHoraires);
        self::assertCount(0, $contrat2->avenants[0]->volumesHoraires);

    }
}
