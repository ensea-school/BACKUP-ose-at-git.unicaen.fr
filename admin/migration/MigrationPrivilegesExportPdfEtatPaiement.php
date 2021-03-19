<?php


use BddAdmin\Bdd;

class MigrationPrivilegesExportPdfEtatPaiement extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_POST;



    public function description(): string
    {
        return "SET UP  : Ajout du privileges export pdf etat paiement pour les rôles pouvant exporter en pdf les mises en paiement";
    }



    public function utile(): bool
    {
        //test si le privilege export pdf etat paiement existe, si il existe déjà alors pas besoin de jouer cette mise à niveau des privilèges
        $bdd       = $this->manager->getBdd();
        $sql       = "SELECT 
                        p.id
                      FROM 
                        privilege p 
                      JOIN categorie_privilege cp ON p.categorie_id = cp.id AND cp.code = 'mise-en-paiement'
                      WHERE p.code = 'export-pdf-etat'";
        $privilege = $bdd->select($sql);
        if (!empty($privilege)) {
            return false;
        }

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

        //On vérifie que le privilege export-pdf-etat existe bien en base
        $sqlPrivilege = "
            SELECT count(*) NB FROM privilege p 
	        JOIN categorie_privilege cp ON p.categorie_id = cp.id AND cp.code = 'mise-en-paiement'
	        WHERE p.code = 'export-pdf-etat'
	        ";

        $res = $bdd->select($sqlPrivilege, [], ['fetch' => $bdd::FETCH_ONE]);

        if ((int)$res['NB'] > 0) {
            //on génére les requêtes insert pour set up le nouveau privilege
            $sqlInsert = "
            SELECT
	            'INSERT INTO role_privilege (ROLE_ID, PRIVILEGE_ID) VALUES ('||rp.role_id||','||(
		            SELECT p.id FROM privilege p 
	                JOIN categorie_privilege cp ON p.categorie_id = cp.id AND cp.code = 'mise-en-paiement'
	                WHERE p.code = 'export-pdf-etat')||')' rsql
            FROM 
	            role_privilege rp 
            WHERE 
	            privilege_id = (
	                SELECT p.id FROM privilege p 
	                JOIN categorie_privilege cp ON p.categorie_id = cp.id AND cp.code = 'mise-en-paiement'
	                WHERE p.code = 'export-pdf' )
            AND role_id NOT IN (
	            SELECT 
		            rp.role_id role_id
		        FROM
		            role_privilege rp 
		        WHERE 
		            privilege_id = (
			            SELECT p.id FROM privilege p 
		                JOIN categorie_privilege cp ON p.categorie_id = cp.id AND cp.code = 'mise-en-paiement'
	    	            WHERE p.code = 'export-pdf-etat') 
                )
        ";


            $inserts = $bdd->select($sqlInsert);
            foreach ($inserts as $insert) {
                $bdd->exec(current($insert));
            }

            $oa->run('clear-cache');
        }
    }
}