<?php declare(strict_types=1);

namespace TblContrat;

use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;
use tests\TblContrat\TblContratTestCase;

final class CalculAutreLibellesTest extends TblContratTestCase
{
    public function testParametresAutreLibellesMission()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_DESACTIVE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);


        /* Jeu de données initial */
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = true;
        $contrat1->parent    = null;
        // Création des objets VolumeHoraire
        $volumeHoraire1               = new VolumeHoraire();
        $volumeHoraire1->autreLibelle = 'test2';

        $volumeHoraire2               = new VolumeHoraire();
        $volumeHoraire2->autreLibelle = 'test1';


        $volumeHoraire3               = new VolumeHoraire();
        $volumeHoraire3->autreLibelle = 'test2';

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3];


        $this->process->calculAutreLibelles($contrat1);


        self::assertEquals('test1, test2', $contrat1->autreLibelles);

    }



    public function testParametresAutreLibellesEnseignement()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_DESACTIVE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_COMPOSANTE,
        ];
        $this->useParametres($parametres);


        /* Jeu de données initial */
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->edite     = true;
        $contrat1->isMission = false;
        $contrat1->parent    = null;
        // Création des objets VolumeHoraire
        $volumeHoraire1               = new VolumeHoraire();
        $volumeHoraire1->autreLibelle = 'test2';

        $volumeHoraire2               = new VolumeHoraire();
        $volumeHoraire2->autreLibelle = null;


        $volumeHoraire3               = new VolumeHoraire();
        $volumeHoraire3->autreLibelle = 'test1';

        $volumeHoraire4               = new VolumeHoraire();
        $volumeHoraire4->autreLibelle = 'test2';

        $volumeHoraire5               = new VolumeHoraire();
        $volumeHoraire5->autreLibelle = null;
        $contrat1->volumesHoraires    = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3, $volumeHoraire4, $volumeHoraire5];


        $this->process->calculAutreLibelles($contrat1);


        self::assertEquals('test1, test2', $contrat1->autreLibelles);


    }
}
