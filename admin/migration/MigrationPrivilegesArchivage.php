<?php


use BddAdmin\Bdd;

class MigrationPrivilegesArchivage extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Ajout du privileges archivage pour les statuts et rôles pouvant éditer les pièces jointes";
    }



    public function utile(): bool
    {
        //test si le privilege archivage existe, si il existe déjà alors pas besoin de jouer cette migration
        $bdd       = $this->manager->getBdd();
        $sql       = "SELECT *
                FROM privilege p 
                WHERE p.code = 'archivage'";
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


        $sqlPrivilegeArchivage = "
            SELECT
                p.id   id,
                p.code code
                
            FROM
                privilege p 
            JOIN 
                categorie_privilege cp ON p.categorie_id = cp.id
            WHERE
                cp.code = 'piece-justificative'
            AND 
                p.code = 'archivage'
        ";

        $privilegeArchivage   = $bdd->select($sqlPrivilegeArchivage, [], ['fetch' => Bdd::FETCH_ONE]);
        $privilegeArchivageId = $privilegeArchivage['ID'];


        $console->println("Récupération des statuts intervenants pouvant éditer une pièce jointe", CONSOLE::COLOR_GREEN);


        //Traitement des privileges pour les statuts
        $statutIntervenant = [];

        $sqlStatut = "SELECT 
            si.code    code_statut,
            si.id      id,
            si.libelle libelle,
            cp.code    categorie_privilege,
            p.code     code_privilege
            FROM statut_intervenant si
            JOIN statut_privilege sp ON sp.statut_id = si.id
            JOIN privilege p ON sp.privilege_id = p.id 
            JOIN categorie_privilege cp ON cp.id = p.categorie_id
            WHERE cp.code = 'piece-justificative'
            AND (p.code = 'edition' OR p.code ='archivage')
            AND si.histo_destruction IS NULL
            ORDER BY si.code ASC
        ";

        $statutsIntervenantPrivilegesArchivage = $bdd->select($sqlStatut);
        foreach ($statutsIntervenantPrivilegesArchivage as $spd) {
            $statutIntervenant[$spd['CODE_STATUT']]['PRIVILEGES'][] = $spd;
            if (!isset($statutIntervenant[$spd['CODE_STATUT']]['ID'])) {
                $statutIntervenant[$spd['CODE_STATUT']]['ID'] = $spd['ID'];
            }
        }

        foreach ($statutIntervenant as $codeStatut => $statut) {
            $console->println('Mise à niveau des privilèges archivage pour le statut : ' . $codeStatut, CONSOLE::COLOR_BLUE);
            if ($this->hasPrivilege($statut, 'edition') && !$this->hasPrivilege($statut, 'archivage')) {
                //On ajouter les privleges archivage pour ce statut car il peut éditer les pièces jointes
                $sqlInsert = "INSERT INTO statut_privilege (privilege_id, statut_id) VALUES ('" . $privilegeArchivageId . "', '" . $statut['ID'] . "')";
                $bdd->exec($sqlInsert);
            }
        }


        $console->println("Récupération des roles pouvant éditer une pièce jointe", CONSOLE::COLOR_GREEN);

        $roles = [];

        $sqlRole = "SELECT
            r.code code_role,
            r.id   id,
            r.libelle libelle,
            cp.code categorie_privilege,
            p.code code_privilege
            FROM role r
            JOIN role_privilege rp ON r.id = rp.role_id
            JOIN privilege p ON p.id = rp.privilege_id
            JOIN categorie_privilege cp ON cp.id = p.categorie_id
            WHERE cp.code = 'piece-justificative'
            AND (p.code = 'edition' OR p.code ='archivage')
            AND r.histo_destruction IS NULL 
            ORDER BY r.code ASC
        ";

        $rolePrivilegesArchivage = $bdd->select($sqlRole);
        foreach ($rolePrivilegesArchivage as $rpd) {
            $roles[$rpd['CODE_ROLE']]['PRIVILEGES'][] = $rpd;
            if (!isset($roles[$rpd['CODE_ROLE']]['ID'])) {
                $roles[$rpd['CODE_ROLE']]['ID'] = $rpd['ID'];
            }
        }

        //Traitement des privileges pour les roles
        foreach ($roles as $codeRole => $role) {
            $console->println('Mise à niveau du privilege archivage pour le role : ' . $codeRole, CONSOLE::COLOR_BLUE);
            if ($this->hasPrivilege($role, 'edition') && !$this->hasPrivilege($role, 'archivage')) {
                //On ajouter les privleges archivage pour ce role car il peut éditer les pièces jointes
                $sqlInsert = "INSERT INTO role_privilege (privilege_id, role_id) VALUES ('" . $privilegeArchivageId . "', '" . $role['ID'] . "')";
                $bdd->exec($sqlInsert);
            }
        }
        //Clear cache car on a modifié les privileges donc les entity en cache ne doivent plus servir
        $oa->run('clear-cache');
    }
}