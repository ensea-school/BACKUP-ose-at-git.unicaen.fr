<?php

namespace Application\Service;

use UnicaenApp\Util;

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
    public function getAlias()
    {
        return 'emp';
    }



    public function getEmployeurs($limit = 100)
    {
        $sql = "
        SELECT * FROM EMPLOYEUR WHERE ROWNUM <= $limit
        ";

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        $res  = $stmt->fetchAll();

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
        $res  = $stmt->fetchAll();

        return $res;
    }



    public function rechercheEmployeur($criteria = null, $limit = 50)
    {
        $employeurs = [];
        $criteria   = Util::reduce($criteria);

        $sql = "
            SELECT 
                * 
            FROM 
                EMPLOYEUR e 
            WHERE rownum <= $limit
        ";

        if (!empty($criteria)) {
            $sql .= "
             AND e.CRITERE_RECHERCHE LIKE '%$criteria%'";
        }

        $sql .= " ORDER BY RAISON_SOCIALE ASC";


        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while ($r = $stmt->fetch()) {
            $siren                = $r['SIREN'];
            $employeurs[$r['ID']] = [
                'id'    => $r['ID'],
                'label' => $r['RAISON_SOCIALE'],
                'siren' => $siren,
                'extra' => "<small>($siren)</small>",
            ];
        }


        return $employeurs;
    }

}