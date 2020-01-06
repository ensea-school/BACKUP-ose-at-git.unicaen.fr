<?php





class DepartementsInitCodes extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_POST;



    public function description(): string
    {
        return "Ajout des codes manquants sur les données par défaut de la table des départements";
    }



    public function utile(): bool
    {
        $bdd = $this->oseAdmin->getBdd();

        $sql     = "
        SELECT 
            count(*) nbvides 
        FROM 
            departement 
        WHERE 
            code IS NULL AND source_code IS NOT NULL
            AND source_id=:source";
        $res     = $bdd->select($sql, ['source' => $this->oseAdmin->getSourceOseId()], \BddAdmin\Bdd::FETCH_ONE);
        $nbvides = (int)$res['NBVIDES'];

        return $nbvides > 0;
    }



    public function action()
    {
        $sql = "UPDATE departement SET code = source_code WHERE code IS NULL AND source_id= :source";
        $this->oseAdmin->getBdd()->exec($sql, ['source' => $this->oseAdmin->getSourceOseId()]);
    }

}