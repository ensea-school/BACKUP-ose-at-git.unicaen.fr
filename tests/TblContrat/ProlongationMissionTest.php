<?php declare(strict_types=1);

namespace TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;
use DateTime;
use tests\TblContrat\TblContratTestCase;

/**
 *
 */
final class ProlongationMissionTest extends TblContratTestCase
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
        $contrat1              = new Contrat();
        $contrat1->intervenantId = 1;
        $contrat1->id          = 1;
        $contrat1->edite       = true;
        $contrat1->isMission   = true;
        $contrat1->finValidite = new DateTime('2020-02-20');

        $volumeHoraire1                 = new VolumeHoraire();
        $volumeHoraire1->missionId      = 18;
        $volumeHoraire1->dateFinMission = new DateTime('2020-02-21');
        $contrat1->volumesHoraires      = [$volumeHoraire1];

        $this->process->contratProlongationMission([$contrat1]);

        ProlongationMissionTest::assertCount(1, $contrat1->avenants);
        ProlongationMissionTest::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);

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


        $this->process->contratProlongationMission([$contrat1, $avenant]);

        ProlongationMissionTest::assertCount(2, $contrat1->avenants);
        ProlongationMissionTest::assertEquals($contrat1->id, $contrat1->avenants[0]->parent->id);
        ProlongationMissionTest::assertEquals($contrat1->id, $contrat1->avenants[1]->parent->id);
        ProlongationMissionTest::assertCount(0, $contrat1->avenants[1]->volumesHoraires);


    }


    /* cas de test a dev :
    Parametre par mission
    contrat  1 éditer et signé sur 1 mission (date fin 2020-02-20)
    un nouveau volume horaire sur mission 1 (donc avenant d'heure)
    une prolongation de date sur mission 1 : date fin 2020-02-22 (donc avenant de prolongation)

    resultat attendu : creation d'un seul avenant d'heure et de prolongation
    */

    /* cas de test a dev :
    Parametre par mission
    contrat  1 éditer et signé sur 3 mission (1,2,3)
    un nouveau volume horaire sur mission 3 avec prolongation (donc avenant d'heure)
    une prolongation de date sur mission 1 (donc avenant de prolongation)

    resultat attendu : creation de deux avenants d'heure et de prolongation
    */

    /* cas de test a dev :
    Parametre par mission
    contrat  1 éditer et signé sur 3 mission (1,2,3) (date fin 2020-02-20)
    un nouveau volume horaire sur mission 3 avec prolongation : date fin 2020-02-24 (donc avenant d'heure)
    une prolongation de date sur mission 1 : date 2020-02-23 (donc avenant de prolongation)

    resultat attendu : Création de deux avenants, une pour prolongation de date et heure sur une mission, et un autre pour une prolongation de date uniquement
    */

    /* cas de test a dev :
    Parametre par mission
    contrat  1 éditer et signé sur 3 mission (1,2,3) (date fin 2020-02-20)
    une prolongation de date sur mission 3 : date fin 2020-02-24
    une prolongation de date sur mission 1 : date fin 2020-02-23

    resultat attendu : Création de un avenant avec date fin 2020-02-24
    */

    /* cas de test a dev :
    Parametre par mission
    Contrat 1 sur mission 10
    Contrat 2 sur mission 12
    Prolongation sur Mission 10
    Prolongation sur Mission 12

    resultat attendu : Création de deux avenant de prolongation, un avenant au contrat 1 et un avenant au contrat 2
    */
    /**
     * @return void
     */
    public function testCasComplique(): void
    {
    }
}
