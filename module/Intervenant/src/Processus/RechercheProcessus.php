<?php

namespace Intervenant\Processus;


use Application\Provider\Privileges;
use Application\Service\ContextService;
use Doctrine\ORM\EntityManager;
use Unicaen\Framework\Authorize\Authorize;
use UnicaenApp\Util;

class RechercheProcessus
{

    /**
     * @var bool
     */
    protected $showHisto = false;



    public function __construct(
        private readonly EntityManager  $entityManager,
        private readonly ContextService $context,
        private readonly Authorize      $authorize
    )
    {
    }



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



    private function rechercheGenerique($critere, $limit = 50, string $key = ':CODE', $onlyLocale = false)
    {
        if (strlen($critere) < 2) return [];

        $anneeId = (int)$this->context->getAnnee()->getId();
        $critere = self::reduce($critere);

        $criteres = explode('_', $critere);

        $sql     = '
        WITH vrec AS (
            ' . $this->sqlLocale() . '  
        )
        SELECT * FROM vrec WHERE 
          rownum <= ' . (int)$limit . ' AND annee_id = ' . $anneeId;
        $sqlCri  = '';
        $criCode = 0;

        foreach ($criteres as $c) {
            $cc = (int)$c;
            if (0 === $cc) {
                if ($sqlCri != '') $sqlCri .= ' AND ';
                $sqlCri .= 'instr(\' \' || critere ,\' ' . $c . '\') > 0';
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


        $stmt = $this->entityManager->getConnection()->executeQuery($sql);
        while ($r = $stmt->fetch()) {
            $k = $this->makeKey($r, $key);
            if (!isset($intervenants[$k])) {
                $intervenants[$k] = [
                    'canView'                => $this->authorize->isAllowedPrivilege(Privileges::INTERVENANT_FICHE),
                    'civilite'               => $r['CIVILITE'],
                    'nom'                    => $r['NOM_USUEL'],
                    'prenom'                 => $r['PRENOM'],
                    'date-naissance'         => new \DateTime($r['DATE_NAISSANCE']),
                    'structure'              => $r['STRUCTURE'],
                    'statut'                 => $r['STATUT'],
                    'typeIntervenantCode'    => $r['TYPE_INTERVENANT_CODE'],
                    'typeIntervenantLibelle' => $r['TYPE_INTERVENANT_LIBELLE'],
                    'numero-personnel'       => $r['CODE_RH'],
                    'destruction'            => $r['HISTO_DESTRUCTION'],
                    'code'                   => $r['CODE'],

                ];
            } else {
                if ($intervenants[$k]['destruction'] && !$r['HISTO_DESTRUCTION']) {
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



    private function sqlSource(): string
    {
        return "
        SELECT
          NULL id,
          i.code,
          i.statut_id,
          ti.code type_intervenant_code,
          ti.libelle type_intervenant_libelle,
          i.nom_usuel,
          i.nom_patronymique,
          i.prenom,
          i.date_naissance,
          s.libelle_court structure,
          c.libelle_long civilite,
          si.libelle statut,
          i.critere_recherche critere,
          i.annee_id,
          NULL histo_destruction
        FROM
          src_intervenant i
          LEFT JOIN structure s ON s.id = i.structure_id
          LEFT JOIN civilite c ON c.id = i.civilite_id
          LEFT JOIN statut si ON si.id = i.statut_id
          LEFT JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
        ";
    }



    private function sqlLocale(): string
    {
        $sql = "
        SELECT
          i.id,
          i.code,
          i.code_rh,
          i.statut_id,
          ti.code type_intervenant_code,
          ti.libelle type_intervenant_libelle,        
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
          LEFT JOIN statut si ON si.id = i.statut_id
          LEFT JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
        ";
        if (!$this->showHisto) {
            $sql .= "WHERE i.histo_destruction IS NULL";
        }

        return $sql;
    }



    protected function makeKey(array $data, string $key): string
    {
        $resKey = $key;
        foreach ($data as $k => $v) {
            if (!empty($v)) {
                $resKey = str_replace(':' . $k, $v, $resKey);
            }
        }

        return $resKey;
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



    /**
     * @param string $str      Chaine à nettoyer
     * @param string $encoding Encodage en sortie
     *
     * @return string
     */

    private static function reduce(string $str, string $encoding = 'UTF-8'): string
    {
        $from = 'ÀÁÂÃÄÅÇÐÈÉÊËÌÍÎÏÒÓÔÕÖØÙÚÛÜŸÑàáâãäåçðèéêëìíîïòóôõöøùúûüÿñ€@()…,<> /?€%!":';
        $to   = 'aaaaaacdeeeeiiiioooooouuuuynaaaaaacdeeeeiiiioooooouuuuynea________________';

        $str = strtolower(Util::strtr($str, $from, $to, false, $encoding));

        //On escape les quotes simples pour la requête sql
        return str_replace("'", "''", $str);
    }


}
