<?php





class v23UpdateStatutParamsIndemnitesEtPj extends AbstractMigration
{

    public function description(): string
    {
        return "Mise à jour activation des pièces justificatives et indémnités journalières par statuts";
    }



    public function utile(): bool
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();
        if ($this->manager->hasNewColumn('STATUT', 'PJ_ACTIVE') || $this->manager->hasNewColumn('INTERVENANT_DOSSIER', 'SITUATION_MATRIMONIALE_ID')) {
            return true;
        }


        return false;
    }



    public function after()
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();
        //On traite d'abord la colonne PJ_ACTIVE
        $this->manager->sauvegarderTable('STATUT', 'SAVE_STATUT');
        //On désactive les pièces justificatives sur les statuts sur lesquels on ne demande aucune pièce
        $c->println("Désactivation des pièces justificatives sur les statuts sur lesquels on ne demande aucune pièce");
        $sqlUpdatePjActive = "UPDATE statut SET PJ_ACTIVE  = 0 WHERE id NOT IN (
                                    SELECT s.id FROM type_piece_jointe_statut tpjs
                                    JOIN statut s ON s.id = tpjs.statut_id 
                                    AND tpjs.histo_destruction is NULL
                                    GROUP BY s.id)";
        $bdd->exec($sqlUpdatePjActive);
        //On désactive les indemnités de fin de contrat sur le statut si on a aucun entrée dans la table prime
        $c->println("Désactivation des indémnités de fin de mission sur les statuts où aucun intervenant n'a encore eu d'indémnités de fin de mission");
        //Si pas de prime de fin de mission alors on désactive sur l'ensemble des statuts
        if (empty($primes)) {
            //On désactive les indémintés de fin de mission sur les statuts sur lesquels il n'y a eu aucun prime de fin de mission de crée
            $sqlUpdateMission = "UPDATE statut SET MISSION_INDEMNITEES = 0 WHERE id NOT IN (
                                    SELECT s.id FROM mission_prime mp 
                                    JOIN intervenant i ON i.id = mp.intervenant_id 
                                    JOIN statut s ON s.id = i.statut_id
                                    GROUP BY s.id
                                    )";
            $bdd->exec($sqlUpdateMission);
        }
    }
}