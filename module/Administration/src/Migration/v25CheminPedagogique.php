<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v25CheminPedagogique extends MigrationAction
{


    public function description(): string
    {
        return "Mise à jour des chemins pédagogique pour l'offre de formation complémentaire";
    }



    public function utile(): bool
    {
        //On vérifier si il y a des chemins pédagogiques qui ne sont pas à jour
        $sql = "SELECT cp.id                  chemin_id,
                       cp.element_pedagogique_id,
                       cp.etape_id            etape_chemin,
                       ep.etape_id            etape_element,
                       ep.annee_id            annee_id
                FROM chemin_pedagogique cp
                JOIN element_pedagogique ep
                  ON ep.id = cp.element_pedagogique_id
                --Uniquement l'offre complémentaire non importée
                JOIN SOURCE s ON ep.source_id = s.id AND s.importable = 0
                WHERE 
                    cp.etape_id <> ep.etape_id 
                --On remet d'équerre uniquement l'offre de 2025
                AND ep.annee_id = 2025 
                AND cp.histo_destruction IS NULL";

        $param = $this->getBdd()->select($sql);
        if (empty($param)) {
            return false;
        } else {
            return true;
        }

    }



    public function before()
    {

        try {
            //1 - Sauvegarde de la table des chemins pédagogiques par prudence
            $this->manager()->sauvegarderTable('CHEMIN_PEDAGOGIQUE', 'SAVE_CHEMIN_PEDAGOGIQUE');

            //2 - Nombre de chemins pédagogiques de l'offre complémentaire à mettre à jour
            $sqlCount = "
            SELECT count(*) as NB
            FROM chemin_pedagogique cp
            JOIN element_pedagogique ep
                 ON ep.id = cp.element_pedagogique_id
            --Uniquement l'offre complémentaire non importée
            JOIN SOURCE s ON ep.source_id = s.id AND s.importable = 0
            WHERE 
                 cp.etape_id <> ep.etape_id 
            --Uniquement sur 2025
            AND ep.annee_id = 2025 
            AND cp.histo_destruction IS NULL";

            $result = $this->getBdd()->selectOne($sqlCount);
            $count  = $result['NB'];

            //3 - Mise à jour des chemins pédagogiques de l'offre complémentaire
            $sql = "
            UPDATE chemin_pedagogique cp
            SET cp.etape_id = (
                SELECT ep.etape_id
                FROM element_pedagogique ep
                WHERE ep.id = cp.element_pedagogique_id
            )
            WHERE EXISTS (
                SELECT 1
                FROM element_pedagogique ep
                JOIN source s
                  ON s.id = ep.source_id AND s.importable = 0
                WHERE ep.id = cp.element_pedagogique_id
                  AND ep.annee_id = 2025
                  AND cp.histo_destruction IS NULL
                  AND cp.etape_id <> ep.etape_id
            )
        ";

            $this->getBdd()->exec($sql);

            $this->logSuccess('Mise à jour de ' . $count . ' chemin(s) pédagogique(s) de l\'offre complémentaire de 2025');
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }

    }


}
