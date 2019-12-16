<?php





abstract class AbstractMigration
{
    CONST CONTEXTE_PRE  = 'pre';
    CONST CONTEXTE_POST = 'post';

    /**
     * @var string
     */
    protected $contexte = self::CONTEXTE_POST;

    /**
     * @var OseAdmin
     */
    protected $oseAdmin;



    public function __construct(OseAdmin $oseAdmin)
    {
        $this->oseAdmin = $oseAdmin;
    }



    public function getContexte(): string
    {
        return $this->contexte;
    }



    abstract public function description(): string;



    abstract public function utile(): bool;



    abstract public function action();
}
