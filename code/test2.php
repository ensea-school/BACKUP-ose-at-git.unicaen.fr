<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */





class Iparser
{
    public  $bdd;

    public  $data        = [];

    public  $actions     = [];

    public  $allActions  = [];

    public  $fncs        = [];

    public  $fncsStatuts = [];

    private $cFnc;

    private $cAnnee;

    private $cCode;

    public  $statuts     = [];



    public function fetch()
    {
        $filter = "
        AND annee_id IN (2020)
        --AND code = 1002
        ";

        $bdd = adminBdd();

        $sts = $bdd->select('select si.id, ti.libelle || \' <br /><br /> \' || si.libelle libelle FROM statut_intervenant si join type_intervenant ti on ti.id = si.type_intervenant_id');
        foreach ($sts as $st) {
            $this->statuts[$st['ID']] = $st['LIBELLE'];
        }

        $sql = "

        with idata as (select 
           intervenant_id, listagg(cc.t, ', ') within group( order by cc.t) tbls
        from 
          (
                  select count(*) || ' AGREMENT'                t, intervenant_id from AGREMENT where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' CONTRAT'                 t, intervenant_id from CONTRAT where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' INTERVENANT_DOSSIER'     t, intervenant_id from INTERVENANT_DOSSIER where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' MODIFICATION_SERVICE_DU' t, intervenant_id from MODIFICATION_SERVICE_DU where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' PIECE_JOINTE'            t, intervenant_id from PIECE_JOINTE where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' SERVICE'                 t, intervenant_id from SERVICE where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' SERVICE_REFERENTIEL'     t, intervenant_id from SERVICE_REFERENTIEL where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' VALIDATION'              t, intervenant_id from VALIDATION where histo_destruction is null GROUP BY intervenant_id
        union all select count(*) || ' FORMULE_RESULTAT'        t, intervenant_id from FORMULE_RESULTAT where 1=1 GROUP BY intervenant_id
        ) cc
        GROUP BY intervenant_id)
        SELECT
          i.id, i.annee_id, i.code, i.source_code, i.statut_id, i.sync_statut, i.histo_creation, i.histo_destruction, idata.tbls
        FROM
          intervenant i
          LEFT JOIN idata ON idata.intervenant_id = i.id
        where 
            1=1 $filter
        ";

        $s = $bdd->select($sql, [], ['fetch' => \BddAdmin\Bdd::FETCH_EACH]);
        while ($i = $s->next()) {
            if (!isset($this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']])) {
                $this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']] = ['i' => [], 's' => [], 'd' => []];
            }
            $this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']]['i'][] = $i;
        }


        $sql = "
        SELECT * FROM V_DIFF_INTERVENANT WHERE 1=1 $filter
        ";

        $s = $bdd->select($sql, [], ['fetch' => \BddAdmin\Bdd::FETCH_EACH]);
        while ($i = $s->next()) {
            if (!isset($this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']])) {
                $this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']] = ['i' => [], 's' => [], 'd' => []];
            }
            $this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']]['d'][] = 'diff';
        }


        $sql = "SELECT 
          i.annee_id, i.code, i.statut_id, i.source_code, i.validite_debut, i.validite_fin 
        FROM
          src_intervenant i
        WHERE
          1=1 $filter
        ";
        $s   = $bdd->select($sql, [], ['fetch' => \BddAdmin\Bdd::FETCH_EACH]);
        while ($i = $s->next()) {
            if (!isset($this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']])) {
                $this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']] = ['i' => [], 's' => [], 'd' => []];
            }
            $this->data[$i['ANNEE_ID']][$i['CODE']][$i['STATUT_ID']]['s'][] = $i;
        }
    }



    public function makeFncs()
    {
        /* On élimine s'il n'y a aucun diff */
        $this->fncs['Aucune différence'] = function ($annee, $code, $statut, array $intervenants, array $sources, array $diff) {
            if (empty($diff)) return true;
        };
        /* Recherche des fiches uniques des 2 côtés et avec les mêmes source_code *
        $this->fncsStatuts['Fiches uniques des 2 côtés avec mêmes sources codes'] = function ($annee, $code, array $statuts) {
            if (1 == count($statuts)) {
                $statuts = current($statuts);
                if (1 == count($statuts['i']) && 1 == count($statuts['s'])) {
                    $i = $statuts['i'][0];
                    $s = $statuts['i'][0];
                    if ($i['SOURCE_CODE'] == $s['SOURCE_CODE']) {
                        return true;  // pas de problème : cas le + simple + 1 statut et tout matche
                    }
                }
            }
        };

        /* Ca matche au niveau d'un seul statut *
        $this->fncs['Fiches avec des source_code égaux'] = function ($annee, $code, $statut, array $intervenants, array $sources, array $diff) {

            if (1 == count($intervenants) && 1 == count($sources)) {
                if ($intervenants[0]['SOURCE_CODE'] == $sources[0]['SOURCE_CODE']) {

                    return true; // on a traité le pb
                }
            }
        };

        /* Recherche des fiches vides et pas dans la vue source *
        $this->fncs['Fiches avec des source_code différents'] = function ($annee, $code, $statut, array $intervenants, array $sources, array $diff) {

            if (1 == count($intervenants) && 1 == count($sources)) {
                if ($intervenants[0]['SOURCE_CODE'] != $sources[0]['SOURCE_CODE']) {
                    $sql = 'UPDATE INTERVENANT SET source_code = \'' . $sources[0]['SOURCE_CODE'] . '\' WHERE id = ' . $intervenants[0]['ID'] . ';';
                    $this->addAction($sql);

                    return true; // on a traité le pb
                }
            }
        };

        /* Recherche des fiches vides et pas dans la vue source *
        $this->fncs['Fiches vides sans aucune source'] = function ($annee, $code, $statut, array $intervenants, array $sources, array $diff) {
            if (1 == count($intervenants) && 0 == count($sources)) {
                $i = $intervenants[0];
                if (null === $i['TBLS']) {
                    $this->addAction('DELETE FROM intervenant WHERE id = ' . $i['ID'] . ' AND annee_id = ' . $annee . ';');

                    return true;
                }
            }
        };

        /* Anciens codes sans nouvelle fiche en façe *
        $this->fncs[] = function ($annee, $code, $statut, array $intervenants, array $sources, array $diff) {

            if (1 == count($intervenants) && 0 == count($sources)) {
                $c = $intervenants[0]['SOURCE_CODE'];
                $c = str_replace('-', '', $c);
                if ((string)(int)$c === $c) {
                    return true; // ancien code
                }
            }
        };

        /**/
    }



    public function exec()
    {
        foreach ($this->fncsStatuts as $currentFnc => $fnc) {
            $this->cFnc = $currentFnc;
            foreach ($this->data as $annee => $dataa) {
                $this->cAnnee = $annee;
                foreach ($dataa as $code => $ds) {
                    $this->cCode = $code;
                    $res         = $fnc($annee, $code, $ds);
                    if (true === $res) {
                        unset($this->data[$annee][$code]);
                        if (empty($this->data[$annee])) {
                            unset($this->data[$annee]);
                        }
                    }
                }
            }
        }

        foreach ($this->fncs as $currentFnc => $fnc) {
            $this->cFnc = $currentFnc;
            foreach ($this->data as $annee => $dataa) {
                $this->cAnnee = $annee;
                foreach ($dataa as $code => $ds) {
                    $this->cCode = $code;
                    foreach ($ds as $statut => $dsi) {
                        $res = $fnc($annee, $code, $statut, $dsi['i'], $dsi['s'], $dsi['d']);
                        if (true === $res) {
                            unset($this->data[$annee][$code][$statut]);
                            if (empty($this->data[$annee][$code])) {
                                unset($this->data[$annee][$code]);
                            }
                            if (empty($this->data[$annee])) {
                                unset($this->data[$annee]);
                            }
                        }
                    }
                }
            }
        }
    }



    public function addAction(string $sql)
    {
        if (!isset($this->actions[$this->cFnc])) {
            $this->actions[$this->cFnc] = [];
        }
        $this->actions[$this->cFnc][] = $sql;
    }



    public function display()
    {
        foreach ($this->data as $annee => $dataa) {
            foreach ($dataa as $code => $datai) {
                echo '<h2>Intervenant Code ' . $code . ', Année ' . $annee . '</h2>';
                echo '<table class="table table-bordered table-condensed table-extra-condensed table-hover">';
                echo "<tr><th>Statut</th><th>Fiches OSE</th><th>Fiches Sources</th></tr>";
                foreach ($datai as $statut => $di) {
                    echo "<tr><th>" . $this->statuts[$statut] . "</th><th>";
                    foreach ($di['i'] as $dii) {
                        unset($dii['CODE']);
                        unset($dii['STATUT_ID']);
                        unset($dii['ANNEE_ID']);
                        var_dump($dii);
                    }
                    echo '</th><th>';
                    foreach ($di['s'] as $dii) {
                        unset($dii['CODE']);
                        unset($dii['STATUT_ID']);
                        unset($dii['ANNEE_ID']);
                        var_dump($dii);
                    }
                    echo '</th><th>';
                    foreach ($di['d'] as $did) {
                        /*unset($did['CODE']);
                        unset($did['STATUT_ID']);
                        unset($did['ANNEE_ID']);
                        var_dump($did);*/
                        echo $did;
                    }
                    echo '</th></tr>';
                }
                echo '</table>';
            }
        }

        echo '<pre>';
        foreach ($this->actions as $fnc => $actions) {
            echo "\n\n-- $fnc\n";
            echo implode("\n", $actions);
        }
        echo '</pre>';
    }
}





$ip = new IParser();
$ip->fetch();
$ip->makeFncs();
$ip->exec();
$ip->display();