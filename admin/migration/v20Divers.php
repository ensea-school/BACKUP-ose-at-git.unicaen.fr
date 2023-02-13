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
        $c   = $this->manager->getOseAdmin()->getConsole();
        $bdd = $this->manager->getBdd();

        $c->begin("Préparation de la migration vers la version 20");

        $bdd->exec("ALTER TABLE TYPE_INTERVENTION DROP CONSTRAINT TYPE_INTERVENTION_CODE_UN");
        $bdd->exec("DROP INDEX TYPE_INTERVENTION_CODE_UN");

        $oseAppliId = $this->manager->getOseAdmin()->getOseAppliId();
        $bdd->exec("UPDATE TYPE_INTERVENTION_STATUT SET HISTO_CREATEUR_ID = $oseAppliId WHERE HISTO_CREATEUR_ID IS NULL");
        $bdd->exec("UPDATE TYPE_INTERVENTION_STATUT SET HISTO_CREATION = SYSDATE WHERE HISTO_CREATION IS NULL");
        $bdd->exec("UPDATE TYPE_INTERVENTION_STATUT SET HISTO_MODIFICATION = SYSDATE WHERE HISTO_MODIFICATION IS NULL");

        $c->end("Préparation terminée");
    }
}