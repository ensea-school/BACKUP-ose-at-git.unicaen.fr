<?php


namespace migration;

use AbstractMigration;

class v23FonctionReferentielCorrectionHisto extends AbstractMigration
{

    public function description(): string
    {
        return "Migration OSE 22 vers OSE 23";
    }



    public function utile(): bool
    {
        $bdd = $this->manager->getBdd();

        $res = $bdd->select('select count(*) c from fonction_referentiel WHERE histo_modification IS NULL');

        return $res[0] == 0 ? true : false;
    }



    public function after()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->console();

        $c->msg('Correction des histo_modification des fonctions référentielles en cours');
        $annee = $this->anneeCourante();
        $sql   = 'UPDATE fonction_referentiel fr
                        SET fr.histo_modification = null , fr.histo_modificateur_id = null 
                        WHERE fr.annee_id > :annee';
        $param = ['annee' => $annee];

        $bdd->exec($sql, $param);


        $c->msg('Correction des histo_modification des fonctions référentielles terminée');
    }



    private function anneeCourante()
    {
        $annee = date('Y');
        $mois  = date('m');
        if ($mois > 8) {
            return $annee;
        } else {
            return $annee - 1;
        }
    }
}