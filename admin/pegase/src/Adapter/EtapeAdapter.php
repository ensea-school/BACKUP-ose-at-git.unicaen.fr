<?php


namespace Adapter;

use Entity\Odf;
use Exception;

class EtapeAdapter implements DataAdapterInterface
{
    /**
     * @throws Exception
     */
    public function run(Odf $odf): void
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