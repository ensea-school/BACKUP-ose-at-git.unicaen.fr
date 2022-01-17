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
     * @var bool
     */
    protected $showHisto = false;



    /**
     * @param string  $critere
     * @param integer $limit
     *
     * @return array
     */
    public function rechercher($critere, $limit = 50, string $key = ':CODE')
    {
        try {
            return $this->rechercheGenerique($critere, $limit, $key, false);
        } catch (\Exception $e) {
            return $this->rechercheGenerique($critere, $limit, $key, true);
        }
    }



    /**
     * @return bool
     */
    public function isShowHisto(): bool
    {
        return $this->showHisto;
    }



    /**
     * @param bool $showHisto
     *
     * @return RechercheProcessus
     */
    public function setShowHisto(bool $showHisto): RechercheProcessus
    {
        $this->showHisto = $showHisto;

        return $this;
    }



    /**
     * @param string  $critere
     * @param integer $limit
     *
     * @return array
     */
    public function rechercherLocalement($critere, $limit = 50, string $key = ':CODE')
    {
        return $this->rechercheGenerique($critere, $limit, $key, true);
    }



    private function sqlLocale(): string
    {
        $sql = "
        SELECT
          i.id,
          i.code,
          i.code_rh,
          i.statut_id,
          i.nom_usuel,
          i.nom_patronymique,
          i.prenom,
          i.date_naissance,
          s.libelle_court structure,
          c.libelle_long civilite,
          si.libelle statut,
          i.critere_recherche critere,
          i.annee_id,
          i.histo_destruction
        FROM
          intervenant i
          LEFT JOIN structure s ON s.id = i.structure_id
          LEFT JOIN civilite c ON c.id = i.civilite_id
          LEFT JOIN statut_intervenant si ON si.id = i.statut_id
        ";
        if (!$this->showHisto) {
            $sql .= "WHERE i.histo_destruction IS NULL";
        }

        return $sql;
    }



    private function sqlSource(): string
    {
        return "
        SELECT
          null id,
          i.code,
          i.statut_id,
          i.nom_usuel,
          i.nom_patronymique,
          i.prenom,
          i.date_naissance,
          s.libelle_court structure,
          c.libelle_long civilite,
          si.libelle statut,
          i.critere_recherche critere,
          i.annee_id,
          null histo_destruction
        FROM
          src_intervenant i
          LEFT JOIN structure s ON s.id = i.structure_id
          LEFT JOIN civilite c ON c.id = i.civilite_id
          LEFT JOIN statut_intervenant si ON si.id = i.statut_id
        ";
    }



    protected function makeKey(array $data, string $key): string
    {
        $resKey = $key;
        foreach ($data as $k => $v) {
            $resKey = str_replace(':' . $k, $v, $resKey);
        }

        return $resKey;
    }



    private function rechercheGenerique($critere, $limit = 50, string $key = ':CODE', $onlyLocale = false)
    {
        if (strlen($critere) < 2) return [];

        $anneeId = (int)$this->getServiceContext()->getAnnee()->getId();

        $critere  = Util::reduce($critere);
        $criteres = explode('_', $critere);

        $sqlSource = '';
        if (!$onlyLocale) {
            $sqlSource = ' UNION ALL ' . $this->sqlSource();
        }

        $sql     = '
        WITH vrec AS (
            ' . $this->sqlLocale() . '
            ' . $sqlSource . '  
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
            $orc[] = 'code LIKE \'%' . $criCode . '%\'';
        }
        $orc = implode(' OR ', $orc);
        $sql .= ' AND (' . $orc . ') ORDER BY nom_usuel, prenom';

        $intervenants = [];


        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        while ($r = $stmt->fetch()) {
            $k = $this->makeKey($r, $key);
            if (!isset($intervenants[$k])) {
                $intervenants[$k] = [
                    'civilite'         => $r['CIVILITE'],
                    'nom'              => $r['NOM_USUEL'],
                    'prenom'           => $r['PRENOM'],
                    'date-naissance'   => new \DateTime($r['DATE_NAISSANCE']),
                    'structure'        => $r['STRUCTURE'],
                    'statut'           => $r['STATUT'],
                    'numero-personnel' => $r['CODE_RH'],
                    'destruction'      => $r['HISTO_DESTRUCTION'],
                ];
            } else {
                if ($intervenants[$k]['destruction'] && !$r['destruction']) {
                    $intervenants[$k]['destruction'] = null;
                }
                if ($intervenants[$k]['statut'] && !is_array($intervenants[$k]['statut'])) {
                    $intervenants[$k]['statut'] = [$intervenants[$k]['statut']];
                }
                if (is_array($intervenants[$k]['statut'])) {
                    $intervenants[$k]['statut'][] = $r['STATUT'];
                } else {
                    $intervenants[$k]['statut'] = $r['STATUT'];
                }
            }
        }

        return $intervenants;
    }

}