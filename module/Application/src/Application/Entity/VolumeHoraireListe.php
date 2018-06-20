<?php

namespace Application\Entity;

use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Service;
use Application\Entity\Db\Periode;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\MotifNonPaiement;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\Validation;
use Application\Service\Traits\SourceServiceAwareTrait;
use RuntimeException;
use LogicException;

/**
 * Description of VolumeHoraireList
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireListe
{
    use SourceServiceAwareTrait;

    /**
     * @var Service|boolean
     */
    protected $service;

    /**
     * @var TypeVolumeHoraire|boolean
     */
    protected $typeVolumeHoraire = false;

    /**
     *
     * @var EtatVolumeHoraire
     */
    protected $etatVolumeHoraire = false;

    /**
     * @var Periode|boolean
     */
    protected $periode = false;

    /**
     * @var TypeIntervention|boolean
     */
    protected $typeIntervention = false;

    /**
     * @var MotifNonPaiement|boolean
     */
    protected $motifNonPaiement = false;

    /**
     * @var Contrat|boolean
     */
    protected $contrat = false;

    /**
     * @var Validation|boolean
     */
    protected $validation = false;

    /**
     * @var \DateTime|boolean
     */
    protected $horaireDebut = false;

    /**
     * @var \DateTime|boolean
     */
    protected $horaireFin = false;

    /**
     * @var Source|boolean
     */
    protected $source = false;

    /**
     * @var bool
     */
    protected $filterByHistorique = true;



    /**
     *
     * @param \Application\Entity\Db\Service $service
     */
    function __construct(Service $service)
    {
        $this->setService($service);
    }



    /**
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }



    /**
     *
     * @param Service $service
     *
     * @return self
     */
    public function setService(Service $service)
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
     *
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
     * @return EtatVolumeHoraire|boolean
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire;
    }



    /**
     *
     * @param EtatVolumeHoraire|boolean $etatVolumeHoraire
     *
     * @return self
     */
    public function setEtatVolumeHoraire($etatVolumeHoraire)
    {
        if (!(is_bool($etatVolumeHoraire) || null === $etatVolumeHoraire || $etatVolumeHoraire instanceof EtatVolumeHoraire)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }



    /**
     *
     * @return Periode|boolean
     */
    public function getPeriode()
    {
        return $this->periode;
    }



    /**
     *
     * @param Periode|boolean $periode
     *
     * @return self
     */
    public function setPeriode($periode)
    {
        if (!(is_bool($periode) || null === $periode || $periode instanceof Periode)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->periode = $periode;

        return $this;
    }



    /**
     *
     * @return TypeIntervention|boolean
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }



    /**
     * Retourne la liste des types d'intervention concernés par le service
     */
    public function getTypesIntervention()
    {
        $typesIntervention = [];
        $vhs               = $this->get();
        foreach ($vhs as $vh) {
            if (!isset($typesIntervention[$vh->getTypeIntervention()->getId()])) {
                $typesIntervention[$vh->getTypeIntervention()->getId()] = $vh->getTypeIntervention();
            }
        }

        return $typesIntervention;
    }



    /**
     *
     * @param TypeIntervention|boolean $typeIntervention
     *
     * @return self
     */
    public function setTypeIntervention($typeIntervention)
    {
        if (!(is_bool($typeIntervention) || null === $typeIntervention || $typeIntervention instanceof TypeIntervention)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->typeIntervention = $typeIntervention;

        return $this;
    }



    /**
     *
     * @return MotifNonPaiement|boolean
     */
    public function getMotifNonPaiement()
    {
        return $this->motifNonPaiement;
    }



    /**
     *
     * @param MotifNonPaiement|boolean $motifNonPaiement
     *
     * @return self
     */
    public function setMotifNonPaiement($motifNonPaiement)
    {
        if (!(is_bool($motifNonPaiement) || null === $motifNonPaiement || $motifNonPaiement instanceof MotifNonPaiement)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }



    /**
     *
     * @return Contrat|boolean
     */
    public function getContrat()
    {
        return $this->contrat;
    }



    /**
     *
     * @param Contrat|boolean $contrat
     *
     * @return self
     */
    public function setContrat($contrat)
    {
        if (!(is_bool($contrat) || null === $contrat || $contrat instanceof Contrat)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->contrat = $contrat;

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
     *
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
     * @return bool|\DateTime
     */
    public function getHoraireDebut()
    {
        return $this->horaireDebut;
    }



    /**
     * @param bool|\DateTime $horaireDebut
     *
     * @return VolumeHoraireListe
     */
    public function setHoraireDebut($horaireDebut)
    {
        $this->horaireDebut = $horaireDebut;

        return $this;
    }



    /**
     * @return bool|\DateTime
     */
    public function getHoraireFin()
    {
        return $this->horaireFin;
    }



    /**
     * @param bool|\DateTime $horaireFin
     *
     * @return VolumeHoraireListe
     */
    public function setHoraireFin($horaireFin)
    {
        $this->horaireFin = $horaireFin;

        return $this;
    }



    /**
     * @return Source|bool
     */
    public function getSource()
    {
        return $this->source;
    }



    /**
     * @param Source|bool $source
     *
     * @return VolumeHoraireListe
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getFilterByHistorique()
    {
        return $this->filterByHistorique;
    }



    /**
     * @param boolean $filterByHistorique
     */
    public function setFilterByHistorique($filterByHistorique)
    {
        $this->filterByHistorique = $filterByHistorique;
    }



    /**
     * Détermine si un volume horaire répond aux critères de la liste ou non
     *
     * @param VolumeHoraire $volumeHoraire
     *
     * @return boolean
     */
    public function match(VolumeHoraire $volumeHoraire)
    {
        if ($volumeHoraire->getRemove()) { // Si le volume horaire est en cours de suppression
            return false;
        }
        if ($this->filterByHistorique && !$volumeHoraire->estNonHistorise()) {
            return false;
        }
        if (false !== $this->typeVolumeHoraire) {
            $typeVolumeHoraire = $volumeHoraire->getTypeVolumeHoraire();
            if (true === $this->typeVolumeHoraire) {
                if (null === $typeVolumeHoraire) return false;
            } else {
                if ($typeVolumeHoraire !== $this->typeVolumeHoraire) return false;
            }
        }
        if (false !== $this->etatVolumeHoraire) {
            $etatVolumeHoraire = $volumeHoraire->getEtatVolumeHoraire();
            if (true === $this->etatVolumeHoraire) {
                if (null === $etatVolumeHoraire) return false;
            } else {
                if ($etatVolumeHoraire->getOrdre() < $this->etatVolumeHoraire->getOrdre()) return false;
            }
        }
        if (false !== $this->periode) {
            $periode = $volumeHoraire->getPeriode();
            if (true === $this->periode) {
                if (null === $periode) return false;
            } else {
                if ($periode !== $this->periode) return false;
            }
        }
        if (false !== $this->typeIntervention) {
            $typeIntervention = $volumeHoraire->getTypeIntervention();
            if (true === $this->typeIntervention) {
                if (null === $typeIntervention) return false;
            } else {
                if ($typeIntervention !== $this->typeIntervention) return false;
            }
        }
        if (false !== $this->motifNonPaiement) {
            $motifNonPaiement = $volumeHoraire->getMotifNonPaiement();
            if (true === $this->motifNonPaiement) {
                if (null === $motifNonPaiement) return false;
            } else {
                if ($motifNonPaiement !== $this->motifNonPaiement) return false;
            }
        }
        if (false !== $this->contrat) {
            $contrat = $volumeHoraire->getContrat();
            if (true === $this->contrat) {
                if (null === $contrat) return false;
            } else {
                if ($contrat !== $this->contrat) return false;
            }
        }
        if (false !== $this->validation && !$volumeHoraire->isAutoValidation()) {
            $validation = $volumeHoraire->getValidation();
            if (true === $this->validation) {
                if ($validation->isEmpty()) return false;
            } elseif (null === $this->validation) {
                if (!$validation->isEmpty()) return false;
            } else {
                if (!$validation->contains($this->validation)) return false;
            }
        }
        if (false !== $this->horaireDebut) {
            $horaireDebut = $this->timestamp($volumeHoraire->getHoraireDebut());
            if (true === $this->horaireDebut) {
                if (0 == $horaireDebut) return false;
            } else {
                if ($horaireDebut != $this->timestamp($this->horaireDebut)) return false;
            }
        }
        if (false !== $this->horaireFin) {
            $horaireFin = $this->timestamp($volumeHoraire->getHoraireFin());
            if (true === $this->horaireFin) {
                if (0 == $horaireFin) return false;
            } else {
                if ($horaireFin != $this->timestamp($this->horaireFin)) return false;
            }
        }
        if (false !== $this->source) {
            $source = $volumeHoraire->getSource();
            if (true === $this->source) {
                if (null === $source) return false;
            } else {
                if ($source !== $this->source) return false;
            }
        }

        return true;
    }



    /**
     * @param DateTime|null $dateTime
     *
     * @return int
     */
    private function timestamp($dateTime): int
    {
        if ($dateTime instanceof \DateTime) {
            return $dateTime->getTimestamp();
        } else {
            return 0;
        }
    }



    /**
     * Retourne la liste des volumes horaires du service (sans les motifs de non paiement)
     * Les clés sont les codes des types d'intervention des volumes horaires
     *
     * @return VolumeHoraire[]
     */
    public function get()
    {
        $data = [];

        $vhs = $this->getService()->getVolumeHoraire();
        foreach ($vhs as $volumeHoraire) {
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
     *
     * @return Periode[]
     */
    public function getPeriodes()
    {
        $periodes = [];
        foreach ($this->get() as $volumeHoraire) {
            $periodes[$volumeHoraire->getPeriode()->getId()] = $volumeHoraire->getPeriode();
        }
        uasort($periodes, function ($a, $b) {
            return ($a ? $a->getOrdre() : '') > ($b ? $b->getOrdre() : '');
        });

        return $periodes;
    }



    /**
     * Détermine si, dans la liste des heures, des périodes non autorisées sont présentes
     *
     * @return boolean
     */
    public function hasForbiddenPeriodes()
    {
        if (!$this->getService()->getElementPedagogique()) return false;
        if (!$periode = $this->getService()->getElementPedagogique()->getPeriode()) return false;

        $periodes = $this->getPeriodes();
        foreach ($periodes as $p) {
            if ($p !== $periode) return true;
        }

        return false;
    }



    /**
     *
     * @return MotifNonPaiement[]
     */
    public function getMotifsNonPaiement()
    {
        $mnps   = [];
        $vChild = $this->getChild();
        foreach ($this->get() as $volumeHoraire) {
            if ($mnp = $volumeHoraire->getMotifNonPaiement()) {
                $vChild->setMotifNonPaiement($mnp);
                if ($vChild->getHeures() !== 0) {
                    $mnps[$mnp->getId()] = $mnp;
                }
            } else {
                $vChild->setMotifNonPaiement(null);
                if ($vChild->getHeures() !== 0) {
                    $mnps[0] = null;
                }
            }
        }
        uasort($mnps, function ($a, $b) {
            return ($a ? $a->getLibelleLong() : '') > ($b ? $b->getLibelleLong() : '');
        });

        return $mnps;
    }



    /**
     * retourne une liste fille de volumes horaires
     *
     * @return self
     */
    public function getChild()
    {
        $volumeHoraireListe = new VolumeHoraireListe($this->getService());
        $volumeHoraireListe->setTypeVolumeHoraire($this->typeVolumeHoraire);
        $volumeHoraireListe->setEtatVolumeHoraire($this->etatVolumeHoraire);
        $volumeHoraireListe->setPeriode($this->periode);
        $volumeHoraireListe->setTypeIntervention($this->typeIntervention);
        $volumeHoraireListe->setMotifNonPaiement($this->motifNonPaiement);
        $volumeHoraireListe->setContrat($this->contrat);
        $volumeHoraireListe->setValidation($this->validation);
        $volumeHoraireListe->setHoraireDebut($this->horaireDebut);
        $volumeHoraireListe->setHoraireFin($this->horaireFin);
        $volumeHoraireListe->setSource($this->source);

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
     * @param float                       $heures
     * @param MotifNonPaiement|null|false $motifNonPaiement
     * @param MotifNonPaiement|null|false $ancienMotifNonPaiement
     *
     * @return self
     * @throws LogicException
     */
    public function setHeures($heures, $motifNonPaiement = null, $ancienMotifNonPaiement = false)
    {
        if ($heures < 0) {
            throw new LogicException('Le nombre d\'heures ne peut être inférieur à zéro');
        }
        if (null !== $motifNonPaiement && false !== $motifNonPaiement && !$motifNonPaiement instanceof MotifNonPaiement) {
            throw new LogicException('Le motif de non paiement transmis n\'est pas correct');
        }
        if (null !== $ancienMotifNonPaiement && false !== $ancienMotifNonPaiement && !$ancienMotifNonPaiement instanceof MotifNonPaiement) {
            throw new LogicException('L\'ancien motif de non paiement transmis n\'est pas correct');
        }

        $vhl = new VolumeHoraireListe($this->getService());
        /* Initialisation */
        if ($this->horaireDebut instanceof \DateTime) {
            $vhl->setHoraireDebut($this->horaireDebut);
        } else {
            $vhl->setHoraireDebut(null);
        }
        if ($this->horaireFin instanceof \DateTime) {
            $vhl->setHoraireFin($this->horaireFin);
        } else {
            $vhl->setHoraireFin(null);
        }
        if ($this->typeVolumeHoraire instanceof TypeVolumeHoraire) {
            $vhl->setTypeVolumeHoraire($this->typeVolumeHoraire);
        } else {
            throw new LogicException('Le type de volume horaire n\'est pas défini');
        }
        if ($this->periode instanceof Periode) {
            $vhl->setPeriode($this->periode);
        } else {
            throw new LogicException('La période n\'est pas définie');
        }
        if ($this->typeIntervention instanceof TypeIntervention) {
            $vhl->setTypeIntervention($this->typeIntervention);
        } else {
            throw new LogicException('Le type d\'intervention n\'est pas défini');
        }

        /* transfert d'heures d'un motif vers un autre */
        if (false !== $ancienMotifNonPaiement && $ancienMotifNonPaiement !== $motifNonPaiement) { // On retranche les anciennes heures si besoin...
            $vhl->setMotifNonPaiement($motifNonPaiement);
            $vhl->setHeures($vhl->getHeures() + $heures, $motifNonPaiement);

            $vhl->setMotifNonPaiement($ancienMotifNonPaiement);
            $newHeures = $vhl->getHeures() - $heures;
            if ($newHeures < 0) $newHeures = 0;
            $vhl->setHeures($newHeures, $ancienMotifNonPaiement);

            return $this;
        }

        $vhl->setMotifNonPaiement($motifNonPaiement); // avec le motif de non paiement transmis
        $lastHeures = $vhl->getHeures();
        $newHeures  = $heures - $lastHeures;
        $vhl->setValidation(null); // On travaille sur les non validés
        $vhl->setSource($this->getServiceSource()->getOse()); // On ne modifie que la source OSE!!
        if ($vhl->isEmpty()) {
            if (0 == $newHeures) return $this; // Pas de modifications à prévoir
            $volumeHoraire = new VolumeHoraire();
            $volumeHoraire->setService($vhl->getService());
            $volumeHoraire->setTypeVolumeHoraire($vhl->getTypeVolumeHoraire());
            $volumeHoraire->setPeriode($vhl->getPeriode());
            $volumeHoraire->setTypeIntervention($vhl->getTypeIntervention());
            $volumeHoraire->setMotifNonPaiement(false === $motifNonPaiement ? null : $motifNonPaiement); // pas de motif de paiement par défaut
            $volumeHoraire->setHoraireDebut($vhl->getHoraireDebut());
            $volumeHoraire->setHoraireFin($vhl->getHoraireFin());
            $volumeHoraire->setHeures($newHeures);
            $this->getService()->addVolumeHoraire($volumeHoraire);
        } else {
            $soldeHeures = $newHeures;
            $vhs         = $vhl->get();
            foreach ($vhs as $volumeHoraire) {
                $saisieHeures = $soldeHeures + $volumeHoraire->getHeures();
                if (0 == $saisieHeures) { // nouvelle valeur à zéro donc on supprime le VH
                    $volumeHoraire->setRemove(true);
                    $soldeHeures = 0; // Fin de la modif
                } elseif (0 < $saisieHeures) {
                    $volumeHoraire->setHeures($saisieHeures); // On ajoute les heures au premier item trouvé
                    $soldeHeures = 0; // Fin de la modif
                } else { // sinon on retire des heures sur tous les motifs jusqu'à ce que le compte soit bon
                    $motifVhl    = $vhl->getChild()->setValidation(false)->setMotifNonPaiement($volumeHoraire->getMotifNonPaiement());
                    $motifHeures = $motifVhl->getHeures(); // on récupère le nbr d'heures du motif de non paiement
                    if ($motifHeures + $soldeHeures <= 0 && !$volumeHoraire->getMotifNonPaiement()) {
                        $soldeHeures += $volumeHoraire->getHeures();
                        $volumeHoraire->setRemove(true); // on l'enlève
                    } else {
                        $volumeHoraire->setHeures($saisieHeures);
                        $soldeHeures = 0;
                    }
                }
                if (0 == $soldeHeures) break; // Fin de boucle si fin de modif
            }
            if ($soldeHeures !== 0) {
                $vhl->getChild()->setMotifNonPaiement(null)->setHeures($lastHeures + $newHeures);
            }
        }
        if (false !== $ancienMotifNonPaiement && $ancienMotifNonPaiement !== $motifNonPaiement) { // On retranche les anciennes heures si besoin...
            $vhl->setMotifNonPaiement($ancienMotifNonPaiement); // avec le motif de non paiement transmis
            $oldSaisieHeures = $vhl->getHeures() - $newHeures;
            if ($oldSaisieHeures < 0) $oldSaisieHeures = 0;
            $this->setHeures($oldSaisieHeures, $ancienMotifNonPaiement);
        }

        return $this;
    }



    /**
     * Vérifie l'éligibilité d'un volume horaire à la liste
     *
     * @param VolumeHoraire $volumeHoraire
     *
     * @return true
     * @throws LogicException
     */
    protected function checkVolumeHoraireEligibilite(VolumeHoraire $volumeHoraire)
    {
        if ($volumeHoraire->getService() !== $this->getService()) {
            throw new LogicException('Le service du volume horaire ne correspond pas à celui de la liste');
        }
        if ($this->getTypeVolumeHoraire() instanceof TypeVolumeHoraire && $volumeHoraire->getTypeVolumeHoraire() !== $this->getTypeVolumeHoraire()) {
            throw new LogicException('Le type du volume horaire ne correspond pas à celui de la liste');
        }
        if ($this->getEtatVolumeHoraire() instanceof EtatVolumeHoraire && $volumeHoraire->getEtatVolumeHoraire()->getOrdre() < $this->getEtatVolumeHoraire()->getOrdre()) {
            throw new LogicException('L\'état du volume horaire ne correspond pas à celui de la liste');
        }
        if ($this->getPeriode() instanceof Periode && $volumeHoraire->getPeriode() !== $this->getPeriode()) {
            throw new LogicException('La période du volume horaire ne correspond pas à celle de la liste');
        }
        if ($this->getTypeIntervention() instanceof TypeIntervention && $volumeHoraire->getTypeIntervention() !== $this->getTypeIntervention()) {
            throw new LogicException('Le type d\'intervention du volume horaire ne correspond pas à celui de la liste');
        }
        if ($this->getMotifNonPaiement() instanceof MotifNonPaiement && $volumeHoraire->getMotifNonPaiement() !== $this->getMotifNonPaiement()) {
            throw new LogicException('Le motif de non paiement du volume horaire ne correspond pas à celui de la liste');
        }
        if ($this->getContrat() instanceof Contrat && $volumeHoraire->getContrat() !== $this->getContrat()) {
            throw new LogicException('Le contrat du volume horaire ne correspond pas à celui de la liste');
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
        if ($this->getEtatVolumeHoraire() instanceof EtatVolumeHoraire) {
            $result['etat-volume-horaire'] = $this->getEtatVolumeHoraire()->getId();
        }
        if ($this->getPeriode() instanceof Periode) {
            $result['periode'] = $this->getPeriode()->getId();
        }
        if ($this->getTypeIntervention() instanceof TypeIntervention) {
            $result['type-intervention'] = $this->getTypeIntervention()->getId();
        }
        if ($this->getMotifNonPaiement() instanceof MotifNonPaiement) {
            $result['motif-non-paiement'] = $this->getMotifNonPaiement()->getId();
        }
        if ($this->getContrat() instanceof Contrat) {
            $result['contrat'] = $this->getContrat()->getId();
        }
        if ($this->getValidation() instanceof Validation) {
            $result['validation'] = $this->getValidation()->getId();
        }
        if ($this->getHoraireDebut() instanceof \DateTime) {
            $result['horaire-debut'] = $this->getHoraireDebut();
        }
        if ($this->getHoraireFin() instanceof \DateTime) {
            $result['horaire-fin'] = $this->getHoraireFin();
        }

        return $result;
    }
}