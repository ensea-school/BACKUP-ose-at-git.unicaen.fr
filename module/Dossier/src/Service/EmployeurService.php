<?php

namespace Dossier\Service;

use Application\Service\AbstractEntityService;
use UnicaenVue\Util;
use UnicaenVue\View\Model\AxiosModel;

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
        return \Dossier\Entity\Db\Employeur::class;
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

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        
        return $res;
    }



    public function getEmployeursIntervenants()
    {
        $sql = "
            SELECT 
                * 
            FROM employeur e
            JOIN intervenant i ON i.employeur_id = e.id
        ";

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);

        return $res;
    }



    public function rechercheEmployeur($criteria = null, $limit = 50)
    {
        $employeurs = [];
        if ($criteria) {
            $criteria = \UnicaenApp\Util::reduce($criteria);
        }


        $sql = "
            SELECT 
                s.code, e.* 
            FROM 
                EMPLOYEUR e
            JOIN source s on s.id =e.source_id
            WHERE rownum <= $limit
            AND HISTO_DESTRUCTION IS NULL
        ";

        if (!empty($criteria)) {
            $sql .= "
             AND e.CRITERE_RECHERCHE LIKE '%$criteria%'";
        }

        $sql .= " ORDER BY RAISON_SOCIALE ASC";


        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while ($r = $stmt->fetch()) {
            $siren                = $r['SIREN'];
            $siret                = $r['SIRET'];
            $employeurs[$r['ID']] = [
                'id'          => $r['ID'],
                'label'       => $r['RAISON_SOCIALE'],
                'siret'       => $siret,
                'extra'       => "<small>($siret)</small>",
                'source'      => $r['SOURCE_ID'],
                'source_code' => $r['CODE'],
            ];
        }


        return $employeurs;
    }



    public function getDataEmployeur(array $post): AxiosModel
    {

        $sql = "
                SELECT
                e.id                id,
                e.raison_sociale    raison_sociale,
                e.nom_commercial    nom_commercial,
                e.siren             siren,
                s.importable        importable
                FROM
                    employeur e
                JOIN source s ON e.source_id = s.id
                WHERE
                  lower(e.critere_recherche) like :search
                ";

        $em = $this->getEntityManager();

        return Util::tableAjaxData($em, $post, $sql);
    }

}