<?php





class FusionInseeCle extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_PRE;



    public function description(): string
    {
        return "Fusion des numéros INSEE et des clés";
    }



    public function utile(): bool
    {
        return $this->manager->hasOldColumn('INTERVENANT', 'NUMERO_INSEE_CLE');
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
        // Augmentation de la taille du champ pour qu'il puisse contenir la clé
        $bdd = $this->manager->getBdd();
        $bdd->exec('ALTER TABLE INTERVENANT MODIFY (NUMERO_INSEE VARCHAR2(20 CHAR))');
        $bdd->exec('UPDATE INTERVENANT SET NUMERO_INSEE = NUMERO_INSEE || NUMERO_INSEE_CLE,NUMERO_INSEE_CLE=NULL WHERE NUMERO_INSEE IS NOT NULL AND NUMERO_INSEE_CLE IS NOT NULL');
    }



    protected function after()
    {

    }
}

