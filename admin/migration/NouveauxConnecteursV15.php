<?php





class NouveauxConnecteursV15 extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_PRE;



    public function description(): string
    {
        return "Désactivation des connecteurs à modifier pour la V15";
    }



    public function utile(): bool
    {
        return $this->manager->hasNewColumn('INTERVENANT', 'VALIDITE_DEBUT');
    }



    public function action(string $contexte)
    {
        $bdd = $this->manager->getBdd();

        $ipd = require $this->manager->getOseAdmin()->getOseDir() . '/data/import_tables.php';

        $bdd->getTable('IMPORT_TABLES')->update(['SYNC_ENABLED' => 0, 'SYNC_FILTRE' => $ipd['INTERVENANT']['SYNC_FILTRE']], ['TABLE_NAME' => 'INTERVENANT']);
        $bdd->getTable('IMPORT_TABLES')->update(['SYNC_ENABLED' => 0], ['TABLE_NAME' => 'STRUCTURE']);
        $bdd->getTable('IMPORT_TABLES')->update(['SYNC_ENABLED' => 0], ['TABLE_NAME' => 'DEPARTEMENT']);
        $bdd->getTable('IMPORT_TABLES')->update(['SYNC_ENABLED' => 0], ['TABLE_NAME' => 'PAYS']);

        $bdd->exec("UPDATE intervenant SET SOURCE_CODE = 'ID-' || id WHERE source_code IS NULL");

        // Reconstruction des vues diff et des proc maj pour éviter d'afficher des bugs après
        $this->manager->getOseAdmin()->exec('UnicaenImport MajVuesFonctions');
    }

}