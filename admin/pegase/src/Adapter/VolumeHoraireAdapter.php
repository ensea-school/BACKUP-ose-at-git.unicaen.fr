<?php


namespace Adapter;

use Entity\Odf;
use Entity\VolumeHoraire;
use Exception;

class VolumeHoraireAdapter implements DataAdapterInterface
{
    /**
     * @throws Exception
     */
    public function run(Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Traitement des volumes horaires récupérées');

        $volumeHoraires       = $odf->getVolumesHoraires();
        $elementsPedagogiquesOdf = $odf->getElementsPedagogiques();
        $arborescence         = $odf->getEnfants();
        foreach ($volumeHoraires as $volumeHoraire) {
            /** @var VolumeHoraire $volumeHoraire */
            $heures = $volumeHoraire->getVolumeHoraire();
            $heures = $heures / 3600.0;
            if (isset($arborescence[$volumeHoraire->getElementId()])) {
//On ne fait plus ruisseler les heures sur les EC, on retire juste le volume horaire de la liste
                //                $elementsPedagogiques = $odf->SearchingElementPedagogique($volumeHoraire->getElementId(), $arborescence);
//                foreach ($elementsPedagogiques as $elementPedagogique) {
//                    $volumeHoraireNew = new VolumeHoraire();
//                    $volumeHoraireNew->setVolumeHoraire($heures);
//                    $volumeHoraireNew->setTypeIntervention($volumeHoraire->getTypeIntervention());
//                    $volumeHoraireNew->setElementId($elementPedagogique);
//                    $volumeHoraireNew->setNombreGroupe($volumeHoraire->getNombreGroupe());
//                    $element = $elementsPedagogiquesOdf[$elementPedagogique];
//                    $volumeHoraireNew->setAnneeDebut($element->getAnneeDebut());
//                    $volumeHoraireNew->setAnneeFin($element->getAnneeFin());
//                    $volumeHoraireNew->setSourceCode($volumeHoraireNew->getElementId() . '_' . $volumeHoraireNew->getTypeIntervention());
//                    $odf->addVolumeHoraire($volumeHoraireNew);
//                    $volumeHoraires[$volumeHoraireNew->getSourceCode()] = $volumeHoraireNew;
//                }
                $odf->unsetVolumeHoraire($volumeHoraire);

            } else {
                $element = $elementsPedagogiquesOdf[$volumeHoraire->getElementId()];
                $volumeHoraire->setAnneeDebut($element->getAnneeDebut());
                $volumeHoraire->setAnneeFin($element->getAnneeFin());
                $volumeHoraire->setVolumeHoraire($heures);
            }
        }
        $console->println('Fin du traitement des étapes récupérées');
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
        return 99.0;
    }
}