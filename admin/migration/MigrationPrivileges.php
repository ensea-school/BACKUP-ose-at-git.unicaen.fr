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
        return $this->manager->hasNew('table', 'DOSSIER_CHAMP_AUTRES');
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
        $console->println("Récupération des nouveaux privilèges données personnelles");
        $sqlNewPrivileges = "
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

        $newPrivilegesDossier = $bdd->select($sqlNewPrivileges);

        $console->println("Récupération des statuts intervenants ayant des privileges de la catégorie dossier", CONSOLE::COLOR_GREEN);


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
            WHERE cp.code = 'dossier'
            AND si.histo_destruction IS NULL
            ORDER BY si.code ASC
        ";

        $statutsIntervenantPrivilegesDossier = $bdd->select($sqlStatut);
        foreach ($statutsIntervenantPrivilegesDossier as $spd) {
            $statutIntervenant[$spd['CODE_STATUT']]['PRIVILEGES'][] = $spd;
            if (!isset($statutIntervenant[$spd['CODE_STATUT']]['ID'])) {
                $statutIntervenant[$spd['CODE_STATUT']]['ID'] = $spd['ID'];
            }
        }

        foreach ($statutIntervenant as $codeStatut => $statut) {
            $ajoutVisualisation      = 0;
            $suppressionVisualiation = 0;
            $ajoutEdition            = 0;
            $suppressionEdition      = 0;
            $console->println('Mise à niveau des privilèges données personnelles du statut : ' . $codeStatut, CONSOLE::COLOR_BLUE);
            if ($this->havePrivilege($statut, 'visualisation')) {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'visualisation')) {
                        if (!$this->havePrivilege($statut, $newPrivilege['CODE'])) {

                            //On ajouter les privleges de visualisation des blocs données perso car il a le droit visualisation sur le dossier
                            $sqlInsert = "INSERT INTO statut_privilege (privilege_id, statut_id) VALUES ('" . $newPrivilege['ID'] . "', '" . $statut['ID'] . "')";
                            $bdd->exec($sqlInsert);
                            $ajoutVisualisation++;
                        }
                    }
                }
                if ($ajoutVisualisation > 0) {
                    //$console->println('Ajout des privileges visualisation pour le statut : ' . $codeStatut, CONSOLE::COLOR_BLUE);
                }
            } else {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'visualisation')) {
                        if ($this->havePrivilege($statut, $newPrivilege['CODE'])) {
                            //On supprime les privileges de visualisation des blocs données perso car il n'a pas le droit visualistion sur le dossier
                            $sqlDelete = "DELETE FROM statut_privilege WHERE privilege_id = " . $newPrivilege['ID'] . " AND statut_id = " . $statut['ID'];
                            $bdd->exec($sqlDelete);
                            $suppressionVisualiation++;
                        }
                    }
                }
                if ($suppressionVisualiation > 0) {
                    //$console->println('Suppression des privileges visualisation pour le statut : ' . $codeStatut, CONSOLE::COLOR_BLUE);
                }
            }
            if ($ajoutVisualisation == 0 && $suppressionEdition == 0) {
                //$console->println('Les priviléges visualisation des blocs dossier perso pour le statut : ' . $codeStatut . ' sont déjà correctement paramétrés', CONSOLE::COLOR_GREEN);
            }
            if ($this->havePrivilege($statut, 'edition')) {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'edition')) {
                        if (!$this->havePrivilege($statut, $newPrivilege['CODE'])) {
                            //On ajouter les privleges d'édition des blocs données perso car il a le droit edition sur le dossier
                            $sqlInsert = "INSERT INTO statut_privilege (privilege_id, statut_id) VALUES ('" . $newPrivilege['ID'] . "', '" . $statut['ID'] . "')";
                            $bdd->exec($sqlInsert);
                            $ajoutEdition++;
                        }
                    }
                }
                if ($ajoutEdition > 0) {
                    //$console->println('Ajout des privileges edition des blocs de données perso pour le statut : ' . $codeStatut, CONSOLE::COLOR_BLUE);
                }
            } else {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'edition')) {
                        if ($this->havePrivilege($statut, $newPrivilege['CODE'])) {
                            //On supprime les privileges d'édition des blocs données perso car il n'a pas le droit edition sur le dossier
                            $sqlDelete = "DELETE FROM statut_privilege WHERE privilege_id = " . $newPrivilege['ID'] . " AND statut_id = " . $statut['ID'];
                            $bdd->exec($sqlDelete);
                            $suppressionEdition++;
                        }
                    }
                }
                if ($suppressionEdition > 0) {
                    //$console->println('Suppression des privileges edition des blocs de données perso pour le statut : ' . $codeStatut, CONSOLE::COLOR_BLUE);
                }
            }
            if ($ajoutEdition == 0 && $suppressionEdition == 0) {
                //$console->println('Les priviléges d\'édition des blocs dossier perso pour le statut : ' . $codeStatut . ' sont déjà correctement paramétrés', CONSOLE::COLOR_GREEN);
            }
        }
        //Traitement des privileges pour les rôles
        $console->println("Récupération des roles ayant des privileges de la catégorie dossier", CONSOLE::COLOR_GREEN);

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
            WHERE cp.code = 'dossier'
            AND r.histo_destruction IS NULL 
            ORDER BY r.code ASC
        ";

        $rolePrivilegesDossier = $bdd->select($sqlRole);
        foreach ($rolePrivilegesDossier as $rpd) {
            $roles[$rpd['CODE_ROLE']]['PRIVILEGES'][] = $rpd;
            if (!isset($roles[$rpd['CODE_ROLE']]['ID'])) {
                $roles[$rpd['CODE_ROLE']]['ID'] = $rpd['ID'];
            }
        }

        //Traitement des privileges pour les roles
        foreach ($roles as $codeRole => $role) {
            $ajoutVisualisation      = 0;
            $suppressionVisualiation = 0;
            $ajoutEdition            = 0;
            $suppressionEdition      = 0;
            $console->println('Mise à niveau des privilèges des données personnelles du role : ' . $codeRole, CONSOLE::COLOR_BLUE);
            if ($this->havePrivilege($role, 'visualisation')) {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'visualisation')) {
                        if (!$this->havePrivilege($role, $newPrivilege['CODE'])) {

                            //On ajouter les privleges de visualisation des blocs données perso car il a le droit visualisation sur le dossier
                            $sqlInsert = "INSERT INTO role_privilege (privilege_id, role_id) VALUES ('" . $newPrivilege['ID'] . "', '" . $role['ID'] . "')";
                            $bdd->exec($sqlInsert);
                            $ajoutVisualisation++;
                        }
                    }
                }
                if ($ajoutVisualisation > 0) {
                    //$console->println('Ajout des privileges visualisation pour le role : ' . $codeRole, CONSOLE::COLOR_BLUE);
                }
            } else {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'visualisation')) {
                        if ($this->havePrivilege($role, $newPrivilege['CODE'])) {
                            //On supprime les privileges de visualisation des blocs données perso car il n'a pas le droit visualistion sur le dossier
                            $sqlDelete = "DELETE FROM role_privilege WHERE privilege_id = " . $newPrivilege['ID'] . " AND role_id = " . $role['ID'];
                            $bdd->exec($sqlDelete);
                            $suppressionVisualiation++;
                        }
                    }
                }
                if ($suppressionVisualiation > 0) {
                    //$console->println('Suppression des privileges visualisation pour le role : ' . $codeRole, CONSOLE::COLOR_BLUE);
                }
            }
            if ($ajoutVisualisation == 0 && $suppressionEdition == 0) {
                //$console->println('Les priviléges visualisation des blocs dossier perso pour le role : ' . $codeRole . ' sont déjà correctement paramétrés', CONSOLE::COLOR_GREEN);
            }
            if ($this->havePrivilege($role, 'edition')) {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'edition')) {
                        if (!$this->havePrivilege($role, $newPrivilege['CODE'])) {
                            //On ajouter les privleges d'édition des blocs données perso car il a le droit edition sur le dossier
                            $sqlInsert = "INSERT INTO role_privilege (privilege_id, role_id) VALUES ('" . $newPrivilege['ID'] . "', '" . $role['ID'] . "')";
                            $bdd->exec($sqlInsert);
                            $ajoutEdition++;
                        }
                    }
                }
                if ($ajoutEdition > 0) {
                    // $console->println('Ajout des privileges edition des blocs de données perso pour le role : ' . $codeStatut, CONSOLE::COLOR_BLUE);
                }
            } else {
                foreach ($newPrivilegesDossier as $newPrivilege) {
                    if (strstr($newPrivilege['CODE'], 'edition')) {
                        if ($this->havePrivilege($role, $newPrivilege['CODE'])) {
                            //On supprime les privileges d'édition des blocs données perso car il n'a pas le droit edition sur le dossier
                            $sqlDelete = "DELETE FROM role_privilege WHERE privilege_id = " . $newPrivilege['ID'] . " AND role_id = " . $role['ID'];
                            $bdd->exec($sqlDelete);
                            $suppressionEdition++;
                        }
                    }
                }
                if ($suppressionEdition > 0) {
                    //$console->println('Suppression des privileges edition des blocs de données perso pour le role : ' . $codeRole, CONSOLE::COLOR_BLUE);
                }
            }
            if ($ajoutEdition == 0 && $suppressionEdition == 0) {
                //$console->println('Les priviléges d\'édition des blocs dossier perso pour le role : ' . $codeRole . ' sont déjà correctement paramétrés', CONSOLE::COLOR_GREEN);
            }
        }
    }



    private function havePrivilege($statut, $privilegeCode)
    {
        foreach ($statut['PRIVILEGES'] as $privilege) {
            if ($privilege['CODE_PRIVILEGE'] == $privilegeCode) {
                return true;
            }
        }

        return false;
    }
}

