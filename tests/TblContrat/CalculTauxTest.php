<?php declare(strict_types=1);

namespace tests\TblContrat;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Parametre;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;
use DateTime;

final class CalculTauxTest extends TblContratTestCase
{
    public function testTauxContratEnseignement()
    {
        $parametres = [
            Parametre::AVENANT           => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS       => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS       => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES => 0.3,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->isMission = false;
        $contrat1->annee     = new Annee();
        $contrat1->annee->setDateDebut(new DateTime('2022-09-01'));
        $contrat1->annee->setActive(true);


        $volumeHoraire1                  = new VolumeHoraire();
        $volumeHoraire1->structureId     = 495;
        $volumeHoraire1->serviceId       = 316066;
        $volumeHoraire1->volumeHoraireId = 748185;
        $volumeHoraire1->cm              = 1;
        $volumeHoraire1->heures          = 1;
        $volumeHoraire1->hetd            = 1.5;
        $volumeHoraire1->tauxRemuId      = 1;

        $volumeHoraire2                  = new VolumeHoraire();
        $volumeHoraire2->structureId     = 495;
        $volumeHoraire2->serviceId       = 316066;
        $volumeHoraire2->volumeHoraireId = 748186;
        $volumeHoraire2->tp              = 3;
        $volumeHoraire2->heures          = 3;
        $volumeHoraire2->hetd            = 2;
        $volumeHoraire2->tauxRemuId      = 1;

        $volumeHoraire3                  = new VolumeHoraire();
        $volumeHoraire3->structureId     = 495;
        $volumeHoraire3->serviceId       = 316067;
        $volumeHoraire3->volumeHoraireId = 748187;
        $volumeHoraire3->cm              = 2;
        $volumeHoraire3->heures          = 2;
        $volumeHoraire3->hetd            = 3;
        $volumeHoraire3->tauxRemuId      = 1;

        $volumeHoraire4                  = new VolumeHoraire();
        $volumeHoraire4->structureId     = 495;
        $volumeHoraire4->serviceId       = 316068;
        $volumeHoraire4->volumeHoraireId = 748189;
        $volumeHoraire4->cm              = 1;
        $volumeHoraire4->heures          = 1;
        $volumeHoraire4->hetd            = 1.5;
        $volumeHoraire4->tauxRemuId      = 1;

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3, $volumeHoraire4];

        try {
            $this->process->calculTauxRemu($contrat1);
        } catch (\Exception $e) {
            self::fail('Une exception ne devait pas être levée : ' . $e->getMessage());
        }

        self::assertEquals(new DateTime('2022-07-01'), $contrat1->tauxRemuDate);
        self::assertEquals(42.86, $contrat1->tauxRemuValeur);
        self::assertEquals(42.86, $contrat1->tauxRemuMajoreValeur);
    }



    public function testTauxContratErreurAnnee()
    {
        $parametres = [
            Parametre::AVENANT           => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS       => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS       => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES => 0.3,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->isMission = false;


        $volumeHoraire1                  = new VolumeHoraire();
        $volumeHoraire1->structureId     = 495;
        $volumeHoraire1->serviceId       = 316066;
        $volumeHoraire1->volumeHoraireId = 748185;
        $volumeHoraire1->cm              = 1;
        $volumeHoraire1->heures          = 1;
        $volumeHoraire1->hetd            = 1.5;
        $volumeHoraire1->tauxRemuId      = 1;

        $contrat1->volumesHoraires = [$volumeHoraire1];

        try {
            $this->process->calculTauxRemu($contrat1);
        } catch (\Exception $e) {
            //On doit renvoyé une erreur si l'année n'existe pas, ce test est la pour verifier que c'est bien le cas
            self::assertTrue(true);
            return;
        }
        self::fail();

    }



    public function testTauxContratErreurTauxDate()
    {
        $parametres = [
            Parametre::AVENANT           => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS       => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS       => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_CONGES_PAYES => 0.3,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->isMission = false;
        $contrat1->annee     = new Annee();
        $contrat1->annee->setDateDebut(new DateTime('2008-09-01'));
        $contrat1->annee->setActive(true);


        $volumeHoraire1                  = new VolumeHoraire();
        $volumeHoraire1->structureId     = 495;
        $volumeHoraire1->serviceId       = 316066;
        $volumeHoraire1->volumeHoraireId = 748185;
        $volumeHoraire1->cm              = 1;
        $volumeHoraire1->heures          = 1;
        $volumeHoraire1->hetd            = 1.5;
        $volumeHoraire1->tauxRemuId      = 1;

        $contrat1->volumesHoraires = [$volumeHoraire1];

        try {
            $this->process->calculTauxRemu($contrat1);
        } catch (\Exception $e) {
            //On ne doit pas renvoyé une erreur si le taux n'existe pas sur cette année, on doit renvoyer 1.0
            self::assertTrue(false);
        }

        self::assertEquals(1.0, $contrat1->tauxRemuValeur);
        self::assertEquals(1.0, $contrat1->tauxRemuMajoreValeur);
        self::assertEquals(new DateTime('2008-09-01'), $contrat1->tauxRemuDate);
    }



    public function testTauxContratMultiTaux()
    {
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_REMU   => 1,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->isMission = true;
        $contrat1->annee     = new Annee();
        $contrat1->annee->setDateDebut(new DateTime('2022-09-01'));
        $contrat1->annee->setActive(true);

        $volumeHoraire1             = new VolumeHoraire();
        $volumeHoraire1->tauxRemuId = 3;

        $volumeHoraire2             = new VolumeHoraire();
        $volumeHoraire2->tauxRemuId = 2;

        $volumeHoraire3             = new VolumeHoraire();
        $volumeHoraire3->tauxRemuId = 4;

        $volumeHoraire4             = new VolumeHoraire();
        $volumeHoraire4->tauxRemuId = 3;

        $contrat1->volumesHoraires = [$volumeHoraire1, $volumeHoraire2, $volumeHoraire3, $volumeHoraire4];

        try {
            $this->process->calculTauxRemu($contrat1);
        } catch (\Exception $e) {
            self::fail('Une exception ne devait pas être levée : ' . $e->getMessage());
        }

        self::assertEquals(new DateTime('2022-07-01'), $contrat1->tauxRemuDate);
        self::assertEquals(1, $contrat1->tauxRemuId);
        self::assertEquals(42.86, $contrat1->tauxRemuValeur);
        self::assertEquals(42.86, $contrat1->tauxRemuMajoreValeur);
    }


    public function testTauxContratIndexe(){
        $parametres = [
            Parametre::AVENANT     => Parametre::AVENANT_AUTORISE,
            Parametre::CONTRAT_MIS => Parametre::CONTRAT_MIS_MISSION,
            Parametre::CONTRAT_ENS => Parametre::CONTRAT_ENS_GLOBAL,
            Parametre::TAUX_REMU   => 1,
        ];
        $this->useParametres($parametres);

        //Contrat 1 global
        $contrat1            = new Contrat();
        $contrat1->id        = 1;
        $contrat1->isMission = true;
        $contrat1->annee     = new Annee();
        $contrat1->annee->setDateDebut(new DateTime('2022-09-01'));
        $contrat1->annee->setActive(true);

        $volumeHoraire1             = new VolumeHoraire();
        $volumeHoraire1->tauxRemuId = 3;

        $contrat1->volumesHoraires = [$volumeHoraire1];

        try {
            $this->process->calculTauxRemu($contrat1);
        } catch (\Exception $e) {
            self::fail('Une exception ne devait pas être levée : ' . $e->getMessage());
        }

        self::assertEquals(new DateTime('2022-08-01'), $contrat1->tauxRemuDate);
        self::assertEquals(3, $contrat1->tauxRemuId);
        self::assertEquals(11.07, $contrat1->tauxRemuValeur);
        self::assertEquals(11.07, $contrat1->tauxRemuMajoreValeur);
    }

}
