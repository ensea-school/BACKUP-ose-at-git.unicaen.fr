<?php


namespace Adapter;

use Entity\Odf;
use Entity\VolumeHoraire;
use Exception;
use Unicaen\BddAdmin\Bdd;

class VolumeHoraireAdapter implements DataAdapterInterface
{
    /**
     * @throws Exception
     */
    public function run(Odf $odf, Bdd $pegase = null): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Traitement des volumes horaires récupérés');

        $volumeHoraires = $odf->getVolumesHoraires();
        $elementsPedagogiquesOdf = $odf->getElementsPedagogiques();
        $arborescence = $odf->getEnfants();
        foreach ($volumeHoraires as $volumeHoraire) {
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
                /* On transforme les secondes en heures */
                $heures = $volumeHoraire->getVolumeHoraire();
                $heures = $heures / 3600.0;
                $volumeHoraire->setVolumeHoraire($heures);

                if (array_key_exists($volumeHoraire->getElementId(), $elementsPedagogiquesOdf) && $elementsPedagogiquesOdf[$volumeHoraire->getElementId()] != null){
                    $element = $elementsPedagogiquesOdf[$volumeHoraire->getElementId()];
                    $volumeHoraire->setAnneeDebut($element->getAnneeDebut());
                    $volumeHoraire->setAnneeFin($element->getAnneeFin());
                }
            }
        }
        $console->println('Fin du traitement des volumes horaires récupérés');
    }



    public function versionMin(): float
    {
        // TODO: Implement versionMin() method.
        return 24.0;
    }



    public function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 99.0;
    }
}