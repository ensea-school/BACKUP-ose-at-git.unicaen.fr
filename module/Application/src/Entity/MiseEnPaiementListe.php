<?php

namespace Application\Entity;

use Application\Entity\Db\ServiceAPayerInterface;
use Application\Interfaces\ServiceAPayerAwareInterface;
use Application\Traits\ServiceAPayerAwareTrait;
use Application\Entity\Db\Periode;

/**
 * Description of MiseEnPaiementListe
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementListe implements ServiceAPayerAwareInterface
{
    use ServiceAPayerAwareTrait;

    /**
     *
     * @var \DateTime|boolean|null
     */
    protected $dateMiseEnPaiement = false;

    /**
     * @var Periode
     */
    protected $periodePaiement = false;

    /**
     * @var Validation|boolean
     */
    protected $validation = false;

    /**
     * Centre de coûts
     *
     * @var Db\CentreCout
     */
    protected $centreCout = false;

    /**
     * Type d'heures
     *
     * @var Db\TypeHeures
     */
    protected $typeHeures = false;



    /**
     *
     * @param ServiceAPayerInterface $serviceAPayer
     */
    function __construct(ServiceAPayerInterface $serviceAPayer)
    {
        $this->setServiceAPayer($serviceAPayer);
    }



    /**
     *
     * @return \DateTime|boolean|null
     */
    function getDateMiseEnPaiement()
    {
        return $this->dateMiseEnPaiement;
    }



    /**
     *
     * @param \DateTime|boolean|null $dateMiseEnPaiement
     *
     * @return self
     * @throws RuntimeException
     */
    function setDateMiseEnPaiement($dateMiseEnPaiement)
    {
        if (!(is_bool($dateMiseEnPaiement) || null === $dateMiseEnPaiement || $dateMiseEnPaiement instanceof \DateTime)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->dateMiseEnPaiement = $dateMiseEnPaiement;

        return $this;
    }



    /**
     *
     * @return Periode|boolean|null
     */
    function getPeriodePaiement()
    {
        return $this->periodePaiement;
    }



    /**
     *
     * @param Periode|null|boolean $periodePaiement
     *
     * @return self
     * @throws RuntimeException
     */
    function setPeriodePaiement($periodePaiement)
    {
        if (!(is_bool($periodePaiement) || null === $periodePaiement || $periodePaiement instanceof Periode)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->periodePaiement = $periodePaiement;

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
     *
     * @return Db\CentreCout|boolean|null
     */
    function getCentreCout()
    {
        return $this->centreCout;
    }



    /**
     *
     * @param Db\CentreCout|null|boolean $centreCout
     *
     * @return self
     * @throws RuntimeException
     */
    function setCentreCout($centreCout)
    {
        if (!(is_bool($centreCout) || null === $centreCout || $centreCout instanceof Db\CentreCout)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->centreCout = $centreCout;

        return $this;
    }



    /**
     *
     * @return Db\TypeHeures|boolean|null
     */
    function getTypeHeures()
    {
        return $this->typeHeures;
    }



    /**
     *
     * @param Db\TypeHeures|null|boolean $typeHeures
     *
     * @return self
     * @throws RuntimeException
     */
    function setTypeHeures($typeHeures)
    {
        if (!(is_bool($typeHeures) || null === $typeHeures || $typeHeures instanceof Db\TypeHeures)) {
            throw new RuntimeException('Valeur non autorisée');
        }
        $this->typeHeures = $typeHeures;

        return $this;
    }



    /**
     * Détermine si une mise en paiement répond aux critères de la liste ou non
     *
     * @param Db\MiseEnPaiement $miseEnPaiement
     *
     * @return boolean
     */
    public function match(Db\MiseEnPaiement $miseEnPaiement)
    {
        //if ($miseEnPaiement->getRemove()){ // Si la mise en paiement est en cours de suppression
        //    return false;
        //}
        if (false !== $this->dateMiseEnPaiement) {
            $dateMiseEnPaiement = $miseEnPaiement->getDateMiseEnPaiement();
            if (true === $this->dateMiseEnPaiement) {
                if (null === $dateMiseEnPaiement) return false;
            } else {
                if ($dateMiseEnPaiement !== $this->dateMiseEnPaiement) return false;
            }
        }
        if (false !== $this->periodePaiement) {
            $periodePaiement = $miseEnPaiement->getPeriodePaiement();
            if (true === $this->periodePaiement) {
                if (null === $periodePaiement) return false;
            } else {
                if ($periodePaiement !== $this->periodePaiement) return false;
            }
        }
        if (false !== $this->validation) {
            $validation = $miseEnPaiement->getValidation();
            if (true === $this->validation) {
                if ($validation->isEmpty()) return false;
            } elseif (null === $this->validation) {
                if (!$validation->isEmpty()) return false;
            } else {
                if (!$validation->contains($this->validation)) return false;
            }
        }
        if (false !== $this->centreCout) {
            $centreCout = $miseEnPaiement->getCentreCout();
            if (true === $this->centreCout) {
                if (null === $centreCout) return false;
            } else {
                if ($centreCout !== $this->centreCout) return false;
            }
        }
        if (false !== $this->typeHeures) {
            $typeHeures = $miseEnPaiement->getTypeHeures();
            if (true === $this->typeHeures) {
                if (null === $typeHeures) return false;
            } else {
                if ($typeHeures !== $this->typeHeures) return false;
            }
        }

        return true;
    }



    /**
     * Retourne la liste des mises en paiement du service à payer
     * Les clés sont les ID des mises en paiement
     *
     * @return Db\MiseEnPaiement[]
     */
    public function get()
    {
        $data = [];
        foreach ($this->getServiceAPayer()->getMiseEnPaiement() as $miseEnPaiement) {
            if ($this->match($miseEnPaiement)) {
                $data[$miseEnPaiement->getId()] = $miseEnPaiement;
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
        $miseEnPaiementListe = new MiseEnPaiementListe  ($this->getServiceAPayer());
        $miseEnPaiementListe->setDateMiseEnPaiement($this->dateMiseEnPaiement);
        $miseEnPaiementListe->setPeriodePaiement($this->periodePaiement);
        $miseEnPaiementListe->setValidation($this->validation);
        $miseEnPaiementListe->setCentreCout($this->centreCout);
        $miseEnPaiementListe->setTypeHeures($this->typeHeures);

        return $miseEnPaiementListe;
    }



    /**
     *
     * @return float
     */
    public function getHeures()
    {
        $misesEnPaiement = $this->get();
        $heures          = 0.0;
        foreach ($misesEnPaiement as $miseEnPaiement) {
            $heures += $miseEnPaiement->getHeures();
        }

        return $heures;
    }



    /**
     * Affecte un certain nombre d'heures à la liste
     *
     * @param float $heures
     *
     * @return self
     * @throws LogicException
     */
    public function setHeures($heures)
    {
        if ($heures < 0) {
            throw new LogicException('Le nombre d\'heures ne peut être inférieur à zéro');
        }

        $mpl = new MiseEnPaiementListe($this->getServiceAPayer());

        $lastHeures = $mpl->getHeures();
        $newHeures  = $heures - $lastHeures;
        $mpl->setValidation(null); // On travaille sur les non validés
        if ($mpl->isEmpty()) {
            if (0 == $newHeures) return $this; // Pas de modifications à prévoir
            $saisieHeures   = $newHeures;
            $miseEnPaiement = new Db\MiseEnPaiement();
            $miseEnPaiement->setServiceAPayer($mpl->getServiceAPayer());
            if ($this->dateMiseEnPaiement instanceof \DateTime) {
                $miseEnPaiement->setDateValidation($this->dateMiseEnPaiement);
            }

            if ($this->periodePaiement instanceof Periode) {
                $miseEnPaiement->setPeriodePaiement($this->periodePaiement);
            }

            $miseEnPaiement->setHeures($newHeures);
            $this->getServiceAPayer()->addMiseEnPaiement($miseEnPaiement);
        } else {
            $soldeHeures = $newHeures;
            foreach ($mpl->get() as $miseEnPaiement) {
                $saisieHeures = $soldeHeures + $miseEnPaiement->getHeures();
                if (0 == $saisieHeures) { // nouvelle valeur à zéro donc on supprime le VH
                    $miseEnPaiement->setRemove(true);
                    $soldeHeures = 0; // Fin de la modif
                } elseif (0 < $saisieHeures) {
                    $miseEnPaiement->setHeures($saisieHeures); // On ajoute les heures au premier item trouvé
                    $soldeHeures = 0; // Fin de la modif
                } else { // sinon on retire des heures sur tous les motifs jusqu'à ce que le compte soit bon
                    $motifMpl    = $mpl->getChild()->setValidation(false);
                    $motifHeures = $motifMpl->getHeures(); // on récupère le nbr d'heures du motif de non paiement
                    if ($motifHeures + $soldeHeures <= 0) {
                        $soldeHeures += $miseEnPaiement->getHeures();
                        $miseEnPaiement->setRemove(true); // on l'enlève
                    } else {
                        $miseEnPaiement->setHeures($saisieHeures);
                        $soldeHeures = 0;
                    }
                }
                if (0 == $soldeHeures) break; // Fin de boucle si fin de modif
            }
            if ($soldeHeures !== 0) {
                $mpl->getChild()->setHeures($lastHeures + $newHeures);
            }
        }

        return $this;
    }



    /**
     * Vérifie l'éligibilité d'une mise en paiement à la liste
     *
     * @param Db\MiseEnPaiement $miseEnPaiement
     *
     * @return true
     * @throws LogicException
     */
    protected function checkEligibilite(Db\MiseEnPaiement $miseEnPaiement)
    {
        if ($miseEnPaiement->getServiceAPayer() !== $this->getServiceAPayer()) {
            throw new LogicException('Le service à payer de la mise en paiement ne correspond pas à celui de la liste');
        }
        if ($this->getPeriodePaiement() instanceof Periode && $miseEnPaiement->getPeriodePaiement() !== $this->getPeriodePaiement()) {
            throw new LogicException('La période de la mise en paiement ne correspond pas à celle de la liste');
        }
        if ($this->getDateMiseEnPaiement() instanceof \DateTime && $miseEnPaiement->getDateMiseEnPaiement() !== $this->getDateMiseEnPaiement()) {
            throw new LogicException('La date de mise en paiement de la mise en paiement ne correspond pas à celle de la liste');
        }
        if ($this->getValidation() instanceof Validation && !$miseEnPaiement->getValidation()->contains($this->getValidation())) {
            throw new LogicException('La validation de la mise en paiement ne correspond pas à celle de la liste');
        }
        if ($this->getCentreCout() instanceof Db\CentreCout && $miseEnPaiement->getCentreCout() !== $this->getCentreCout()) {
            throw new LogicException('Le centre de coûts de la mise en paiement ne correspond pas à celui de la liste');
        }
        if ($this->getTypeHeures() instanceof Db\TypeHeures && $miseEnPaiement->getTypeHeures() !== $this->getTypeHeures()) {
            throw new LogicException('Le type d\'heures de la mise en paiement ne correspond pas à celui de la liste');
        }

        return true;
    }



    /**
     * @return array
     */
    public function filtersToArray()
    {
        $result = [];
        if ($this->getPeriodePaiement() instanceof Periode) {
            $result['periode-paiement'] = $this->getPeriodePaiement()->getId();
        }
        if ($this->getDateMiseEnPaiement() instanceof \DateTime) {
            $result['date-mise-paiement'] = $this->getDateMiseEnPaiement()->format('Y-m-d');
        }
        if ($this->getValidation() instanceof Validation) {
            $result['validation'] = $this->getValidation()->getId();
        }
        if ($this->getCentreCout() instanceof Db\CentreCout) {
            $result['centre-cout'] = $this->getCentreCout()->getId();
        }
        if ($this->getTypeHeures() instanceof Db\TypeHeures) {
            $result['type-heures'] = $this->getTypeHeures()->getId();
        }

        return $result;
    }



    /**
     *
     * @return Db\TypeHeures[]
     */
    public function getTypesHeures()
    {
        $result = [];
        $meps   = $this->get();
        foreach ($meps as $mep) {
            if ($mep->getTypeHeures()) {
                $result[$mep->getTypeHeures()->getId()] = $mep->getTypeHeures();
            }
        }
        uasort($result, function ($a, $b) {
            return $a->getOrdre() - $b->getOrdre();
        });

        return $result;
    }



    /**
     * @return Db\CentreCout[]
     */
    public function getCentresCout()
    {
        $result = [];
        $meps   = $this->get();
        foreach ($meps as $mep) {
            if ($mep->getCentreCout()) {
                $result[$mep->getCentreCout()->getId()] = $mep->getCentreCout();
            }
        }

        return $result;
    }
}