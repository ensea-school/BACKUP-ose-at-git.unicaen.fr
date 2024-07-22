<?php

namespace Entity;

use DateTime;
use Exception;

class Odf
{
    /**
     * @var TypeFormation[] $typeFormations
     */
    public array $typeFormations;

    /**
     * @var Etape[] $etapes
     */
    private array $etapes;

    /**
     * @var ElementPedagogique[] $elementsPedagogiques
     */
    private array $elementsPedagogiques;

    /**
     * @var CheminPedagogique[] $cheminsPedagogiques
     */
    private array $cheminsPedagogiques;

    /**
     * @var Structure[] $structures
     */
    private array $structures;

    /**
     * @var VolumeHoraire[] $volumesHoraires
     */
    private array $volumesHoraires;

    /**
     * @var array $enfants
     */
    private array $enfants;

    /**
     * @var ObjetFormation[] $objetsFormation
     */
    private array $objetsFormation;



    public function getTypeFormations(): array
    {
        return $this->typeFormations;
    }



    public function setTypeFormations(array $typeFormations): void
    {
        $this->typeFormations = $typeFormations;
    }



    public function getEtapes(): array
    {
        return $this->etapes;
    }



    public function setEtapes(?array $etapes): void
    {
        $this->etapes = $etapes;
    }



    public function getElementsPedagogiques(): array
    {
        return $this->elementsPedagogiques;
    }



    public function setElementsPedagogiques(?array $elementsPedagogiques): void
    {
        $this->elementsPedagogiques = $elementsPedagogiques;
    }



    public function getStructures(): array
    {
        return $this->structures;
    }



    public function setStructures(array $structures): void
    {
        $this->structures = $structures;
    }



    public function getEnfants(): array
    {
        return $this->enfants;
    }



    public function setEnfants(array $enfants): void
    {
        $this->enfants = $enfants;
    }



    public function setObjetsFormation(array $objetsFormation): void
    {
        $this->objetsFormation = $objetsFormation;
    }



    public function getObjetsFormation(): array
    {
        return $this->objetsFormation;
    }



    public function addObjetsFormation(ObjetFormation $objetFormation): void
    {
        $this->objetsFormation[$objetFormation->getSourceCode()] = $objetFormation;
    }



    public function getObjetFormationByCode(string $code): ?ObjetFormation
    {
        return $this->objetsFormation[$code];
    }



    public function getCheminsPedagogiques(): array
    {
        return $this->cheminsPedagogiques;
    }



    public function setCheminsPedagogiques(array $cheminsPedagogiques): void
    {
        $this->cheminsPedagogiques = $cheminsPedagogiques;
    }



    /**
     * @return array|VolumeHoraire[]
     */
    public function getVolumesHoraires(): array
    {
        return $this->volumesHoraires;
    }



    public function setVolumesHoraires(array $volumesHoraires): void
    {
        $this->volumesHoraires = $volumesHoraires;
    }



    public function unsetEtape(Etape $etape)
    {
        unset($this->etapes[$etape->getSourceCode()]);
    }



    public function unsetElementPedagogique(ElementPedagogique $element)
    {
        unset($this->elementsPedagogiques[$element->getSourceCode()]);
    }



    /**
     * @throws Exception
     */
    public function traitementPeriode($annee_universitaire, $date_debut_validite, $date_fin_validite): ?array
    {
        $res = [];
        if ($annee_universitaire != null) {
            return ['anneeDebut' => $annee_universitaire, 'anneeFin' => $annee_universitaire];
        }

        if ($date_debut_validite !== null) {
            $dateDebut  = new DateTime($date_debut_validite);
            $yearDebut  = $dateDebut->format('Y');
            $monthDebut = $dateDebut->format('m');
            if ($monthDebut >= 9 && $monthDebut <= 12) {
                $res['anneeDebut'] = $yearDebut;
            } else {
                $res['anneeDebut'] = $yearDebut - 1;
            }
        } else {
            $res['anneeDebut'] = null;
        }


        if ($date_fin_validite !== null) {
            $dateFin = new DateTime($date_fin_validite);

            $yearFin  = $dateFin->format('Y');
            $monthFin = $dateFin->format('m');
            if ($monthFin >= 9 && $monthFin <= 12) {
                $res['anneeFin'] = $yearFin;
            } else {
                $res['anneeFin'] = $yearFin - 1;
            }
        } else {
            $res['anneeFin'] = null;
        }

        if($res['anneeDebut'] && $res['anneeFin'] == null){
            $res['anneeFin'] = $res['anneeDebut'];
        }
        return $res;
    }



    public function SearchingElementPedagogique(string $elementId, array $enfants): ?array
    {
        if (isset($enfants[$elementId])) {
            $elementsRes = [];
            foreach ($enfants[$elementId] as $elementEnfant) {
                $elementsFound = $this->SearchingElementPedagogique($elementEnfant, $enfants);
                foreach ($elementsFound as $elementFound) {
                    $elementsRes[] = $elementFound;
                }
            }

            return $elementsRes;
        } else {
            return [$elementId];
        }
    }



    public function addVolumeHoraire(VolumeHoraire $volumeHoraireNew)
    {
        $this->volumesHoraires[$volumeHoraireNew->getSourceCode()] = $volumeHoraireNew;
    }



    public function unsetVolumeHoraire(VolumeHoraire $volumeHoraire)
    {
        unset($this->volumesHoraires[$volumeHoraire->getSourceCode()]);
    }

}