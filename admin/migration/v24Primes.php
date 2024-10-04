<?php


class v24Primes extends AbstractMigration
{

    public function description(): string
    {
        return "Renomage de FC_MAJOREES EN PRIMES";
    }



    public function utile(): bool
    {
        return $this->manager->hasColumn('CC_ACTIVITE', 'FC_MAJOREES');
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();

        $bdd->exec('ALTER TABLE CC_ACTIVITE RENAME COLUMN FC_MAJOREES TO PRIMES');
        $bdd->exec('ALTER TABLE TYPE_RESSOURCE RENAME COLUMN FC_MAJOREES TO PRIMES');
        $bdd->exec("UPDATE TYPE_HEURES SET CODE='primes' WHERE code='fc_majorees'");
    }

}