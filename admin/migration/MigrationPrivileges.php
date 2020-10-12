<?php





class MigrationPrivileges extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Ajout des privileges d'édition et visualisation du dossier pour les intervenants";
    }



    public function utile(): bool
    {
        return true;
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
        $console->println("Récupération des statuts intervenants");
        $sqlStatut = "
            SELECT 
              *
            FROM statut_intervenant si 
            WHERE 
                si.histo_destruction IS NULL
            AND si.peut_saisir_dossier = 1";

        $statutsIntervenant = $bdd->select($sqlStatut);

        $sqlPrivileges = "
            SELECT
                p.id   id,
                p.code code
            FROM
                privilege p 
            JOIN 
                categorie_privilege cp ON p.categorie_id = cp.id
            WHERE
                cp.code = 'dossier'
            AND 
                p.code NOT IN ('differences', 'purger-differences', 'devalidation', 'validation', 'suppression', 'visualisation', 'edition')";

        $privileges = $bdd->select($sqlPrivileges);

        foreach ($statutsIntervenant as $statut) {
            $statutId = $statut['ID'];
            $console->println("Insertion priviliege dossier pour le statut : " . $statut['LIBELLE'], $console::COLOR_LIGHT_GREEN);
            foreach ($privileges as $privilege) {
                $privilegeId = $privilege['ID'];
                try {
                    $sqlInsert = "INSERT INTO statut_privilege (privilege_id, statut_id) VALUES ('" . $privilegeId . "', '" . $statutId . "')";
                    $bdd->exec($sqlInsert);
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    continue;
                    /*if (!strpos($message, 'ORA-00001')) {
                        $console->println("Privilege déjà existant en base pour ce statut", $console::COLOR_LIGHT_RED);
                    }*/
                }
            }
        }

        $console->println("Fin de l'insertion des privileges dossier");
    }
}

