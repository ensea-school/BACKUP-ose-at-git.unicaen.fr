<?php





class NouveauConnecteurIntervenantV15 extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Préparation de la mise en place du nouveau connectuer INTERVENANT pour la V15";
    }



    public function utile(): bool
    {
        return $this->manager->hasNewColumn('INTERVENANT', 'VALIDITE_DEBUT');
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
        $bdd = $this->manager->getBdd();

        $ipd = require $this->manager->getOseAdmin()->getOseDir() . '/data/import_tables.php';

        $bdd->getTable('IMPORT_TABLES')->update(['SYNC_ENABLED' => 0, 'SYNC_FILTRE' => $ipd['INTERVENANT']['SYNC_FILTRE']], ['TABLE_NAME' => 'INTERVENANT']);
    }



    protected function after()
    {
        $bdd = $this->manager->getBdd();
    }

}

