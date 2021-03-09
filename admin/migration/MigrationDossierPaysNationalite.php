<?php


use BddAdmin\Bdd;

class MigrationDossierPaysNationalite extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "";
    }



    public function utile(): bool
    {

        if ($this->manager->hasColumn('INTERVENANT_DOSSIER', 'PAYS_NATIONALITE_ID')) {
            //on lance uniquement si on a des dossiers validé dont le pays nationalité est vide et le pays naissance est 'France'
            $sqlNationalite = "
            SELECT d.id FROM intervenant_dossier d
                JOIN intervenant i ON i.id = d.intervenant_id 
                JOIN validation v ON d.intervenant_id = v.intervenant_id AND type_validation_id = 1 AND v.histo_destruction IS NULL 
                WHERE d.pays_nationalite_id IS NULL
                AND d.histo_destruction  IS NULL
                AND d.pays_naissance_id = (SELECT id FROM PAYS WHERE ose_divers.str_reduce(libelle) = 'france'  AND HISTO_DESTRUCTION IS NULL)
            ";

            $result = $this->manager->getBdd()->select($sqlNationalite);

            if (count($result) > 0) {
                return true;
            }
        }

        return false;
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        } else {
            $this->after();
        }
    }



    protected function before()
    {

    }



    protected function after()
    {
        $oa      = $this->manager->getOseAdmin();
        $bdd     = $this->manager->getBdd();
        $console = $this->manager->getOseAdmin()->getConsole();

        $console->println("Remise en conformité des dossiers intervenants déjà validés avec un pays nationalité manquant");

        $sqlNationalite = "
            SELECT d.id FROM intervenant_dossier d
                JOIN intervenant i ON i.id = d.intervenant_id 
                JOIN validation v ON d.intervenant_id = v.intervenant_id AND type_validation_id = 1 AND v.histo_destruction IS NULL 
                WHERE d.pays_nationalite_id IS NULL
                AND d.histo_destruction  IS NULL
                AND d.pays_naissance_id = (SELECT id FROM PAYS WHERE ose_divers.str_reduce(libelle) = 'france'  AND HISTO_DESTRUCTION IS NULL)
        ";

        $result = $bdd->select($sqlNationalite);

        $console->println("PRE TRAITEMENT : Nombre de dossier intervenant validé sans pays nationalité dont le pays naissance est France : " . count($result));
        $console->println("TRAITEMENT : Lancement traitement pour forcer nationalité 'France' pour les naissance en 'France'");


        $sqlUpdateNationalite = "
            UPDATE intervenant_dossier SET pays_nationalite_id = (SELECT id FROM PAYS WHERE ose_divers.str_reduce(libelle) = 'france'  AND HISTO_DESTRUCTION IS NULL)
            WHERE id IN (
                SELECT d.id FROM intervenant_dossier d
                JOIN intervenant i ON i.id = d.intervenant_id
                JOIN validation v ON d.intervenant_id = v.intervenant_id AND type_validation_id = 1 AND v.histo_destruction IS NULL
                WHERE d.pays_nationalite_id IS NULL
                AND d.histo_destruction  IS NULL
                AND d.pays_naissance_id = (SELECT id FROM PAYS WHERE ose_divers.str_reduce(libelle) = 'france'  AND HISTO_DESTRUCTION IS NULL)
            )
        ";

        $bdd->exec($sqlUpdateNationalite);

        $result = $bdd->select($sqlNationalite);

        $console->println("POST TRAITEMENT : Nombre de dossier intervenant validé sans pays nationalité dont le pays naissance est France : " . count($result));

        //on recalcule TBL dossier
        $console->println("Recalcul du TBL DOSSIER");
        $bdd->exec('BEGIN unicaen_tbl.calculer(\'dossier\'); END;');
        //Clear cache car on a modifié les privileges donc les entity en cache ne doivent plus servir
        $oa->run('clear-cache');
    }
}