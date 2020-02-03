<?php





abstract class AbstractMigration
{
    CONST CONTEXTE_PRE  = 'pre';
    CONST CONTEXTE_POST = 'post';
    CONST CONTEXTE_ALL  = 'all';

    /**
     * @var string
     */
    protected $contexte = self::CONTEXTE_POST;

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



    abstract public function action(string $contexte);
}
