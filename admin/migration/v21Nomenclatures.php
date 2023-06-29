<?php





class v21Nomenclatures extends AbstractMigration
{

    public function description(): string
    {
        return "Peuplement des nouvelles nomenclatures";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'TAUX_REMU');
    }



    public function after()
    {
        $c   = $this->manager->getOseAdmin()->getConsole();
        $du = $this->manager->getBdd()->dataUpdater();

        $du->run('install', 'TAUX_REMU');
        $du->run('install', 'TAUX_REMU_VALEUR');
        $du->run('install', 'JOUR_FERIE');
        $du->run('install', 'TYPE_SERVICE');
        $du->run('install', 'TYPE_MISSION');
    }
}