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
        if (!$this->manager->has('table', 'INTERVENANT_DOSSIER')) {
            return false;
        }

        $sql = "SELECT count(*) res FROM statut_intervenant WHERE code = 'AUTRES' AND (dossier_adresse = 1 OR
                    dossier_employeur = 1 OR
                    dossier_contact = 1 OR
                    dossier_iban = 1 OR
                    dossier_identite_comp = 1 OR
                    dossier_insee = 1)";

        $bdd = $this->manager->getBdd();

        return $bdd->select($sql)[0]['RES'] == '1';
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
        $bdd                   = $this->manager->getBdd();
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

