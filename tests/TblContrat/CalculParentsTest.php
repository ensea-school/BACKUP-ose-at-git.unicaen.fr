<?php declare(strict_types=1);

namespace tests\TblContrat;

use Administration\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;

final class CalculParentsTest extends TblContratTestCase
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
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = false;

        $contrat2            = new Contrat();
        $contrat2->isMission = false;

        $contrats = [$contrat1, $contrat2];
        $this->process->calculParentsIds($contrats);

        $this->assertNotNull($contrat2->parent);
        $this->assertEquals(1, $contrat2->parent->id);

    }



    public function testCasComplique(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */
        //Contrat 1 global
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->uuid        = 'contrat_principal';
        $contrat1->isMission   = true;
        $contrat1->structureId = null;
        $contrat1->edite       = true;
        $contrat1->avenants    = [];
        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 1;
        $volumeHoraire1->structureId = 1;
        $volumeHoraire2              = new VolumeHoraire();
        $volumeHoraire2->missionId   = 1;
        $volumeHoraire2->structureId = 1;
        $volumeHoraire3              = new VolumeHoraire();
        $volumeHoraire3->missionId   = 1;
        $volumeHoraire3->structureId = 2;
        // Ajout des objets dans l'array volumesHoraires

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3];

        //Contrat 2 par composante
        $contrat2            = new Contrat();
        $contrat2->id        = 2;
        $contrat2->uuid      = 'avenant_edite';
        $contrat2->isMission = true;
        $contrat2->setParent($contrat1);
        $contrat2->structureId = 1;
        $contrat2->avenants    = [];

        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 1;
        $volumeHoraire1->structureId = 1;

        // Ajout des objets dans l'array volumesHoraires
        $contrat2->volumesHoraires = [$volumeHoraire1];


        //COntrat 3 par composante sur structure 2
        $contrat3              = new Contrat();
        $contrat3->id          = null;
        $contrat1->uuid        = 'avenant_a_calculer';
        $contrat3->isMission   = true;
        $contrat3->parent      = null;
        $contrat3->structureId = 2;
        $contrat3->avenants    = [];

        // Création des objets VolumeHoraire
        $volumeHoraire1            = new VolumeHoraire();
        $volumeHoraire1->missionId = 1;

        // Ajout des objets dans l'array volumesHoraires
        $contrat3->volumesHoraires = [$volumeHoraire1];

        $contrats = [$contrat1, $contrat2, $contrat3];
        $this->process->calculParentsIds($contrats);


        /* Verification des calculs */

        $this->assertCount(2, $contrat1->avenants);
        $uuids = [];
        foreach ($contrat1->avenants as $avenant) {
            $uuids[] = $avenant->uuid;
        }
        $uuidsExpected = [$contrat2->uuid, $contrat3->uuid];
        $this->assertArrayEquals($uuidsExpected, $uuids);
        CalculParentsTest::assertNotNull($contrat2->parent);
        CalculParentsTest::assertNotNull($contrat3->parent);
        CalculParentsTest::assertEquals($contrat1->id, $contrat2->parent->id);
        CalculParentsTest::assertEquals($contrat1->id, $contrat3->parent->id);

    }



    public function testMissionComposante(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_COMPOSANTE,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */
        //deux contrats anciennement créer sous un parametrage contrat_mis_mission

        // Création du contrat 1
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->isMission   = true;
        $contrat1->structureId = 3;
        $contrat1->edite       = true;
        $contrat1->avenants    = [];

        // Création des objets VolumeHoraire pour contrat1
        $volumeHoraire1            = new VolumeHoraire();
        $volumeHoraire1->missionId = 18;
        $contrat1->volumesHoraires = [$volumeHoraire1];

        // Création du contrat 2
        $contrat2              = new Contrat();
        $contrat2->id          = 2;
        $contrat2->isMission   = true;
        $contrat2->parent      = null;
        $contrat2->structureId = 3;
        $contrat2->edite       = true;
        $contrat2->avenants    = [];

        // Création des objets VolumeHoraire pour contrat2
        $volumeHoraire2            = new VolumeHoraire();
        $volumeHoraire2->missionId = 1;
        $contrat2->volumesHoraires = [$volumeHoraire2];

        // Création du contrat 3 (nouveau contrat après changement de paramétrage)
        $contrat3              = new Contrat();
        $contrat3->id          = null;
        $contrat3->isMission   = true;
        $contrat3->parent      = null;
        $contrat3->structureId = 3;
        $contrat3->avenants    = [];

        // Création des objets VolumeHoraire pour contrat3
        $volumeHoraire3            = new VolumeHoraire();
        $volumeHoraire3->missionId = 1;
        $contrat3->volumesHoraires = [$volumeHoraire3];

        $contrats = [$contrat1, $contrat2, $contrat3];
        $this->process->calculParentsIds($contrats);

        //Verification des données
        $this->assertCount(0, $contrat1->avenants);
        $this->assertCount(1, $contrat2->avenants);
        $this->assertCount(0, $contrat3->avenants);
        $uuids = [];
        foreach ($contrat2->avenants as $avenant) {
            $uuids[] = $avenant->uuid;
        }
        $uuidsExpected = [$contrat3->uuid];
        $this->assertArrayEquals($uuidsExpected, $uuids);
        $this->assertNull($contrat1->parent);
        $this->assertNull($contrat2->parent);
        $this->assertNotNull($contrat3->parent);
        $this->assertEquals($contrat2->id, $contrat3->parent->id);
    }



    public function testMissionComposanteContratEtProjet(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_COMPOSANTE,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */

        //deux contrats anciennement créer sous un parametrage contrat_mis_mission, l'un est un projet l'autre non
        // Création du contrat 1
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->isMission   = true;
        $contrat1->structureId = 3;
        $contrat1->edite       = true;
        $contrat1->avenants    = [];

        // Création des objets VolumeHoraire pour contrat1
        $volumeHoraire1            = new VolumeHoraire();
        $volumeHoraire1->missionId = 18;
        $contrat1->volumesHoraires = [$volumeHoraire1];

        // Création du contrat 2
        $contrat2              = new Contrat();
        $contrat2->id          = 2;
        $contrat2->isMission   = true;
        $contrat2->parent      = null;
        $contrat2->structureId = 3;
        $contrat2->edite       = false;
        $contrat2->avenants    = [];

        // Création des objets VolumeHoraire pour contrat2
        $volumeHoraire2            = new VolumeHoraire();
        $volumeHoraire2->missionId = 1;
        $contrat2->volumesHoraires = [$volumeHoraire2];

        // Création du contrat 3 (nouveau contrat après changement de paramétrage)
        $contrat3              = new Contrat();
        $contrat3->id          = null;
        $contrat3->isMission   = true;
        $contrat3->parent      = null;
        $contrat3->structureId = 3;
        $contrat3->avenants    = [];

        // Création des objets VolumeHoraire pour contrat3
        $volumeHoraire3            = new VolumeHoraire();
        $volumeHoraire3->missionId = 1;
        $contrat3->volumesHoraires = [$volumeHoraire3];

        $contrats = [$contrat1, $contrat2, $contrat3];
        $this->process->calculParentsIds($contrats);

        //Verification des données
        $this->assertCount(2, $contrat1->avenants);
        $this->assertCount(0, $contrat2->avenants);
        $this->assertCount(0, $contrat3->avenants);
        $uuids = [];
        foreach ($contrat1->avenants as $avenant) {
            $uuids[] = $avenant->uuid;
        }
        $uuidsExpected = [$contrat2->uuid, $contrat3->uuid];
        $this->assertArrayEquals($uuidsExpected, $uuids);
        $this->assertNull($contrat1->parent);
        $this->assertNotNull($contrat2->parent);
        $this->assertNotNull($contrat3->parent);
        $this->assertEquals($contrat1->id, $contrat2->parent->id);
        $this->assertEquals($contrat1->id, $contrat3->parent->id);

    }



    public function testContratGlobalParamParMission(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */

        //Un contrat global créer precedemment, deux nouveau contrat créer en param_mis_mission
        // Création du contrat 1
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->isMission   = true;
        $contrat1->structureId = null;
        $contrat1->edite       = true;
        $contrat1->avenants    = [];

        // Création des objets VolumeHoraire pour contrat1
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 1;
        $volumeHoraire1->structureId = 4;

        $volumeHoraire2              = new VolumeHoraire();
        $volumeHoraire2->missionId   = 2;
        $volumeHoraire2->structureId = 4;

        $volumeHoraire3              = new VolumeHoraire();
        $volumeHoraire3->missionId   = 3;
        $volumeHoraire3->structureId = 4;

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3];

        // Création du contrat 2
        $contrat2              = new Contrat();
        $contrat2->id          = null;
        $contrat2->isMission   = true;
        $contrat2->parent      = null;
        $contrat2->structureId = 3;
        $contrat2->avenants    = [];

        // Création de l'objet VolumeHoraire pour contrat2
        $volumeHoraire4              = new VolumeHoraire();
        $volumeHoraire4->missionId   = 5;
        $volumeHoraire4->structureId = 6;
        $contrat2->volumesHoraires   = [$volumeHoraire4];

        // Création du contrat 3 (nouveau contrat après changement de paramétrage)
        $contrat3              = new Contrat();
        $contrat3->id          = null;
        $contrat3->isMission   = true;
        $contrat3->parent      = null;
        $contrat3->structureId = 3;
        $contrat3->avenants    = [];

        // Création de l'objet VolumeHoraire pour contrat3
        $volumeHoraire5              = new VolumeHoraire();
        $volumeHoraire5->missionId   = 1;
        $volumeHoraire5->structureId = 4;
        $contrat3->volumesHoraires   = [$volumeHoraire5];

        $contrats = [$contrat1, $contrat2, $contrat3];
        $this->process->calculParentsIds($contrats);

        //Verification des données
        //Verification des données
        $this->assertCount(1, $contrat1->avenants);
        $this->assertCount(0, $contrat2->avenants);
        $this->assertCount(0, $contrat3->avenants);
        $uuids = [];
        foreach ($contrat1->avenants as $avenant) {
            $uuids[] = $avenant->uuid;
        }
        $uuidsExpected = [$contrat3->uuid];
        $this->assertArrayEquals($uuidsExpected, $uuids);
        $this->assertNull($contrat1->parent);
        $this->assertNull($contrat2->parent);
        $this->assertNotNull($contrat3->parent);
        $this->assertEquals($contrat1->id, $contrat3->parent->id);

    }



    public function testContratGlobalEtNouveauContratComplique(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */
        //Contrat 1 global
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->uuid        = 'contrat_principal';
        $contrat1->isMission   = true;
        $contrat1->structureId = null;
        $contrat1->edite       = true;
        $contrat1->avenants    = [];
        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 1;
        $volumeHoraire1->structureId = 1;
        $volumeHoraire2              = new VolumeHoraire();
        $volumeHoraire2->missionId   = 1;
        $volumeHoraire2->structureId = 1;
        $volumeHoraire3              = new VolumeHoraire();
        $volumeHoraire3->missionId   = 1;
        $volumeHoraire3->structureId = 2;
        // Ajout des objets dans l'array volumesHoraires

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3];

        //Contrat 2 par mission
        $contrat2              = new Contrat();
        $contrat2->id          = 2;
        $contrat2->uuid        = 'avenant_edite';
        $contrat2->isMission   = true;
        $contrat2->parent      = &$contrat1;
        $contrat2->structureId = 1;
        $contrat2->avenants    = [];

        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 1;
        $volumeHoraire1->structureId = 1;

        // Ajout des objets dans l'array volumesHoraires
        $contrat2->volumesHoraires = [$volumeHoraire1];


        $contrat1->avenants[] = &$contrat2;

        //COntrat 3 par mission
        $contrat3              = new Contrat();
        $contrat3->id          = null;
        $contrat1->uuid        = 'avenant_a_calculer';
        $contrat3->isMission   = true;
        $contrat3->parent      = null;
        $contrat3->structureId = 3;
        $contrat3->avenants    = [];

        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 4;
        $volumeHoraire1->structureId = 3;

        // Ajout des objets dans l'array volumesHoraires
        $contrat3->volumesHoraires = [$volumeHoraire1];

        $contrats = [$contrat1, $contrat2, $contrat3];
        $this->process->calculParentsIds($contrats);


        /* Verification des calculs */

        $this->assertCount(1, $contrat1->avenants);
        $this->assertCount(0, $contrat2->avenants);
        $this->assertCount(0, $contrat3->avenants);
        $uuids = [];
        foreach ($contrat1->avenants as $avenant) {
            $uuids[] = $avenant->uuid;
        }
        $uuidsExpected = [$contrat2->uuid];
        $this->assertArrayEquals($uuidsExpected, $uuids);
        $this->assertNotNull($contrat2->parent);
        $this->assertNull($contrat3->parent);
        $this->assertNull($contrat3->parent?->id);
    }



    public function testDeuxParentsPotentielsPrendreDernierProjetCreer(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_COMPOSANTE,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
        ];
        $this->useParametres($parametres);

        /* Jeu de données initial */
        //Contrat 1 par mission
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->uuid        = 'contrat_principal';
        $contrat1->isMission   = true;
        $contrat1->structureId = 10;
        $contrat1->edite       = true;
        $contrat1->avenants    = [];
        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 1;
        $volumeHoraire1->structureId = 10;
        // Ajout des objets dans l'array volumesHoraires

        $contrat1->volumesHoraires = [$volumeHoraire1];

        //Contrat 2 par mission
        $contrat2              = new Contrat();
        $contrat2->id          = 2;
        $contrat2->uuid        = 'avenant_edite';
        $contrat2->isMission   = true;
        $contrat2->edite       = true;
        $contrat2->parent      = null;
        $contrat2->structureId = 10;
        $contrat2->avenants    = [];

        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 2;
        $volumeHoraire1->structureId = 10;

        // Ajout des objets dans l'array volumesHoraires
        $contrat2->volumesHoraires = [$volumeHoraire1];

        //COntrat 3 par composante
        $contrat3              = new Contrat();
        $contrat3->id          = null;
        $contrat3->uuid        = 'avenant_a_calculer';
        $contrat3->isMission   = true;
        $contrat3->parent      = null;
        $contrat3->structureId = 10;
        $contrat3->avenants    = [];

        // Création des objets VolumeHoraire
        $volumeHoraire1              = new VolumeHoraire();
        $volumeHoraire1->missionId   = 1;
        $volumeHoraire1->structureId = 10;

        // Ajout des objets dans l'array volumesHoraires
        $contrat3->volumesHoraires = [$volumeHoraire1];

        $contrats = [$contrat1, $contrat2, $contrat3];
        $this->process->calculParentsIds($contrats);


        /* Verification des calculs */

        $this->assertCount(0, $contrat1->avenants);
        $this->assertCount(1, $contrat2->avenants);
        $this->assertCount(0, $contrat3->avenants);
        $uuids = [];
        foreach ($contrat2->avenants as $avenant) {
            $uuids[] = $avenant->uuid;
        }
        $uuidsExpected = [$contrat3->uuid];
        $this->assertArrayEquals($uuidsExpected, $uuids);
        $this->assertNull($contrat1->parent);
        $this->assertNull($contrat2->parent);
        $this->assertNotNull($contrat3->parent);
        $this->assertEquals($contrat2->id, $contrat3->parent?->id);

    }



    public function testAvenantSurProjets()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        // on a un contrat sur une structure et on veut créer un second contrat sur une autre structure,
        // Lorsqu'on a deux projets, ce sont 2 projets de contrat

        $contrat1              = new Contrat();
        $contrat1->id          = 39940;
        $contrat1->structureId = 495;
        $contrat1->totalHetd   = 8;

        $volumeHoraire1                  = new VolumeHoraire();
        $volumeHoraire1->structureId     = 495;
        $volumeHoraire1->serviceId       = 316066;
        $volumeHoraire1->volumeHoraireId = 748185;
        $volumeHoraire1->cm              = 1;
        $volumeHoraire1->heures          = 1;
        $volumeHoraire1->hetd            = 1.5;

        $volumeHoraire2                  = new VolumeHoraire();
        $volumeHoraire2->structureId     = 495;
        $volumeHoraire2->serviceId       = 316066;
        $volumeHoraire2->volumeHoraireId = 748186;
        $volumeHoraire2->tp              = 3;
        $volumeHoraire2->heures          = 3;
        $volumeHoraire2->hetd            = 2;

        $volumeHoraire3                  = new VolumeHoraire();
        $volumeHoraire3->structureId     = 495;
        $volumeHoraire3->serviceId       = 316067;
        $volumeHoraire3->volumeHoraireId = 748187;
        $volumeHoraire3->cm              = 2;
        $volumeHoraire3->heures          = 2;
        $volumeHoraire3->hetd            = 3;

        $volumeHoraire4                  = new VolumeHoraire();
        $volumeHoraire4->structureId     = 495;
        $volumeHoraire4->serviceId       = 316068;
        $volumeHoraire4->volumeHoraireId = 748189;
        $volumeHoraire4->cm              = 1;
        $volumeHoraire4->heures          = 1;
        $volumeHoraire4->hetd            = 1.5;

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3, $volumeHoraire4];


        $contrat2              = new Contrat();
        $contrat2->structureId = 594;

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

        $contrats = [$contrat1, $contrat2];
        $this->process->calculParentsIds($contrats);


        self::assertNull($contrat2->parent);
        self::assertNull($contrat1->parent);
    }



    public function testProjetAvenantSurContrat()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        // on a un contrat sur une structure et on veut créer un second contrat sur une autre structure,
        // Lorsqu'on a 1 contrat, le nouveau projet est un avenant

        $contrat1              = new Contrat();
        $contrat1->id          = 39940;
        $contrat1->edite       = true;
        $contrat1->structureId = 495;
        $contrat1->totalHetd   = 8;

        $volumeHoraire1                  = new VolumeHoraire();
        $volumeHoraire1->structureId     = 495;
        $volumeHoraire1->serviceId       = 316066;
        $volumeHoraire1->volumeHoraireId = 748185;
        $volumeHoraire1->cm              = 1;
        $volumeHoraire1->heures          = 1;
        $volumeHoraire1->hetd            = 1.5;

        $volumeHoraire2                  = new VolumeHoraire();
        $volumeHoraire2->structureId     = 495;
        $volumeHoraire2->serviceId       = 316066;
        $volumeHoraire2->volumeHoraireId = 748186;
        $volumeHoraire2->tp              = 3;
        $volumeHoraire2->heures          = 3;
        $volumeHoraire2->hetd            = 2;

        $volumeHoraire3                  = new VolumeHoraire();
        $volumeHoraire3->structureId     = 495;
        $volumeHoraire3->serviceId       = 316067;
        $volumeHoraire3->volumeHoraireId = 748187;
        $volumeHoraire3->cm              = 2;
        $volumeHoraire3->heures          = 2;
        $volumeHoraire3->hetd            = 3;

        $volumeHoraire4                  = new VolumeHoraire();
        $volumeHoraire4->structureId     = 495;
        $volumeHoraire4->serviceId       = 316068;
        $volumeHoraire4->volumeHoraireId = 748189;
        $volumeHoraire4->cm              = 1;
        $volumeHoraire4->heures          = 1;
        $volumeHoraire4->hetd            = 1.5;

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3, $volumeHoraire4];


        $contrat2              = new Contrat();
        $contrat2->structureId = 594;

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

        $contrats = [$contrat1, $contrat2];
        $this->process->calculParentsIds($contrats);


        self::assertEquals($contrat1, $contrat2->parent);
        self::assertNull($contrat1->parent);
    }



    public function testIsParentPotentiel(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        // on a un contrat sur une structure et on veut créer un second contrat sur une autre structure,
        // Lorsqu'on a 1 contrat, le nouveau projet est un avenant

        $contrat1              = new Contrat();
        $contrat1->id          = 39940;
        $contrat1->edite       = true;
        $contrat1->structureId = 495;


        $contrat2              = new Contrat();
        $contrat2->structureId = 594;

        $contrat3              = new Contrat();
        $contrat3->id          = 39940;
        $contrat3->edite       = true;
        $contrat3->structureId = null;

        $contrat4              = new Contrat();
        $contrat4->id          = 39940;
        $contrat4->edite       = false;
        $contrat4->structureId = 495;
        try {
            $res1 = $this->process->isParentPotentiel($contrat2, $contrat1);
            $res2 = $this->process->isParentPotentiel($contrat2, $contrat2);
            $res3 = $this->process->isParentPotentiel($contrat2, $contrat3);
            $res4 = $this->process->isParentPotentiel($contrat2, $contrat4);
        } catch (\Exception $e) {
            //Cela ne devrait pas planté ici
            self::fail();
        }


        self::assertTrue($res1);
        self::assertFalse($res2);
        self::assertTrue($res3);
        self::assertFalse($res4);
    }



    public function testIsParentPotentielEnseignementError(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        // on a un contrat sur une structure et on veut créer un second contrat sur une autre structure,
        // Lorsqu'on a 1 contrat, le nouveau projet est un avenant

        $contrat1              = new Contrat();
        $contrat1->id          = 39940;
        $contrat1->edite       = true;
        $contrat1->structureId = 495;
        $contrat1->isMission   = false;


        $contrat2            = new Contrat();
        $contrat2->isMission = false;

        try {
            $res1 = $this->process->isParentPotentiel($contrat2, $contrat1);
        } catch (\Exception $e) {
            //L'erreur est normal, pas de structure en mode par composante
            self::assertTrue(true);
            return;
        }
        self::fail();
    }



    public function testIsParentPotentielMissionError(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        // on a un contrat sur une structure et on veut créer un second contrat sur une autre structure,
        // Lorsqu'on a 1 contrat, le nouveau projet est un avenant

        $contrat1              = new Contrat();
        $contrat1->id          = 39940;
        $contrat1->edite       = true;
        $contrat1->structureId = 495;
        $contrat1->isMission   = true;


        $contrat2            = new Contrat();
        $contrat2->isMission = true;


        try {
            $res1 = $this->process->isParentPotentiel($contrat2, $contrat1);
        } catch (\Exception $e) {
            //L'erreur est normal, pas de structure en mode par composante
            self::assertTrue(true);
            return;
        }
        self::fail();

    }



    public function testIsParentPotentielMissionComposanteError(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_COMPOSANTE,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        // on a un contrat sur une structure et on veut créer un second contrat sur une autre structure,
        // Lorsqu'on a 1 contrat, le nouveau projet est un avenant

        $contrat1              = new Contrat();
        $contrat1->id          = 39940;
        $contrat1->edite       = true;
        $contrat1->structureId = 495;
        $contrat1->isMission   = true;


        $contrat2            = new Contrat();
        $contrat2->isMission = true;


        try {
            $res1 = $this->process->isParentPotentiel($contrat2, $contrat1);
        } catch (\Exception $e) {
            //L'erreur est normal, pas de structure en mode par composante
            self::assertTrue(true);
            return;
        }
        self::fail();
    }



    public function testIsParentPotentielMission(): void
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_GLOBAL,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);

        // on a un contrat sur une structure et on veut créer un second contrat sur une autre structure,
        // Lorsqu'on a 1 contrat, le nouveau projet est un avenant

        $contrat1              = new Contrat();
        $contrat1->id          = 39940;
        $contrat1->edite       = true;
        $contrat1->structureId = 495;
        $contrat1->isMission   = true;


        $contrat2            = new Contrat();
        $contrat2->isMission = true;


        try {
            $res1 = $this->process->isParentPotentiel($contrat2, $contrat1);
        } catch (\Exception $e) {
            //en mode global pas d'erreur
            self::fail();
        }

        self::assertTrue($res1);

    }
}
