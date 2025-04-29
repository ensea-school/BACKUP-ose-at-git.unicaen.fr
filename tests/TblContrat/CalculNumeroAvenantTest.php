<?php declare(strict_types=1);

namespace tests\TblContrat;

use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;

final class CalculNumeroAvenantTest extends TblContratTestCase
{
    /**
     * Test pour vérifier que pour un contrat global sans avenant existant,
     * le numéro d'aveant sera bien 1
     *
     * @return void
     */

    public function testNumeroAvenantContratGlobalSansAvenant(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Modification de contrat initial, avenant */
        $avenant            = new Contrat();
        $avenant->isMission = false;
        $avenant->edite     = false;
        $avenant->setParent($contrat);

        $this->process->calculNumeroAvenant($avenant);
        $this->assertEquals(1, $avenant->numeroAvenant, 'Numéro d\'avenant attendu n\'est pas égal à 1');

    }



    /**
     * Test d'un contrat ayant déjà des avenants, pour trouver le bon prochain numéro d'avenant
     *
     * @return void
     */

    public function testNumeroAvenantContratGlobalAvecAvenantExistant(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Avenant n°1 */
        $avenant1                = new Contrat();
        $avenant1->id            = 2;
        $avenant1->edite         = true;
        $avenant1->isMission     = false;
        $avenant1->numeroAvenant = 1;
        $avenant1->setParent($contrat);

        /* Avenant n°3 */
        $avenant2                = new Contrat();
        $avenant2->id            = 3;
        $avenant2->edite         = true;
        $avenant2->isMission     = false;
        $avenant2->numeroAvenant = 2;
        $avenant2->setParent($contrat);

        /* Création d'un 3ème avenant */
        $avenant3            = new Contrat();
        $avenant3->isMission = false;
        $avenant3->edite     = false;
        $avenant3->setParent($contrat);

        $this->process->calculNumeroAvenant($avenant3);
        $this->assertEquals(3, $avenant3->numeroAvenant, 'Dans le cadre d\'un troisième avenant le numéro d\'avenant attendu doit être égale à 3');

    }



    /* Contrat global initiale, avenant avec numéro 1 et 3 existant, numero 2 supprimer.
    Nouvelle avenant en place. Numéro attendu 4 */
    public function testNumeroAvenantContratGlobalAvecAvenantExistantEtSupprimé(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Avenant n°1 */
        $avenant1                = new Contrat();
        $avenant1->id            = 2;
        $avenant1->edite         = true;
        $avenant1->isMission     = false;
        $avenant1->numeroAvenant = 1;
        $avenant1->setParent($contrat);

        /* Avenant n°3 */
        $avenant2                = new Contrat();
        $avenant2->id            = 3;
        $avenant2->edite         = true;
        $avenant2->isMission     = false;
        $avenant2->numeroAvenant = 3;
        $avenant2->setParent($contrat);

        /* Création d'un 3ème avenant */
        $avenant3            = new Contrat();
        $avenant3->isMission = false;
        $avenant3->edite     = false;
        $avenant3->setParent($contrat);
        $contrat->avenants = [$avenant1, $avenant2, $avenant3];
        $this->process->calculNumeroAvenant($avenant1);
        $this->process->calculNumeroAvenant($avenant3);
        $this->process->calculNumeroAvenant($avenant2);
        $this->process->calculNumeroAvenant($contrat);
        $this->assertEquals(4, $avenant3->numeroAvenant);
        $this->assertEquals(1, $avenant1->numeroAvenant);
        $this->assertEquals(3, $avenant2->numeroAvenant);
        $this->assertEquals(0, $contrat->numeroAvenant);

    }



    public function testNumeroAvenantContratGlobalAvecDeuxAvenants(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Avenant n°1 */
        $avenant2            = new Contrat();
        $avenant2->isMission = false;
        $avenant2->setParent($contrat);

        /* Avenant n°3 */
        $avenant2            = new Contrat();
        $avenant2->isMission = false;
        $avenant2->setParent($contrat);

        /* Création d'un 3ème avenant */
        $avenant3            = new Contrat();
        $avenant3->isMission = false;
        $avenant3->edite     = false;
        $avenant3->setParent($contrat);
        $contrat->avenants = [$avenant2, $avenant3];
        $this->process->calculNumeroAvenant($avenant2);
        $this->process->calculNumeroAvenant($avenant3);
        $this->assertEquals(1, $avenant3->numeroAvenant);
        $this->assertEquals(1, $avenant2->numeroAvenant);
        $this->assertEquals(0, $contrat->numeroAvenant);

    }


    /*

 * On a 1 contrat et 2 avenants édités (1 et 2 en numero)
 * 3 contrat historisé
 * 1 projet non édité
 * 1 projet en devenir
 * On doit ignoré les contrat historisé et le projet non édité et en devenir et donc on obtient numero d'avenant a 3
 */
    public function testProjetEtAvenantEnDevenirEtAncienAvenantSupprimer(): void
    {
        $contrat1                = new Contrat();
        $contrat1->id            = 1247;
        $contrat1->structureId   = 8;
        $contrat1->debutValidite = new \Datetime('2024-10-07');
        $contrat1->finValidite   = new \Datetime('2024-12-20');
        $contrat1->edite         = true;

        $volumeHoraire1 = new VolumeHoraire();
        $volumeHoraire1->structureId        = 8;
        $volumeHoraire1->missionId          = 1186;
        $volumeHoraire1->tauxRemuId         = 3;
        $volumeHoraire1->dateFinMission     = new \Datetime('2025-06-30');
        $volumeHoraire1->autres             = 15;
        $volumeHoraire1->heures             = 15;
        $volumeHoraire1->hetd               = 15;

        $contrat1->volumesHoraires = [$volumeHoraire1];

        $avenant1                = new Contrat();
        $avenant1->id            = 1365;
        $avenant1->structureId   = 8;
        $avenant1->setParent($contrat1);
        $avenant1->numeroAvenant = 1;
        $avenant1->debutValidite = new \Datetime('2024-10-07');
        $avenant1->finValidite   = new \Datetime('2024-12-20');
        $avenant1->edite         = true;

        $volumeHoraire2 = new VolumeHoraire();
        $volumeHoraire2->structureId        = 8;
        $volumeHoraire2->missionId          = 1186;
        $volumeHoraire2->tauxRemuId         = 3;
        $volumeHoraire2->dateFinMission     = new \Datetime('2025-06-30');
        $volumeHoraire2->autres             = 15;
        $volumeHoraire2->heures             = 15;
        $volumeHoraire2->hetd               = 15;

        $avenant1->volumesHoraires = [$volumeHoraire2];

        $avenant2                = new Contrat();
        $avenant2->id            = 1419;
        $avenant2->structureId   = 8;
        $avenant2->setParent($contrat1);
        $avenant2->numeroAvenant = 2;
        $avenant2->debutValidite = new \Datetime('2024-10-07');
        $avenant2->finValidite   = new \Datetime('2024-12-20');
        $avenant2->edite         = true;

        $volumeHoraire3 = new VolumeHoraire();
        $volumeHoraire3->structureId        = 8;
        $volumeHoraire3->missionId          = 1186;
        $volumeHoraire3->tauxRemuId         = 3;
        $volumeHoraire3->dateFinMission     = new \Datetime('2025-06-30');
        $volumeHoraire3->autres             = 30;
        $volumeHoraire3->heures             = 30;
        $volumeHoraire3->hetd               = 30;

        $avenant2->volumesHoraires = [$volumeHoraire3];

        $avenant3                = new Contrat();
        $avenant3->id            = 1485;
        $avenant3->historise     = true;
        $avenant3->structureId   = 8;
        $avenant3->setParent($avenant2);
        $avenant3->numeroAvenant = 3;
        $avenant3->debutValidite = new \Datetime('2024-10-07');
        $avenant3->finValidite   = new \Datetime('2025-06-30');

        $avenant4                = new Contrat();
        $avenant4->id            = 1486;
        $avenant4->historise     = true;
        $avenant4->structureId   = 8;
        $avenant4->setParent($contrat1);
        $avenant4->numeroAvenant = 3;
        $avenant4->debutValidite = new \Datetime('2024-10-07');
        $avenant4->finValidite   = new \Datetime('2025-06-30');

        $avenant5                = new Contrat();
        $avenant5->id            = 1487;
        $avenant5->historise     = true;
        $avenant5->structureId   = 8;
        $avenant5->setParent($avenant2);
        $avenant5->numeroAvenant = 3;
        $avenant5->debutValidite = new \Datetime('2024-10-07');
        $avenant5->finValidite   = new \Datetime('2025-06-30');

        $avenant6                = new Contrat();
        $avenant6->id            = 1488;
        $avenant6->structureId   = 8;
        $avenant6->setParent($contrat1);
        $avenant6->numeroAvenant = 4;
        $avenant6->debutValidite = new \Datetime('2024-10-07');
        $avenant6->finValidite   = new \Datetime('2025-06-30');


// Volumes horaires orphelins...
        $avenant7 = new Contrat();
        $avenant7->parent = $contrat1;
        $contrat1->avenants[] = $avenant7;
        $volumeHoraire4 = new VolumeHoraire();
        $volumeHoraire4->structureId        = 8;
        $volumeHoraire4->missionId          = 1186;
        $volumeHoraire4->tauxRemuId         = 3;
        $volumeHoraire4->dateFinMission     = new \Datetime('2025-06-30');
        $volumeHoraire4->autres             = 45;
        $volumeHoraire4->heures             = 45;
        $volumeHoraire4->hetd               = 45;

        $volumeHoraire5 = new VolumeHoraire();
        $volumeHoraire5->structureId        = 8;
        $volumeHoraire5->missionId          = 1186;
        $volumeHoraire5->tauxRemuId         = 3;
        $volumeHoraire5->dateFinMission     = new \Datetime('2025-06-30');
        $volumeHoraire5->autres             = -45;
        $volumeHoraire5->heures             = -45;
        $volumeHoraire5->hetd               = -45;

        $volumeHoraire6 = new VolumeHoraire();
        $volumeHoraire6->structureId        = 8;
        $volumeHoraire6->missionId          = 1186;
        $volumeHoraire6->tauxRemuId         = 3;
        $volumeHoraire6->dateFinMission     = new \Datetime('2025-06-30');
        $volumeHoraire6->autres             = 45;
        $volumeHoraire6->heures             = 45;
        $volumeHoraire6->hetd               = 45;

        $volumeHoraire7 = new VolumeHoraire();
        $volumeHoraire7->structureId        = 8;
        $volumeHoraire7->missionId          = 1186;
        $volumeHoraire7->tauxRemuId         = 3;
        $volumeHoraire7->dateFinMission     = new \Datetime('2025-06-30');
        $volumeHoraire7->autres             = -45;
        $volumeHoraire7->heures             = -45;
        $volumeHoraire7->hetd               = -45;

        $avenant7->volumesHoraires = [$volumeHoraire4,$volumeHoraire5,$volumeHoraire6,$volumeHoraire7];

        $this->process->calculNumeroAvenant($avenant6);
        $this->process->calculNumeroAvenant($avenant7);
        $this->assertEquals(3, $avenant6->numeroAvenant);
        $this->assertEquals(3, $avenant7->numeroAvenant);
    }
}
