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

    public function rechercheEmployeur($criteria = null, $limit = 50)
    {
        $employeurs = [];

        $sql = "
            SELECT 
                SIREN, LIBELLE 
            FROM 
                EMPLOYEUR e 
            WHERE rownum <= $limit
        ";

        if(!empty($criteria))
        {
            $sql .= "
             AND (e.LIBELLE LIKE upper('%$criteria%') OR e.SIREN LIKE '%$criteria%')
            ";
           /*  $sql .= "
          AND (regexp_like(LIBELLE, '$criteria', 'i') OR e.SIREN LIKE '%$criteria%')
         ";*/
        }

        $sql .= " ORDER BY LIBELLE ASC";


        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
            while ($r = $stmt->fetch()) {
                $employeurs[] = [
                    'libelle'  => $r['SIREN'],
                    'siren'    => $r['LIBELLE'],
                ];
            }

        return $employeurs;

    }

}