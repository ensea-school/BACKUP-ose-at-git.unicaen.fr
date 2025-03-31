<?php declare(strict_types=1);

namespace TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;
use tests\TblContrat\TblContratTestCase;

final class ProlongationMissionTest extends TblContratTestCase
{


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
        $contrat1->id          = 1;
        $contrat1->edite       = true;
        $contrat1->isMission   = true;
        $contrat1->finValidite = new \DateTime('2020-02-20');

        $volumeHoraire1                 = new VolumeHoraire();
        $volumeHoraire1->missionId      = 18;
        $volumeHoraire1->dateFinMission = new \DateTime('2020-02-21');
        $contrat1->volumesHoraires      = [$volumeHoraire1];

        $this->process->contratProlongationMission([$contrat1]);

        $this->assertCount($contrat1->id, $contrat1->avenants[0]->parent->id);
        $this->assertEquals(1, $contrat1->avenants[0]->parent->id);

    }

    /* cas de test a dev :
        Parametre par mission
        contrat  1 éditer et signé sur 3 mission (1,2,3) (date fin 2020-02-20)
        un nouveau volume horaire sur mission 1 (donc avenant d'heure)
        une prolongation de date sur mission 3 : date fin 2020-02-24 (donc avenant de prolongation)

        resultat attendu : creation de deux avenant, un d'heure sur mission 1 et un de prolongation (avenant au contrat sans volume horaire)
    */

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

    resultat attendu : creation d'un seul avenant d'heure et de prolongation
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
    public function testCasComplique(): void
    {
    }
}
