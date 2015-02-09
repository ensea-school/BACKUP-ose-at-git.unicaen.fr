<?php

namespace Application\Entity;

use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Validation;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;

/**
 * 
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireReferentielListe
{
    /**
     * @var ServiceReferentiel|boolean
     */
    protected $service;

    /**
     * @var TypeVolumeHoraire|boolean
     */
    protected $typeVolumeHoraire = false;

    /**
     * @var Validation|boolean
     */
    protected $validation = false;

    /**
     *
     * @param ServiceReferentiel $service
     */
    function __construct(ServiceReferentiel $service)
    {
        $this->setService($service);
    }

    /**
     *
     * @return ServiceReferentiel
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     *
     * @param ServiceReferentiel $service
     * @return self
     */
    public function setService(ServiceReferentiel $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     *
     * @return TypeVolumeHoraire|boolean
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }

    /**
     *
     * @param TypeVolumeHoraire|boolean $typeVolumeHoraire
     * @return self
     */
    public function setTypeVolumeHoraire($typeVolumeHoraire)
    {
        if (!(is_bool($typeVolumeHoraire) || null === $typeVolumeHoraire || $typeVolumeHoraire instanceof TypeVolumeHoraire)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        return $this;
    }

    /**
     *
     * @return Validation|boolean
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     *
     * @param Validation|boolean $validation
     * @return self
     */
    public function setValidation($validation)
    {
        if (!(is_bool($validation) || null === $validation || $validation instanceof Validation)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->validation = $validation;
        return $this;
    }

    /**
     * Détermine si un volume horaire répond aux critères de la liste ou non
     * 
     * @param VolumeHoraireReferentiel $volumeHoraire
     * @return boolean
     */
    public function match(VolumeHoraireReferentiel $volumeHoraire)
    {
        if ($volumeHoraire->getRemove()) { // Si le volume horaire est en cours de suppression
            return false;
        }
        if (false !== $this->typeVolumeHoraire) {
            $typeVolumeHoraire = $volumeHoraire->getTypeVolumeHoraire();
            if (true === $this->typeVolumeHoraire) {
                if (null === $typeVolumeHoraire)
                    return false;
            }else {
                if ($typeVolumeHoraire !== $this->typeVolumeHoraire)
                    return false;
            }
        }
        if (false !== $this->validation) {
            $validation = $volumeHoraire->getValidation();
            if (true === $this->validation) {
                if ($validation->isEmpty())
                    return false;
            }elseif (null === $this->validation) {
                if (!$validation->isEmpty())
                    return false;
            }else {
                if (!$validation->contains($this->validation))
                    return false;
            }
        }
        return true;
    }

    /**
     * Retourne la liste des volumes horaires du service.
     *
     * @return VolumeHoraireReferentiel[]
     */
    public function get()
    {
        $data = [];
        foreach ($this->getService()->getVolumeHoraireReferentiel() as $volumeHoraire) {
            if ($this->match($volumeHoraire)) {
                $data[$volumeHoraire->getId()] = $volumeHoraire;
            }
        }
        return $data;
    }

    /**
     * Retourne le nombre de volumes horaires concernés par la liste
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->get());
    }

    /**
     * Détermine si la liste est vide ou non
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 === $this->count();
    }

    /**
     * retourne une liste fille de volumes horaires
     *
     * @return self
     */
    public function getChild()
    {
        $volumeHoraireListe = new VolumeHoraireReferentielListe($this->getService());
        $volumeHoraireListe->setTypeVolumeHoraire($this->typeVolumeHoraire);
        $volumeHoraireListe->setValidation($this->validation);
        return $volumeHoraireListe;
    }

    /**
     *
     * @return type
     */
    public function getHeures()
    {
        $volumesHoraires = $this->get();
        $heures          = 0;
        foreach ($volumesHoraires as $volumeHoraire) {
            $heures += $volumeHoraire->getHeures();
        }
        return $heures;
    }

    /**
     * Affecte un certain nombre d'heures à la liste
     *
     * @param float $heures
     * @return self
     * @throws LogicException
     */
    public function setHeures($heures)
    {
        if ($heures < 0) {
            throw new LogicException('Le nombre d\'heures ne peut être inférieur à zéro');
        }

        $vhl = new VolumeHoraireReferentielListe($this->getService());
        /* Initialisation */
        if ($this->typeVolumeHoraire instanceof TypeVolumeHoraire) {
            $vhl->setTypeVolumeHoraire($this->typeVolumeHoraire);
        }
        else {
            throw new LogicException('Le type de volume horaire n\'est pas défini');
        }

//        /* transfert d'heures d'un motif vers un autre */
//        if (false !== $ancienMotifNonPaiement && $ancienMotifNonPaiement !== $motifNonPaiement){ // On retranche les anciennes heures si besoin...
//            $vhl->setMotifNonPaiement($motifNonPaiement);
//            $vhl->setHeures($vhl->getHeures() + $heures, $motifNonPaiement);
//
//            $vhl->setMotifNonPaiement($ancienMotifNonPaiement);
//            $newHeures = $vhl->getHeures() - $heures;
//            if ($newHeures < 0) $newHeures = 0;
//            $vhl->setHeures($newHeures, $ancienMotifNonPaiement);
//            return $this;
//        }
//
//        $vhl->setMotifNonPaiement($motifNonPaiement); // avec le motif de non paiement transmis
        $lastHeures = $vhl->getHeures();
        $newHeures  = $heures - $lastHeures;
        $vhl->setValidation(null); // On travaille sur les non validés
        if ($vhl->isEmpty()) {
            if (0 == $newHeures)
                return $this; // Pas de modifications à prévoir
            $saisieHeures  = $newHeures;
            $volumeHoraire = new VolumeHoraireReferentiel();
            $volumeHoraire->setServiceReferentiel($vhl->getService());
            $volumeHoraire->setTypeVolumeHoraire($vhl->getTypeVolumeHoraire());
            $volumeHoraire->setHeures($newHeures);
            $this->getService()->addVolumeHoraireReferentiel($volumeHoraire);
        }else {
            $soldeHeures = $newHeures;
            foreach ($vhl->get() as $volumeHoraire) {
                $saisieHeures = $soldeHeures + $volumeHoraire->getHeures();
                if (0 == $saisieHeures) { // nouvelle valeur à zéro donc on supprime le VH
                    $volumeHoraire->setRemove(true);
                    $soldeHeures = 0; // Fin de la modif
                }
                elseif (0 < $saisieHeures) {
                    $volumeHoraire->setHeures($saisieHeures); // On ajoute les heures au premier item trouvé
                    $soldeHeures = 0; // Fin de la modif
                }/* else{ // sinon on retire des heures sur tous les motifs jusqu'à ce que le compte soit bon
                  $motifVhl = $vhl->getChild()->setValidation(false)->setMotifNonPaiement($volumeHoraire->getMotifNonPaiement());
                  $motifHeures = $motifVhl->getHeures(); // on récupère le nbr d'heures du motif de non paiement
                  if ($motifHeures + $soldeHeures <= 0){
                  $soldeHeures += $volumeHoraire->getHeures();
                  $volumeHoraire->setRemove(true); // on l'enlève
                  }else{
                  $volumeHoraire->setHeures($saisieHeures);
                  $soldeHeures = 0;
                  }
                  } */
                if (0 == $soldeHeures)
                    break; // Fin de boucle si fin de modif
            }
            if ($soldeHeures !== 0) {
                $vhl->getChild()/* ->setMotifNonPaiement(null) */->setHeures($lastHeures + $newHeures);
            }
        }
//        if (false !== $ancienMotifNonPaiement && $ancienMotifNonPaiement !== $motifNonPaiement){ // On retranche les anciennes heures si besoin...
//            $vhl->setMotifNonPaiement($ancienMotifNonPaiement); // avec le motif de non paiement transmis
//            $oldSaisieHeures = $vhl->getHeures() - $newHeures;
//            if ($oldSaisieHeures < 0) $oldSaisieHeures = 0;
//            $this->setHeures($oldSaisieHeures, $ancienMotifNonPaiement);
//        }
        return $this;
    }

    /**
     * Vérifie l'éligibilité d'un volume horaire à la liste
     *
     * @param VolumeHoraireReferentiel $volumeHoraire
     * @return true
     * @throws LogicException
     */
    protected function checkVolumeHoraireEligibilite(VolumeHoraireReferentiel $volumeHoraire)
    {
        if ($volumeHoraire->getService() !== $this->getService()) {
            throw new LogicException('Le service du volume horaire ne correspond pas à celui de la liste');
        }
        if ($this->getTypeVolumeHoraire() instanceof TypeVolumeHoraire && $volumeHoraire->getTypeVolumeHoraire() !== $this->getTypeVolumeHoraire()) {
            throw new LogicException('Le type du volume horaire ne correspond pas à celui de la liste');
        }
        if ($this->getValidation() instanceof Validation && !$volumeHoraire->getValidation()->contains($this->getValidation())) {
            throw new LogicException('La validation du volume horaire ne correspond pas à celle de la liste');
        }
        return true;
    }

    /**
     * @return array
     */
    public function filtersToArray()
    {
        $result = [];
        if ($this->getTypeVolumeHoraire() instanceof TypeVolumeHoraire) {
            $result['type-volume-horaire'] = $this->getTypeVolumeHoraire()->getId();
        }
        if ($this->getValidation() instanceof Validation) {
            $result['validation'] = $this->getValidation()->getId();
        }
        return $result;
    }
}