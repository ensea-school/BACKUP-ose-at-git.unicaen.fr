<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;

final class CalculParentsTest extends TblContratTestCase
{
    protected function assertContrats(array $contrats, array $expected): void
    {
        $contratsModels = [];
        foreach ($contrats as $contrat) {
            $contratsModels[] = $this->hydrateContrat($contrat);
        }
        $this->process->calculParentsIds($contratsModels);
        foreach ($contratsModels as $index => $model) {
            $contratsModels[$index] = $this->extractContrat($model);
        }
        $this->assertArrayEquals($expected, $contratsModels, false);
    }



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

        $this->process->calculParentsIds([$contrat1, $contrat2]);

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
        $contrat1              = new Contrat();
        $contrat1->id          = 1;
        $contrat1->uuid        = 'contrat_principal';
        $contrat1->isMission   = true;
        $contrat1->structureId = null;
        $contrat1->edite       = true;
        $contrat1->avenants    = [];
        // Création des objets VolumeHoraire
        $volumeHoraire1            = new VolumeHoraire();
        $volumeHoraire1->missionId = 1;
        $volumeHoraire2            = new VolumeHoraire();
        $volumeHoraire2->missionId = 1;
        $volumeHoraire3            = new VolumeHoraire();
        $volumeHoraire3->missionId = 1;
        // Ajout des objets dans l'array volumesHoraires

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3];


        $contrat2              = new Contrat();
        $contrat2->id          = 2;
        $contrat2->uuid        = 'avenant_edite';
        $contrat2->isMission   = true;
        $contrat2->parent      = &$contrat1;
        $contrat2->structureId = 1;
        $contrat2->avenants    = [];

        // Création des objets VolumeHoraire
        $volumeHoraire1            = new VolumeHoraire();
        $volumeHoraire1->missionId = 1;

        // Ajout des objets dans l'array volumesHoraires
        $contrat2->volumesHoraires = [$volumeHoraire1];


        $contrat1->avenants[] = &$contrat2;


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

        $this->process->calculParentsIds([$contrat1, $contrat2, $contrat3]);


        /* Verification des calculs */

        $this->assertCount(2, $contrat1->avenants);
        $uuids = [];
        foreach ($contrat1->avenants as $avenant) {
            $uuids[] = $avenant->uuid;
        }
        $uuidsExpected = [$contrat2->uuid, $contrat3->uuid];
        $this->assertArrayEquals($uuidsExpected, $uuids);
        $this->assertNotNull($contrat2->parent);
        $this->assertNotNull($contrat3->parent);
        $this->assertEquals($contrat1->id, $contrat2->parent->id);
        $this->assertEquals($contrat1->id, $contrat3->parent->id);

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


        $this->process->calculParentsIds([$contrat1, $contrat2, $contrat3]);

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


        $this->process->calculParentsIds([$contrat1, $contrat2, $contrat3]);

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


        $this->process->calculParentsIds([$contrat1, $contrat2, $contrat3]);

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
}
