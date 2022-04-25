<?php





class v18Divers extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_PRE;



    public function description(): string
    {
        return "Migration OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'PLAFOND_PERIMETRE');
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        } else {
            $this->after();
        }
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        try {
            $bdd->exec("ALTER TABLE TYPE_INTERVENANT DROP CONSTRAINT TYPE_INTERVENANT_CODE_UN");
            $c->msg('Suppression de la contrainte TYPE_INTERVENANT_CODE_UN en prévision de sa recréation');
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }
    }

}