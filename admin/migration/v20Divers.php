<?php





class v20Divers extends AbstractMigration
{

    public function description(): string
    {
        return "Transformation des modèles de contrats en états de sortie";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'TAG');
    }



    public function before()
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();

        $c->begin("Préparation de la migration vers la version 20");

        if ($bdd->uniqueConstraint()->exists('TYPE_INTERVENTION_CODE_UN')) {
            $bdd->uniqueConstraint()->drop('TYPE_INTERVENTION_CODE_UN');
        }
        if ($bdd->index()->exists('TYPE_INTERVENTION_CODE_UN')){
            $bdd->index()->drop('TYPE_INTERVENTION_CODE_UN');
        }

        if ($bdd->uniqueConstraint()->exists('TI_STATUT_STATUT_UN')) {
            $bdd->uniqueConstraint()->drop('TI_STATUT_STATUT_UN');
        }
        if ($bdd->index()->exists('TI_STATUT_STATUT_UN')){
            $bdd->index()->drop('TI_STATUT_STATUT_UN');
        }

        $oseAppliId = $this->manager->getOseAdmin()->getOseAppliId();
        $bdd->exec("UPDATE TYPE_INTERVENTION_STATUT SET HISTO_CREATEUR_ID = $oseAppliId WHERE HISTO_CREATEUR_ID IS NULL");
        $bdd->exec("UPDATE TYPE_INTERVENTION_STATUT SET HISTO_CREATION = SYSDATE WHERE HISTO_CREATION IS NULL");
        $bdd->exec("UPDATE TYPE_INTERVENTION_STATUT SET HISTO_MODIFICATION = SYSDATE WHERE HISTO_MODIFICATION IS NULL");

        $c->end("Préparation terminée");
    }
}