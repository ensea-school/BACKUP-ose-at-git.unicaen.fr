<?php


namespace Adapter;

use Entity\ElementPedagogique;
use Entity\Odf;
use Exception;
use Entity\CheminPedagogique;
use Unicaen\BddAdmin\Bdd;
use function PHPUnit\Framework\isEmpty;

class ObjetFormationAdapter implements DataAdapterInterface
{
    /**
     * @throws Exception
     */
    public function run(Odf $odf, Bdd $pegase = null): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Traitement des objet de formation récupérés');


        $enfants                   = $odf->getEnfants();
        $etapes                    = $odf->getEtapes();
        $elementsPedagogiquesToAdd = [];
        $cheminsElementsToAdd      = [];
        $compter                   = 0;
        $codePresent               = [];
        foreach ($etapes as $etape) {
            $elementsPedagogiques = $odf->SearchingElementPedagogique($etape->getSourceCode(), $enfants);

            foreach ($elementsPedagogiques as $elementId) {
                $elementPedagogique = $odf->getObjetFormationByCode($elementId);

                if ($elementPedagogique == null) {
                    continue;
                }
                $periodesAnnee = $odf->traitementPeriode($elementPedagogique->getAnneeUniversitaire(), $elementPedagogique->getDateDebut(), $elementPedagogique->getDateFin());
                if ($periodesAnnee['anneeDebut'] == null || $periodesAnnee['anneeFin'] == null) {
                    continue;
                } else {
                    $elementPedagogique->setAnneeDebut($periodesAnnee['anneeDebut']);
                    $elementPedagogique->setAnneeFin($periodesAnnee['anneeFin']);
                }

                if (!isset($elementsPedagogiquesToAdd[$elementPedagogique->getSourceCode()])) {
                    $elementToAdd = new ElementPedagogique();
                    $elementToAdd->setLibelle($elementPedagogique->getLibelle());
                    $elementToAdd->setSourceCode($elementPedagogique->getSourceCode());
                    $elementToAdd->setStructureId($elementPedagogique->getStructureId());
                    $elementToAdd->setTauxFoad($elementPedagogique->getTauxFoad());

                    $elementToAdd->setAnneeDebut($periodesAnnee['anneeDebut']);
                    $elementToAdd->setAnneeFin($periodesAnnee['anneeFin']);
                    $increment = 0;
                    $code      = $elementPedagogique->getCode();
                    for ($i = $periodesAnnee['anneeDebut']; $i <= $periodesAnnee['anneeFin']; $i++) {
                        if (!isset($codePresent[$i])) {
                            $codePresent[$i] = [];
                        }
                        while (isset($codePresent[$i][$code])) {
                            $increment++;
                            $code = $elementPedagogique->getCode() . '-' . $increment;
                        }
                    }
                    $elementToAdd->setCode($code);
                    if ($elementToAdd->getEtapeId() == null) {
                        $elementToAdd->setEtapeId($etape->getSourceCode());
                    }

                    for ($i = $periodesAnnee['anneeDebut']; $i <= $periodesAnnee['anneeFin']; $i++) {
                        $codePresent[$i][$code] = $code;
                    }


                    $elementsPedagogiquesToAdd[$elementToAdd->getSourceCode()] = $elementToAdd;

                } else {
                    $elemTest = $elementsPedagogiquesToAdd[$elementPedagogique->getSourceCode()];

                    if ($elemTest->getEtapeId() == null) {
                        $elemTest->setEtapeId($etape->getSourceCode());
                    }
                }

                $cheminElementPedagogique = new CheminPedagogique();
                $cheminElementPedagogique->setId($etape->getSourceCode() . '_' . $elementPedagogique->getSourceCode());
                $cheminElementPedagogique->setElementPedagogiqueCode($elementsPedagogiquesToAdd[$elementPedagogique->getSourceCode()]->getCode());
                $cheminElementPedagogique->setElementPedagogiqueId($elementPedagogique->getSourceCode());
                $cheminElementPedagogique->setEtapeCode($etape->getCode());
                $cheminElementPedagogique->setEtapeId($etape->getSourceCode());
                $cheminElementPedagogique->setAnneeFin($periodesAnnee['anneeFin']);
                $cheminElementPedagogique->setAnneeDebut($periodesAnnee['anneeDebut']);

                $cheminsElementsToAdd[$cheminElementPedagogique->getId()] = $cheminElementPedagogique;
            }
        }


        $odf->setElementsPedagogiques($elementsPedagogiquesToAdd);
        $odf->setCheminsPedagogiques($cheminsElementsToAdd);

        $console->println('Fin du traitement des objet de formation récupérés');
    }



//0d63e468-890c-4863-b55c-275b40ba99b4

    public function versionMin(): float
    {
        // TODO: Implement versionMin() method.
        return 0.0;
    }



    public function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 99.0;
    }

}