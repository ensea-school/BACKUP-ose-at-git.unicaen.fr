<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v24ParametresAnnualises extends MigrationAction
{


    public function description(): string
    {
        return "Correction du problème de paramétrages annualisés";
    }



    public function utile(): bool
    {
        $param = $this->getBdd()->selectOne($this->sql());
        if (empty($param)) {
            return false;
        } else {
            return true;
        }
    }



    public function after()
    {
        $this->logMsg("Correction du problème de paramétrages annualisés");

        // On boucle 50 fois pour être sûr que rien ne reste
        for ($passe = 1; $passe < 51; $passe++) {
            $stmt  = $this->getBdd()->selectEach($this->sql());
            $count = 0;
            while ($d = $stmt->next()) {

                $tableName = $d['TABLE_NAME'];
                $data      = ['HISTO_MODIFICATEUR_ID' => null];
                $key       = ['ID' => (int)$d['ID']];

                $this->getBdd()->getTable($tableName)->update($data, $key);
                $count++;
            }

            if (null === $d) {
                $passe = 51;
            } else {
                $this->logMsg("Passe $passe : $count cas traités");
            }
        }
    }



    private function sql(): string
    {
        return "
        SELECT
          'STATUT' table_name, t.id id
        FROM 
          STATUT t
          JOIN STATUT ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND ta.code = t.code 
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
        
        UNION ALL
        
        SELECT
          'TYPE_PIECE_JOINTE_STATUT' table_name, t.id id
        FROM 
          TYPE_PIECE_JOINTE_STATUT t
          JOIN TYPE_PIECE_JOINTE_STATUT ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND ta.TYPE_PIECE_JOINTE_ID = t.TYPE_PIECE_JOINTE_ID
                        AND (select code FROM statut WHERE id = ta.STATUT_ID) = (select code FROM statut WHERE id = t.STATUT_ID)
                        AND ta.NUM_REGLE = t.NUM_REGLE
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
        
        UNION ALL
        
        SELECT
          'FONCTION_REFERENTIEL' table_name, t.id id
        FROM 
          FONCTION_REFERENTIEL t
          JOIN FONCTION_REFERENTIEL ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND ta.code = t.code 
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
          
        UNION ALL
        
        SELECT
          'TYPE_INTERVENTION_STATUT' table_name, t.id id
        FROM 
          TYPE_INTERVENTION_STATUT t
          JOIN TYPE_INTERVENTION_STATUT ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND ta.TYPE_INTERVENTION_ID = t.TYPE_INTERVENTION_ID 
                        AND (select code FROM statut WHERE id = ta.STATUT_ID) = (select code FROM statut WHERE id = t.STATUT_ID)
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
          
        UNION ALL
        
        SELECT
          'TYPE_MISSION' table_name, t.id id
        FROM 
          TYPE_MISSION t
          JOIN TYPE_MISSION ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND ta.code = t.code 
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
          
        UNION ALL
        
        SELECT
          'PLAFOND_MISSION' table_name, t.id id
        FROM 
          PLAFOND_MISSION t
          JOIN PLAFOND_MISSION ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND (select code from type_mission where id = ta.TYPE_MISSION_ID) = (select code from type_mission where id = t.TYPE_MISSION_ID)
                        AND ta.PLAFOND_ID = t.PLAFOND_ID 
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
          
        UNION ALL
        
        SELECT
          'PLAFOND_REFERENTIEL' table_name, t.id id
        FROM 
          PLAFOND_REFERENTIEL t
          JOIN PLAFOND_REFERENTIEL ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND (select code from fonction_referentiel WHERE id = ta.FONCTION_REFERENTIEL_ID) = (select code from fonction_referentiel WHERE id = t.FONCTION_REFERENTIEL_ID)
                        AND ta.PLAFOND_ID = t.PLAFOND_ID  
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
          
        UNION ALL
        
        SELECT
          'PLAFOND_STATUT' table_name, t.id id
        FROM 
          PLAFOND_STATUT t
          JOIN PLAFOND_STATUT ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND (select code FROM statut WHERE id = ta.STATUT_ID) = (select code FROM statut WHERE id = t.STATUT_ID)
                        AND ta.PLAFOND_ID = t.PLAFOND_ID  
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
          
        UNION ALL
        
        SELECT
          'PLAFOND_STRUCTURE' table_name, t.id id
        FROM 
          PLAFOND_STRUCTURE t
          JOIN PLAFOND_STRUCTURE ta ON ta.annee_id = t.annee_id - 1 AND ta.histo_destruction IS NULL
                        AND ta.STRUCTURE_ID = t.STRUCTURE_ID 
                        AND ta.PLAFOND_ID = t.PLAFOND_ID  
        WHERE 
          t.histo_destruction IS NULL
          AND t.annee_id > 2024
          AND t.histo_modificateur_id = ose_parametre.get_ose_user
          AND t.histo_modification = ta.histo_modification
        ";
    }
}
