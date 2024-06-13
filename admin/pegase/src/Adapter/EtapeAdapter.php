<?php


namespace Adapter;

use Entity\Odf;
use Exception;
use Unicaen\BddAdmin\Bdd;

class EtapeAdapter implements DataAdapterInterface
{
    /**
     * @throws Exception
     */
    public function run(Odf $odf, Bdd $pegase = null): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Traitement des étapes récupérées');

        $codePresent = [];
        $etapes      = $odf->getEtapes();
        foreach ($etapes as $etape) {

            $periodesAnnee = $odf->traitementPeriode($etape->getAnneeUniv(), $etape->getDateDebut(), $etape->getDateFin());
            if ($periodesAnnee['anneeDebut'] == null || $periodesAnnee['anneeFin'] == null) {
                $odf->unsetEtape($etape);
                continue;
            }

            if($etape->getTypeFormationId() == null) {
                $sql = 'SELECT om_enfant.id as id_objet, COALESCE(om_parent_n2.code_type_diplome, om_parent.code_type_diplome) as code_type_diplome
                FROM schema_odf.enfant e
                LEFT JOIN schema_odf.enfant e_n2 ON e.id_objet_maquette_parent = e_n2.id_objet_maquette
                LEFT JOIN schema_odf.objet_maquette om_parent_n2 ON e_n2.id_objet_maquette_parent = om_parent_n2.id

                JOIN schema_odf.objet_maquette om_enfant ON e.id_objet_maquette = om_enfant.id
                JOIN schema_odf.objet_maquette om_parent ON om_parent.id = e.id_objet_maquette_parent
                WHERE e.id_objet_maquette = :id_objet';
                $param['id_objet'] = $etape->getSourceCode();
                $res =  $pegase->select($sql, $param, ['fetch' => Bdd::FETCH_EACH]);
                while ($parent = $res->next()) {
                    $etape->setTypeFormationId($parent['code_type_diplome']);
                    break;
                }
            }

            $code      = $etape->getCode();
            $increment = 0;

            $domaine = $etape->getDomaineFonctionnelId();
            switch ($domaine) {
                case 'L':
                    $domaine = 'D101';
                break;
                case 'M':
                    $domaine = 'D102';
                break;
                case 'D':
                    $domaine = 'D103';
                break;
                default:
                    $domaine = 'D101';
                break;
            }
            $etape->setDomaineFonctionnelId($domaine);

            $etape->setAnneeDebut($periodesAnnee['anneeDebut']);
            $etape->setAnneeFin($periodesAnnee['anneeFin']);
            for ($i = $periodesAnnee['anneeDebut']; $i <= $periodesAnnee['anneeFin']; $i++) {
                if (!isset($codePresent[$i])) {
                    $codePresent[$i] = [];
                }
                while (isset($codePresent[$i][$code])) {
                    $increment++;
                    $code = $etape->getCode() . '-' . $increment;
                }
            }
            $etape->setCode($code);
            for ($i = $periodesAnnee['anneeDebut']; $i <= $periodesAnnee['anneeFin']; $i++) {
                $codePresent[$i][$code] = $code;
            }
        }

        $console->println('Fin du traitement des étapes récupérées');
    }



    public
    function versionMin(): float
    {
        // TODO: Implement versionMin() method.
        return 0.0;
    }



    public
    function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 99.0;
    }
}