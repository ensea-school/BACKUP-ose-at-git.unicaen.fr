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
        $oa = $this->manager->getOseAdmin();

        $bdd = $this->manager->getSchema()->getBdd();

        $sql     = "
        SELECT 
            count(*) NBVIDES 
        FROM 
            DEPARTEMENT 
        WHERE 
            CODE IS NULL AND SOURCE_CODE IS NOT NULL
            AND SOURCE_ID=:source";
        $res     = $bdd->select($sql, ['source' => $oa->getSourceOseId()], \BddAdmin\Bdd::FETCH_ONE);
        $nbvides = (int)$res['NBVIDES'];

        return $nbvides > 0;
    }



    public function action(string $contexte)
    {
        $oa = $this->manager->getOseAdmin();

        $sql = "UPDATE DEPARTEMENT SET CODE = SOURCE_CODE WHERE CODE IS NULL AND SOURCE_ID= :source";
        $this->manager->getSchema()->getBdd()->exec($sql, ['source' => $oa->getSourceOseId()]);
    }

}