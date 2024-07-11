<?php





class v23UpdateVolumeHoraireMission extends AbstractMigration
{

    public function description(): string
    {
        return "Mise à jour du source code sur les volumes horaires mission";
    }



    public function utile(): bool
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();
        //Si j'ai des source_code null dans la table volume_horaire_missions
        if ($this->manager->has('table', 'VOLUME_HORAIRE_MISSION')) {
            $result = $bdd->select("SELECT id FROM volume_horaire_mission where source_code IS NULL");
            if (!empty($result)) {
                return true;
            }
        }

        return false;
    }



    public function before()
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();

        //On met à jour le source code avec la valeur de l'id de la ligne qui lui doit être unique
        $c->println("Update source code avec une valeur unique");
        $sqlUpdateSourceCode = "UPDATE volume_horaire_mission SET source_code  = id WHERE source_code IS NULL";
        $bdd->exec($sqlUpdateSourceCode);
    }
}