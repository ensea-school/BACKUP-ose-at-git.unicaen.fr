<?php


class v23minimum extends AbstractMigration
{

    public function description(): string
    {
        return "Détection de la version 23 au minimum";
    }



    public function utile(): bool
    {
        return !$this->manager->hasColumn('STRUCTURE', 'IDS') // on est en v23
            && $this->manager->hasTable('STRUCTURE'); // pour éviter de se taper le blocage en cas de nouvelle install
    }



    public function before()
    {
        $this->manager->getOseAdmin()->console()->printDie('Vous devez devez d\'abord migrer en version 23 avant de monter en version ultérieure.');
    }

}