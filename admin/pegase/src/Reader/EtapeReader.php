<?php

namespace Reader;

use Entity\Etape;
use Entity\Odf;
use OseAdmin;
use Unicaen\BddAdmin\Bdd;

class EtapeReader implements ReaderInterface
{
    public function run(Bdd $pegase, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Récupération des étapes en cours');

        $config = OseAdmin::instance()->config()->get('pegase');
        if (isset($config['etapes'])) {
            $etapes = $config['etapes'];
        } else {
            $etapes = null;
        }
        if (isset($config['type_objet_maquette'])) {
            $typeObjetMaquettes = $config['type_objet_maquette'];
        } else {
            $typeObjetMaquettes = null;
        }
        $param = [];

        $sql = 'select
                    e.id,
                    om.id as id_objet_formation,
                    om.code_type_objet_formation as code_type,
                    om.libelle_long as libelle_long,
                    om.id_formation_porteuse,
                    td.code as code_type_formation,
                                        CASE
                        WHEN om_formation.code_structure_principale IS NULL
                        THEN om.code_structure_principale
                        ELSE om_formation.code_structure_principale
                    END as code_structure,
                    esp.annee_universitaire as annee_universitaire,
                    esp.date_debut_validite as date_debut_validite,
                    esp.date_fin_validite as date_fin_validite,
                    om.code as code,
                    cf.code_bcn,
                    om.type_objet_maquette
                FROM schema_odf.objet_maquette om
                LEFT JOIN schema_odf.objet_maquette om_formation on om.id_formation_porteuse = om_formation.id 
                JOIN schema_odf.espace esp on esp.id = om.id_espace
                JOIN schema_odf.enfant e ON e.id_objet_maquette_parent = om.id
                left JOIN schema_ref.type_diplome td ON om.code_type_diplome = td.code
                left JOIN schema_ref.cursus_formation cf ON td.id_cursus_formation = cf.id';

        $etapeOuTypeObjetMaquette = 0;
        if (isset($etapes) && $etapes != null && $etapes != '') {
            $i = 0;
            foreach ($etapes as $etape) {
                $nameCode = 'codeType' . $i;
                if ($i != 0) {
                    $sql .= ' OR';
                } else {
                    $sql .= ' WHERE';
                }
                $sql              .= ' om.code_type_objet_formation = :' . $nameCode;
                $param[$nameCode] = $etape;
                $i++;
            }
            $etapeOuTypeObjetMaquette++;
        }
        if (isset($typeObjetMaquettes) && $typeObjetMaquettes != null && $typeObjetMaquettes != '') {
            $i = 0;
            foreach ($typeObjetMaquettes as $typeObjetMaquette) {
                $nameCode = 'codeType' . $i;
                if ($i != 0 || $etapeOuTypeObjetMaquette != 0) {
                    $sql .= ' OR';
                } else {
                    $sql .= ' WHERE';
                }
                $sql              .= ' om.type_objet_maquette = :' . $nameCode;
                $param[$nameCode] = $typeObjetMaquette;
                $i++;
            }
            $etapeOuTypeObjetMaquette++;
        }

        if ($etapeOuTypeObjetMaquette == 0) {
            $sql2    = 'select e.id_objet_maquette FROM schema_odf.enfant e GROUP BY e.id_objet_maquette';
            $resList = $pegase->select($sql2);

            $sql .= ' WHERE om.id NOT IN (';

            $i = 0;
            foreach ($resList as $objet) {
                $paramName = 'list' . $i;
                if ($i != 0) {
                    $sql .= ', ';
                }
                $sql               .= ':' . $paramName;
                $param[$paramName] = $objet['id_objet_maquette'];
                $i++;
            }

            $sql .= ');';
        }


        $res        = $pegase->select($sql, $param, ['fetch' => Bdd::FETCH_EACH]);
        $listEtapes = [];
        while ($etape = $res->next()) {
            $newEtape = new Etape();
            $newEtape->setLibelle($etape['libelle_long']);
            $newEtape->setSourceCode($etape['id_objet_formation']);
            $newEtape->setDateDebut($etape['date_debut_validite']);
            $newEtape->setDateFin($etape['date_fin_validite']);
            $newEtape->setAnneeUniv($etape['annee_universitaire']);
            $newEtape->setTypeFormationId($etape['code_type_formation']);
            $newEtape->setStructureId($etape['code_structure']);
            $newEtape->setCode($etape['code']);
            $newEtape->setDomaineFonctionnelId($etape['code_bcn']);

            $listEtapes[$newEtape->getSourceCode()] = $newEtape;
        }


        $odf->setEtapes($listEtapes);
        $console->println('Les étapes ont été récupéré');

    }



    public
    function versionMin(): float
    {
        // TODO: Implement versionMin() method.
        return 24.0;
    }



    public
    function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 24.0;
    }

}