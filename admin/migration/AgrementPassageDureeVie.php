<?php





class AgrementPassageDureeVie extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Initalisation de la durée de vie des agréments";
    }



    public function utile(): bool
    {
        return
            $this->manager->hasOldColumn('TYPE_AGREMENT_STATUT', 'PREMIER_RECRUTEMENT')
            && $this->manager->hasNewColumn('TYPE_AGREMENT_STATUT', 'DUREE_VIE');
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
        $this->manager->sauvegarderTable('TYPE_AGREMENT_STATUT', 'TAS_AJOUT_DUREE_VIE');
    }



    protected function after()
    {
        $sql = '
        UPDATE TYPE_AGREMENT_STATUT SET DUREE_VIE = (
          SELECT CASE WHEN PREMIER_RECRUTEMENT = 1 THEN 99 ELSE 1 END FROM TAS_AJOUT_DUREE_VIE O WHERE O.ID = TYPE_AGREMENT_STATUT.ID
        )';

        $this->manager->getSchema()->getBdd()->exec($sql);
        $this->manager->supprimerSauvegarde('TAS_AJOUT_DUREE_VIE');
    }

}