<?php





class v23FonctioneReferentiel extends AbstractMigration
{

    public function description(): string
    {
        return "Migration OSE 22 vers OSE 23";
    }



    public function utile(): bool
    {
        return true;
        return $this->manager->hasNewColumn('FONCTION_REFERENTIEL', 'ANNEE_ID');
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
//        $c   = $this->manager->getOseAdmin()->getConsole();
//

        try {
            $bdd->exec("ALTER TABLE FONCTION_REFERENTIEL ADD(ANNEE_ID NUMBER)");
        } catch (\Exception $e) {
        }

        try {
            $bdd->exec("alter table FONCTION_REFERENTIEL modify HISTO_MODIFICATION null");
        } catch (\Exception $e) {
        }
        try {
            $bdd->exec("alter table FONCTION_REFERENTIEL modify HISTO_MODIFICATEUR_ID null");
        } catch (\Exception $e) {
        }

        $res = $bdd->select("
            SELECT
              *
            FROM
              FONCTION_REFERENTIEL fr
            ");

        var_dump($res);

        // ajouter toute les fonctions avec années
        for ($a = 2010; $a < 2100; $a++) {
            $toutesAnnees[] = $a;
        }

        //Migrer toute les données du service vers fonction avec année

        //supprimer les anciennes fonction qui n'ont pas d'année

    }
}