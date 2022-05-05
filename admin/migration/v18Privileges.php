<?php





class v18Privileges extends AbstractMigration
{

    public function description(): string
    {
        return "Migration des privilèges de OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        if (!$this->manager->hasTable('SAVE_V18_PRIVILEGE')) {
            return false;
        }

        $sql = "
        SELECT 
          count(*) c
        FROM 
          privilege p
          JOIN categorie_privilege cp ON cp.id = p.categorie_id
        WHERE 
          cp.code = 'enseignement'
          AND p.code = 'visualisation'
          ";

        return $this->manager->getBdd()->select($sql)[0]['C'] == '1';
    }



    public function after()
    {
        $c   = $this->manager->getOseAdmin()->getConsole();
        $bdd = $this->manager->getBdd();

        $sql = "
        INSERT INTO ROLE_PRIVILEGE (privilege_id,role_id)
SELECT 
  np.id privilege_id, r.id role_id
FROM 
  save_v18_privilege p
  JOIN categorie_privilege cp ON cp.id = p.categorie_id
  JOIN save_v18_role_privilege rp ON rp.privilege_id = p.id
  JOIN role r ON r.id = rp.role_id AND r.code <> 'administrateur'
  JOIN (SELECT 'prevu' code FROM dual UNION ALL SELECT 'realise' code FROM dual) tvh ON 1=1
  
  JOIN privilege np ON np.categorie_id = cp.id AND np.code = tvh.code || '-' || p.code
  LEFT JOIN role_privilege nrp ON nrp.privilege_id = np.id AND nrp.role_id = r.id
WHERE 
  cp.code IN ('enseignement','referentiel')
  AND p.code IN ('visualisation','edition', 'validation', 'autovalidation')
  AND rp.role_id <> COALESCE(nrp.role_id,0)
        ";

        $bdd->exec($sql);
        $c->msg('Transfert d\'anciens privilèges vers le nouveau système');
    }

}