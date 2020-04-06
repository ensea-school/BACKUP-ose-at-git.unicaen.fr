<?php

namespace Application\Service;

class EmployeurService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\Employeur::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'emp';
    }

    public function loadEmployeurFromFile($file)
    {
        $csvFile = fopen($file, r);
        $data = [];
        while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
            $num = count($data);
            echo "$num champs à la ligne $row : ";
            $row++;
            for ($c=0; $c < $num; $c++) {
                echo $data[$c];
            }
            echo "\n";
        }

    }

    public function getEmployeurs($limit = 100)
    {
        $sql = "
        SELECT * FROM EMPLOYEUR WHERE ROWNUM <= $limit
        ";

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        $res = $stmt->fetchAll();
        return $res;
    }

    public function getEmployeursIntervenants()
    {
        $sql = "
            SELECT 
                * 
            FROM EMPLOYEUR e
            JOIN INTERVENANT i ON i.employeur_id = e.id
        ";

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        $res = $stmt->fetchAll();
        return $res;
    }

}