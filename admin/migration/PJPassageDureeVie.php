<?php





class PJPassageDureeVie extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Initalisation de la durée de vie des pièces justificatives";
    }



    public function utile(): bool
    {
        return
            $this->manager->hasOldColumn('TYPE_PIECE_JOINTE_STATUT', 'PREMIER_RECRUTEMENT')
            && $this->manager->hasNewColumn('TYPE_PIECE_JOINTE_STATUT', 'DUREE_VIE');
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
        $this->manager->sauvegarderTable('TYPE_PIECE_JOINTE_STATUT', 'TPJS_AJOUT_DUREE_VIE');
    }



    protected function after()
    {
        $sql = '
        UPDATE TYPE_PIECE_JOINTE_STATUT SET DUREE_VIE = (
          SELECT CASE WHEN PREMIER_RECRUTEMENT = 1 THEN 99 ELSE 1 END FROM TPJS_AJOUT_DUREE_VIE O WHERE O.ID = TYPE_PIECE_JOINTE_STATUT.ID
        )';

        $this->manager->getSchema()->getBdd()->exec($sql);
        $this->manager->supprimerSauvegarde('TPJS_AJOUT_DUREE_VIE');
    }

}