<?php





class MigrationStatutAutres extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_POST;



    public function description(): string
    {
        return "Paramétre le statut 'Autres' pour qu'il n'ait aucun bloc de données personnelles";
    }



    public function utile(): bool
    {
        //Forcer le paramétrage du statuts AUTRES même après la migration v15
        return ($this->manager->hasNew('table', 'INTERVENANT_DOSSIER') ||
            $this->manager->has('table', 'INTERVENANT_DOSSIER'));
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
        $bdd     = $this->manager->getBdd();
        $console = $this->manager->getOseAdmin()->getConsole();
        $console->println("Traitement données perso statut AUTRES");
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

        $bdd->exec($sqlUpdateStatutAutres);
    }
}

