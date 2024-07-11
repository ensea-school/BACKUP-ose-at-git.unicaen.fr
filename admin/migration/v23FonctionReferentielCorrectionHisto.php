<?php


use Unicaen\BddAdmin\Bdd;
class v23FonctionReferentielCorrectionHisto extends AbstractMigration
{

    public function description(): string
    {
        return "Correction des histo_modification des fonctions referentielles";
    }



    public function utile(): bool
    {
        $bdd = $this->manager->getBdd();

        $res = $bdd->select('select count(*) C from fonction_referentiel WHERE histo_modification IS NULL');

        return $res[0]['C'] == 0;
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