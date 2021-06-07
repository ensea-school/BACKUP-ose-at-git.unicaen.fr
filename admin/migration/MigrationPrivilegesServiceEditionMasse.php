<?php


use BddAdmin\Bdd;

class MigrationPrivilegesServiceEditionMasse extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_POST;



    public function description(): string
    {
        return "Ajout du privileges edition des services en masse au niveau des rôles nécessaires";
    }



    public function utile(): bool
    {
        //test si le privilege edition-masse existe
        $bdd = $this->manager->getBdd();
        $sql = "SELECT 
                  p.id 
                FROM privilege p
                JOIN categorie_privilege cp ON cp.id = p.categorie_id AND cp.code = 'enseignement'
                WHERE p.code = 'edition-masse'";

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


        $sqlPrivilegeEditionMasse = "
            SELECT
                p.id   id,
                p.code code
                
            FROM
                privilege p 
            JOIN 
                categorie_privilege cp ON p.categorie_id = cp.id
            WHERE
                cp.code = 'enseignement'
            AND 
                p.code = 'edition-masse'
        ";

        $privilege   = $bdd->select($sqlPrivilegeEditionMasse, [], ['fetch' => Bdd::FETCH_ONE]);
        $privilegeId = $privilege['ID'];


        if (!empty($privilegeId)) {

            $sql = "
                INSERT INTO role_privilege rp (role_id, privilege_id)
                SELECT r.id, $privilegeId FROM  role r 
	            JOIN role_privilege rp ON rp.role_id = r.id AND rp.privilege_id = (
		            SELECT p.id FROM privilege p
		            JOIN categorie_privilege cp ON cp.id = p.categorie_id AND cp.code = 'enseignement'
		            WHERE p.code = 'edition')
		        JOIN role r ON rp.role_id = r.id AND r.code != 'administrateur'    
            ";

            $bdd->exec($sql);
            $console->println("Privilege 'Edition en masse' ajouté sur les rôles nécessaires", CONSOLE::COLOR_GREEN);
        } else {
            $console->println("Privilege 'Edition en masse' non trouvé en base", CONSOLE::COLOR_GREEN);
        }
        //Clear cache car on a modifié les privileges donc les entity en cache ne doivent plus servir
        $oa->run('clear-cache');
    }
}
