<?php

namespace Application\Processus\Intervenant;


use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Util;

class RechercheProcessus
{
    use EntityManagerAwareTrait;
    use ContextServiceAwareTrait;



    /**
     * @param string  $critere
     * @param integer $limit
     *
     * @return array
     */
    public function rechercher($critere, $limit = 50)
    {
        if (strlen($critere) < 2) return [];

        $anneeId = (int)$this->getServiceContext()->getAnnee()->getId();

        $critere  = Util::reduce($critere);
        $criteres = explode('_', $critere);

        $sql     = '
        WITH vrec AS (
        SELECT
          i.id,
          i.source_code,
          i.nom_usuel,
          i.nom_patronymique,
          i.prenom,
          i.date_naissance,
          s.libelle_court structure,
          c.libelle_long civilite,
          i.critere_recherche critere,
          i.annee_id
        FROM
          intervenant i
          JOIN structure s ON s.id = i.structure_id
          JOIN civilite c ON c.id = i.civilite_id
        WHERE
          i.histo_destruction IS NULL
          
        UNION ALL
        
        SELECT
          null id,
          i.source_code,
          i.nom_usuel,
          i.nom_patronymique,
          i.prenom,
          i.date_naissance,
          s.libelle_court structure,
          c.libelle_long civilite,
          i.critere_recherche critere,
          i.annee_id
        FROM
          src_intervenant i
          JOIN structure s ON s.id = i.structure_id
          JOIN civilite c ON c.id = i.civilite_id
        )
        SELECT * FROM vrec WHERE 
          rownum <= ' . (int)$limit . ' AND annee_id = ' . $anneeId;
        $sqlCri  = '';
        $criCode = 0;

        foreach ($criteres as $c) {
            $cc = (int)$c;
            if (0 === $cc) {
                if ($sqlCri != '') $sqlCri .= ' AND ';
                $sqlCri .= 'critere LIKE q\'[%' . $c . '%]\'';
            } else {
                $criCode = $cc;
            }
        }
        $orc = [];
        if ($sqlCri != '') {
            $orc[] = '(' . $sqlCri . ')';
        }
        if ($criCode) {
            $orc[] = 'source_code LIKE \'%' . $criCode . '%\'';
        }
        $orc = implode(' OR ', $orc);
        $sql .= ' AND (' . $orc . ') ORDER BY nom_usuel, prenom';

        $intervenants = [];

        try {
            $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
            while ($r = $stmt->fetch()) {
                $intervenants[$r['SOURCE_CODE']] = [
                    'civilite'         => $r['CIVILITE'],
                    'nom'              => $r['NOM_USUEL'],
                    'prenom'           => $r['PRENOM'],
                    'date-naissance'   => new \DateTime($r['DATE_NAISSANCE']),
                    'structure'        => $r['STRUCTURE'],
                    'numero-personnel' => $r['SOURCE_CODE'],
                ];
            }
        } catch (\Exception $e) {
            // à traiter si la vue source intervenant n'existe pas!!
        }

        return $intervenants;
    }

}