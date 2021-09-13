<?php





class ScenarioNoeudEffectifImportable extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_PRE;



    public function description(): string
    {
        return "Rendre importable la table SCENARIO_NOEUD_EFFECTIF";
    }



    public function utile(): bool
    {
        return $this->manager->hasNewColumn('SCENARIO_NOEUD_EFFECTIF', 'SOURCE_ID');
    }



    public function action(string $contexte)
    {
        $bdd = $this->manager->getBdd();

        $bdd->exec('ALTER TABLE SCENARIO_NOEUD_EFFECTIF ADD (HISTO_CREATEUR_ID NUMBER(*,0))');
        $bdd->exec('ALTER TABLE SCENARIO_NOEUD_EFFECTIF ADD (HISTO_MODIFICATEUR_ID NUMBER(*,0))');
        $bdd->exec('ALTER TABLE SCENARIO_NOEUD_EFFECTIF ADD (SOURCE_ID NUMBER(*,0))');

        $bdd->exec('UPDATE SCENARIO_NOEUD_EFFECTIF SET HISTO_CREATEUR_ID = OSE_DIVERS.GET_OSE_UTILISATEUR_ID()');
        $bdd->exec('UPDATE SCENARIO_NOEUD_EFFECTIF SET HISTO_MODIFICATEUR_ID = OSE_DIVERS.GET_OSE_UTILISATEUR_ID()');
        $bdd->exec('UPDATE SCENARIO_NOEUD_EFFECTIF SET SOURCE_ID = OSE_DIVERS.GET_OSE_SOURCE_ID()');
    }

}

