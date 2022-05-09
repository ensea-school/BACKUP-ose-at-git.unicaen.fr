<?php





abstract class AbstractMigration
{

    /**
     * @var MigrationManager
     */
    protected $manager;



    public function __construct(MigrationManager $manager)
    {
        $this->manager = $manager;
    }



    public function getContexte(): string
    {
        return $this->contexte;
    }



    abstract public function description(): string;



    abstract public function utile(): bool;

    /*

    Ajouter uniquement si nécessaire :
    - une méthode publique before() qui s'exécutera AVANT la mise à jour
    - une méthode publique after() qui s'exécutera APRES la mise à jour

    */
}
