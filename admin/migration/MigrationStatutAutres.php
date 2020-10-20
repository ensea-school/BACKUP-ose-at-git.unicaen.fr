<?php





class MigrationStatutAutres extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Paramétre le statut 'Autres' pour qu'il n'ait aucun bloc de données personnelles";
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

        $sqlStatutAutres = "
            SELECT
            *
            FROM statut_intervenant si 
            WHERE 
                si.code = 'AUTRES'
        ";

        $statutAutres = current($bdd->select($sqlStatutAutres));

        if ($statutAutres['DOSSIER_ADRESSE'] ||
            $statutAutres['DOSSIER_EMPLOYEUR'] ||
            $statutAutres['DOSSIER_CONTACT'] ||
            $statutAutres['DOSSIER_IBAN'] ||
            $statutAutres['DOSSIER_IDENTITE_COMP'] ||
            $statutAutres['DOSSIER_INSEE']
        ) {
            $sqlUpdateStatutAutres = "
                UPDATE 
                    statut_intervenant 
                SET
                    dossier_adresse = 0,
                    dossier_employeur = 0,
                    dossier_contact = 0,
                    dossier_iban = 0,
                    dossier_identite_comp = 0,
                    dossier_insee = 0
                WHERE code = 'AUTRES'
                ";

            $console->println("Update du statut autre pour n'avoir aucun bloc dans les données persos");

            $bdd->exec($sqlUpdateStatutAutres);
        }
        $console->println("Fin du traitement du statut autres");
    }
}

