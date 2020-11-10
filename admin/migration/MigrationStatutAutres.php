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
        $bdd = $this->manager->getBdd();

        $sqlStatutAutres = "
            SELECT *
            FROM statut_intervenant si 
            WHERE si.code = 'AUTRES'
        ";

        $statutAutres = current($bdd->select($sqlStatutAutres));
        //Si j'ai DOSSIER_ADRESSE alors je suis en V15
        if (isset($statutAutres['DOSSIER_ADRESSE'])) {
            //Alors je teste pour voir si pour le statut AUTRES aucun bloc de données perso est activé sinon je les désactive
            return ($statutAutres['DOSSIER_ADRESSE'] ||
                $statutAutres['DOSSIER_CONTACT'] ||
                $statutAutres['DOSSIER_IBAN'] ||
                $statutAutres['DOSSIER_IDENTITE_COMP'] ||
                $statutAutres['DOSSIER_INSEE']
            );
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
        $bdd = $this->manager->getBdd();

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

