<?php


use Unicaen\BddAdmin\Bdd;

class v23TblPaiement extends AbstractMigration
{

    public function description (): string
    {
        return "Ajout du type d'intervenant au TBL_PAIEMENT";
    }



    public function utile (): bool
    {
        return $this->manager->hasNewColumn('TBL_PAIEMENT', 'TYPE_INTERVENANT_ID');
    }



    public function before ()
    {
        $bdd = $this->manager->getBdd();

        $bdd->exec('DELETE FROM TBL_PAIEMENT');
    }

}