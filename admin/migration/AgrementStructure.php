<?php





class AgrementStructure extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Correction des structures manquantes sur les agréments";
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
        $bdd     = $this->manager->getBdd();
        $console = $this->manager->getOseAdmin()->getConsole();
        //La vue TBL_AGREMENT a changé, donc il faut d'abord recharger la vue agrement pour le traitement
        $console->println("Calcul du tableau de bord agrement");
        $bdd->exec('BEGIN unicaen_tbl.calculer(\'agrement\'); END;');

        $tacrId = (int)$bdd->select("SELECT id FROM type_agrement WHERE code = 'CONSEIL_RESTREINT'")[0]['ID'];

        //On récupère les agrements de type conseil restreint sans structure
        $agrementsToRecreate = [];
        $sql                 = "SELECT 
                    a.id               agrement_id,
                    i.id               intervenant_id,
                    i.annee_id         annee_id,
                    i.nom_usuel        nom_usuel,
                    i.prenom           prenom,
                    i.code             code_intervenant,
                    a.type_agrement_id type_agrement_id
                FROM AGREMENT a
                JOIN INTERVENANT i ON i.id = a.intervenant_id
                WHERE
                a.type_agrement_id = $tacrId
                AND a.structure_id IS NULL
                AND a.histo_destruction IS NULL
                ";

        $agrements = $bdd->select($sql);
        //Nombre d'agréments concernés
        $nbAgrement = count($agrements);
        if ($nbAgrement > 0) {
            $bdd->exec("alter trigger AGREMENT_CK disable");
            $console->println("Il y a $nbAgrement agréments du conseil restreint a corriger");
            $nbAgrementCorrige = 0;
            //On commence le traitement de chaque agrément qui pose problème
            foreach ($agrements as $agrement) {
                try {
                    $structureId    = false;
                    $nomIntervenant = $agrement['NOM_USUEL'];
                    $intervenantId  = $agrement['INTERVENANT_ID'];
                    $agrementId     = $agrement['AGREMENT_ID'];
                    $console->println("------------------------------------------------------------------");
                    $console->println("Traitement de l'agrement de $nomIntervenant / Agrement_id : $agrementId");
                    $console->println("Recherche d'un structure id dans la table TBL_AGREMENT");
                    //On recherche d'abord dans la table TBL_AGREMENT pour trouver la structure possible
                    $sqlAgrement = "SELECT
                                     ta.structure_id   structure_id,
                                     ta.intervenant_id intervenant_id
                                 FROM 
                                    TBL_AGREMENT ta
                                 WHERE
                                 ta.intervenant_id = $intervenantId
                                 AND ta.type_agrement_id = $tacrId
                                 AND ta.agrement_id IS NULL";

                    $agrements = $bdd->select($sqlAgrement);

                    $nbStructuresPossibles = count($agrements);
                    //Cas où une seule structure est trouvée dans la table TBL_AGREMENT
                    if ($nbStructuresPossibles == 1) {
                        $agrement = current($agrements);
                        //Je set la variable $structureId pour l'update
                        $structureId = $agrement['STRUCTURE_ID'];
                        $console->println("Une seule structure trouvée pour cet intervenant / Structure_id : " . $structureId);
                    } else {
                        //Cas où j'ai plusieurs structures possibles.
                        $console->println("Plusieurs structure_id possible pour cet agrément : $nbStructuresPossibles");
                        $console->println("Recherche des structures possibles via les contrats");
                        //Il faut donc remonter au niveau des contrats pour pouvoir essayer de trouver le bon structure_id de l'agrement
                        $sqlContrats = "SELECT 
                                        c.structure_id   structure_id,
                                        c.intervenant_id intervenant_id   
                                    FROM
                                        CONTRAT c 
                                    WHERE 
                                        c.intervenant_id = $intervenantId
                                        AND c.histo_destruction IS NULL";

                        $contrats = $bdd->select($sqlContrats);

                        $nbContrats = count($contrats);
                        //Cas où j'ai un contrat unique valide, je peux donc isoler la structure_id pour la mettre à jour dans l'agrément
                        if ($nbContrats == 1) {
                            $contrat = current($contrats);
                            //Je set la variable $structureId pour l'update
                            $structureId = $contrat['STRUCTURE_ID'];
                            $console->println("Un contrat unique trouvé et validé avec la structure id : " . $structureId);
                        }
                    }
                    //Si j'ai réussi à identifier une structure unique alors je peux mettre à jour la table agrement
                    if ($structureId !== false) {
                        $console->println("Mise à jour de l'agrement id : $agrementId avec le structure id : $structureId", $console::COLOR_LIGHT_GREEN);
                        $sqlUpdate = "UPDATE AGREMENT SET STRUCTURE_ID = $structureId WHERE ID = $agrementId";
                        $nbAgrementCorrige++;
                        $bdd->exec($sqlUpdate);
                    } else {
                        //Sinon je fais un soft delete de l'agrement
                        //on met une date précise pour retrouver facilement
                        $console->println("Suppression de l'agrément id : $agrementId car aucune structure n'a pu être identifiée", $console::COLOR_LIGHT_RED);
                        $agrementsToRecreate [] = $agrement;
                        $sqlDelete              = "UPDATE 
                                AGREMENT
                              SET 
                                HISTO_DESTRUCTION = TO_DATE('2020 - 01 - 01', 'YYYY - MM - DD'),
                                HISTO_DESTRUCTEUR_ID = HISTO_CREATEUR_ID 
                              WHERE ID = $agrementId";
                        $bdd->exec($sqlDelete);
                    }
                } catch (Exception $e) {
                    $console->println($e->getMessage());
                }
            }

            if (!empty($agrementsToRecreate)) {
                $console->println("Seulement $nbAgrementCorrige sur $nbAgrement on été corrigés", $console::COLOR_LIGHT_RED);
                $console->println("Les intervenants suivants nécessitent une intervention manuelle de votre part : il faut saisir à nouveau leurs agréments du conseil restreint : ", $console::COLOR_LIGHT_RED);
                foreach ($agrementsToRecreate as $value) {
                    $console->println($value['PRENOM'] . " " . $value['NOM_USUEL'] . " (année : " . $value['ANNEE_ID'] . " / id : " . $value['INTERVENANT_ID'] . " / code : " . $value['CODE_INTERVENANT'] . ")", $console::COLOR_LIGHT_RED);
                }
            } else {
                $console->println("Les $nbAgrement agrements restreints sans structure on été corrigés.", $console::COLOR_LIGHT_GREEN);
            }

            $bdd->exec("alter trigger AGREMENT_CK enable");

            //On recalcule les tableaux de bord agrement et workflow pour tout remettre d'équerre
            $console->println("------------------------------------------------------------------");
            $console->println("Nouveau calcul du tableau de bord agrement");
            $bdd->exec('BEGIN unicaen_tbl.calculer(\'agrement\'); END;');

            $console->println("Nouveau calcul du tableau de bord workflow");
            $bdd->exec('BEGIN unicaen_tbl.calculer(\'workflow\'); END;');

            $console->println("Terminé");
        }
    }

}