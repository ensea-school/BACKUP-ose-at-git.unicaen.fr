<?php

namespace Dossier\Service;

use Application\Service\AbstractEntityService;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenVue\Util;
use UnicaenVue\View\Model\AxiosModel;

class EmployeurService extends AbstractEntityService
{
    use BddAwareTrait;

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
                ORDER BY raison_sociale ASC
                ";

        $em = $this->getEntityManager();

        return Util::tableAjaxData($em, $post, $sql);
    }



    public function mergeDatasEmployeur(string $filepath, int $idSource): void
    {

        $tableEmployeur = $this->bdd->getTable('EMPLOYEUR');
        $csvFile        = fopen($filepath, "r");

        $num     = str_replace('.csv', '', basename($filepath));
        $row     = 0;
        $datas   = [];
        $options = [];
        while (($data = fgetcsv($csvFile, 1000, ",")) !== false) {

            /*
            * $data[0] = Siren
            * $data[1] = Etat Administratif
            * $data[2] = Nom unité légale (cas entreprise en nom propre)
            * $data[3] = Nom usage unité légale
            * $data[4] = Raison sociale pour les personnes morales
            * $data[5] = Nom sous lequel est connu l'entreprise du grand public (champ N°1 à 70 carac)
            * $data[6] = Nom sous lequel est connu l'entreprise du grand public (champ N°2 à 70 carac)
            * $data[7] = Nom sous lequel est connu l'entreprise du grand public (champ N°3 à 70 carac)
            * $data[8] = Date de dernier traitement de l'unité légale
            * $data[9] = Unité pouvant employer des personnes
            * $data[10] = Identifiant association
            * $data[11] = Siret
            *
            */

            //On ignore la premiere ligne du fichier qui est un header
            if ($data[0] == 'siren') {
                continue;
            }

            $nomCommercial = (!empty($data[5])) ? $data[5] : '';
            $nomCommercial .= (!empty($data[6])) ? ' ' . $data[6] : '';
            $nomCommercial .= (!empty($data[7])) ? ' ' . $data[7] : '';
            $nomCommercial = str_replace("''", "'", $nomCommercial);
            //RAISON_SOCIALE
            $nomJuridique = $data[4];
            //SIREN
            $siren = $data[0];
            //SIRET
            $siret = (isset($data[11])) ? $data[0] . $data[11] : '';

            //IDENTIFIANT ASSOCIATION
            $identifiantAssociation = $data[10];
            //Nom propre entité
            $nomPropre = $data[2];
            //Nom usage entité au lieu du nom propre
            $nomUsage = $data[3];
            //Raison sociale
            if (!empty($nomJuridique)) {
                $raisonSociale = $nomJuridique;
            } elseif (!empty($nomUsage)) {
                $raisonSociale = $nomUsage;
            } elseif (!empty($nomPropre)) {
                $raisonSociale = $nomPropre;
            }
            $raisonSociale = str_replace("''", "'", $raisonSociale);
            //Si pas de raison sociale et pas de nom commercial on passe
            if (empty($raisonSociale) && empty($nomCommercial)) {
                continue;
            }

            //Compilation des datas

            $data                            = [];
            $options                         = [];
            $data['SIREN']                   = $siren;
            $data['SIRET']                   = $siret;
            $data['RAISON_SOCIALE']          = $raisonSociale;
            $data['NOM_COMMERCIAL']          = $nomCommercial;
            $data['SOURCE_CODE']             = $siret;
            $data['SOURCE_ID']               = $idSource;
            $data['HISTO_DESTRUCTEUR_ID']    = null;
            $data['HISTO_DESTRUCTION']       = null;
            $data['IDENTIFIANT_ASSOCIATION'] = $identifiantAssociation;
            $data['CRITERE_RECHERCHE']       = \UnicaenApp\Util::reduce($raisonSociale . ' ' . $nomCommercial . ' ' . $siren . ' ' . $siret);
            $datas[]                         = $data;
            $options['where']                = 'SIREN LIKE \'' . $num . '%\' AND SOURCE_ID = (SELECT id FROM source WHERE code = \'INSEE\') AND SIREN NOT IN (\'999999999\', \'000000000000\')';
            $options['delete']               = false;

        }
        if (!empty($datas)) {
            $tableEmployeur->merge($datas, 'SIREN', $options);
        }

    }



    public function mergeDefaultEmployeur($idSource): void
    {

        $tableEmployeur = $this->bdd->getTable('EMPLOYEUR');
        //Employeur étrangé
        $data                            = [];
        $data['SIREN']                   = '999999999';
        $data['RAISON_SOCIALE']          = 'EMPLOYEUR ETRANGER';
        $data['NOM_COMMERCIAL']          = 'EMPLOYEUR ETRANGER';
        $data['SOURCE_CODE']             = '999999999';
        $data['SOURCE_ID']               = $idSource;
        $data['HISTO_DESTRUCTEUR_ID']    = null;
        $data['HISTO_DESTRUCTION']       = null;
        $data['IDENTIFIANT_ASSOCIATION'] = null;
        $data['CRITERE_RECHERCHE']       = \UnicaenApp\Util::reduce('Employeur étranger 999999999');
        $options['where']                = 'SIREN = \'999999999\'';
        $options['soft-delete']          = true;
        $datas                           = [];
        $datas[]                         = $data;
        $tableEmployeur->merge($datas, 'SIREN', $options);

        //Employeur non présent dans la liste
        $data                            = [];
        $data['SIREN']                   = '000000000000';
        $data['RAISON_SOCIALE']          = 'Employeur non présent dans la liste';
        $data['NOM_COMMERCIAL']          = 'Employeur non présent dans la liste';
        $data['SOURCE_CODE']             = '000000000000';
        $data['SOURCE_ID']               = $idSource;
        $data['HISTO_DESTRUCTEUR_ID']    = null;
        $data['HISTO_DESTRUCTION']       = null;
        $data['IDENTIFIANT_ASSOCIATION'] = null;
        $data['CRITERE_RECHERCHE']       = \UnicaenApp\Util::reduce('Employeur non présent dans la liste 000000000000');
        $options['where']                = 'SIREN = \'000000000000\'';
        $options['soft-delete']          = true;
        $datas                           = [];
        $datas[]                         = $data;
        $tableEmployeur->merge($datas, 'SIREN', $options);

    }


}