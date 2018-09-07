<?php

namespace Application\Entity;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\MotifNonPaiement;
use Application\Entity\Db\Periode;
use Application\Entity\Db\Service;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Hydrator\VolumeHoraire\ListeFilterHydrator;
use Application\Service\Traits\SourceServiceAwareTrait;
use LogicException;
use RuntimeException;
use UnicaenImport\Entity\Db\Source;

/**
 * Description of VolumeHoraireList
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireListe
{
    const FILTRE_CONTRAT             = 'contrat';
    const FILTRE_ETAT_VOLUME_HORAIRE = 'etat-volume-horaire';
    const FILTRE_HORAIRE_DEBUT       = 'horaire-debut';
    const FILTRE_HORAIRE_FIN         = 'horaire-fin';
    const FILTRE_MOTIF_NON_PAIEMENT  = 'motif-non-paiement';
    const FILTRE_PERIODE             = 'periode';
    const FILTRE_TYPE_INTERVENTION   = 'type-intervention';
    const FILTRE_TYPE_VOLUME_HORAIRE = 'type-volume-horaire';
    const FILTRE_VALIDATION          = 'validation';
    const FILTRE_SOURCE              = 'source';
    const FILTRE_HISTORIQUE          = 'historique';
    const FILTRE_NEW                 = 'new';
    const FILTRE_LIST                = [
        self::FILTRE_CONTRAT, self::FILTRE_ETAT_VOLUME_HORAIRE, self::FILTRE_HORAIRE_DEBUT, self::FILTRE_HORAIRE_FIN,
        self::FILTRE_MOTIF_NON_PAIEMENT, self::FILTRE_PERIODE, self::FILTRE_TYPE_INTERVENTION,
        self::FILTRE_TYPE_VOLUME_HORAIRE, self::FILTRE_VALIDATION, self::FILTRE_SOURCE, self::FILTRE_HISTORIQUE,
        self::FILTRE_NEW,
    ];

    CONST FILTRES_LIST = [
        self::FILTRE_CONTRAT             => [
            'class'       => Contrat::class,
            'accessor'    => 'Contrat',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_ETAT_VOLUME_HORAIRE => [
            'class'       => EtatVolumeHoraire::class,
            'accessor'    => 'EtatVolumeHoraire',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_HORAIRE_DEBUT       => [
            'class'       => \DateTime::class,
            'accessor'    => 'HoraireDebut',
            'to-int-func' => 'getTimestamp',
        ],
        self::FILTRE_HORAIRE_FIN         => [
            'class'       => \DateTime::class,
            'accessor'    => 'HoraireFin',
            'to-int-func' => 'getTimestamp',
        ],
        self::FILTRE_MOTIF_NON_PAIEMENT  => [
            'class'       => MotifNonPaiement::class,
            'accessor'    => 'MotifNonPaiement',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_PERIODE             => [
            'class'       => Periode::class,
            'accessor'    => 'Periode',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_TYPE_INTERVENTION   => [
            'class'       => TypeIntervention::class,
            'accessor'    => 'TypeIntervention',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_TYPE_VOLUME_HORAIRE => [
            'class'       => TypeVolumeHoraire::class,
            'accessor'    => 'TypeVolumeHoraire',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_VALIDATION          => [
            'class'       => Validation::class,
            'accessor'    => 'Validation',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_SOURCE              => [
            'class'       => Source::class,
            'accessor'    => 'Source',
            'to-int-func' => 'getId',
        ],
        self::FILTRE_HISTORIQUE          => [
            'class'       => null,
            'accessor'    => 'FilterByHistorique',
            'to-int-func' => null,
        ],
        self::FILTRE_NEW                 => [
            'class'       => null,
            'accessor'    => 'new',
            'to-int-func' => null,
        ],
    ];

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
     * @var bool
     */
    protected $new = false;

    /**
     * @var object
     */
    public $__debug;



    /**
     *
     * @param \Application\Entity\Db\Service $service
     */
    function __construct(Service $service)
    {
        $this->setService($service);
    }



    /**
     * Retourne la liste des volumes horaires du service (sans les motifs de non paiement)
     * Les clés sont les codes des types d'intervention des volumes horaires
     *
     * @return VolumeHoraire[]
     */
    public function getVolumeHoraires()
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
        if ($this->new && $volumeHoraire->getId()) {
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
            if ($validation = $volumeHoraire->getValidation()) {
                if (true === $this->validation) {
                    if ($validation->isEmpty()) return false;
                } elseif (null === $this->validation) {
                    if (!$validation->isEmpty()) return false;
                } else {
                    if (!$validation->contains($this->validation)) return false;
                }
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
        if ($this->__debug) $this->__debug->dumpAction('setSource', $source);

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
        if ($this->__debug) $this->__debug->dumpAction('setFilterByHistorique', $filterByHistorique);

        return $this;
    }



    /**
     * @return bool
     */
    public function getNew(): bool
    {
        return $this->new;
    }



    /**
     * @param bool $new
     *
     * @return $this
     */
    public function setNew(bool $new)
    {
        $this->new = $new;
        if ($this->__debug) $this->__debug->dumpAction('setNew', $new);

        return $this;
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
     * Retourne la liste des types d'intervention concernés par le service
     */
    public function getTypesIntervention()
    {
        $typesIntervention = [];
        $vhs               = $this->getVolumeHoraires();
        foreach ($vhs as $vh) {
            if (!isset($typesIntervention[$vh->getTypeIntervention()->getId()])) {
                $typesIntervention[$vh->getTypeIntervention()->getId()] = $vh->getTypeIntervention();
            }
        }

        return $typesIntervention;
    }



    /**
     *
     * @return Periode[]
     */
    public function getPeriodes()
    {
        $periodes = [];
        foreach ($this->getVolumeHoraires() as $volumeHoraire) {
            $periodes[$volumeHoraire->getPeriode()->getId()] = $volumeHoraire->getPeriode();
        }
        uasort($periodes, function ($a, $b) {
            return ($a ? $a->getOrdre() : '') > ($b ? $b->getOrdre() : '');
        });

        return $periodes;
    }



    /**
     *
     * @return MotifNonPaiement[]
     */
    public function getMotifsNonPaiement()
    {
        $mnps   = [];
        $vChild = $this->createChild();
        foreach ($this->getVolumeHoraires() as $volumeHoraire) {
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
     * @param VolumeHoraire $vh
     * @param array         $filtres
     *
     * @return string
     */
    private function makeSousListeId(VolumeHoraire $vh, array $filtres = []): string
    {
        $id = [];

        foreach ($filtres as $filtre) {
            $rule      = self::FILTRES_LIST[$filtre];
            $getter    = 'get' . $rule['accessor'];
            $toIntFunc = $rule['to-int-func'];
            if (is_object($v = $vh->$getter()) && is_a($v, $rule['class'])) {
                if ($toIntFunc) {
                    $id[] = $filtre . '=' . $v->$toIntFunc();
                } else {
                    $id[] = $filtre . '=' . $v;
                }
            }
        }

        if (in_array(self::FILTRE_HISTORIQUE, $filtres)) {
            $id[] = self::FILTRE_HISTORIQUE . '=' . $this->intify($vh->estNonHistorise());
        }

        if (in_array(self::FILTRE_NEW, $filtres)) {
            $id[] = self::FILTRE_NEW . '=' . $vh->getId() ? 0 : 1;
        }

        if (in_array(self::FILTRE_VALIDATION, $filtres)) {
            $id[] = self::FILTRE_VALIDATION . '=' . $this->intify($vh->getValidation());
        }

        return implode(';', $id);
    }



    /**
     * @param array $filtres
     *
     * @return self[]
     */
    public function getSousListes(array $filtres = []): array
    {
        $listes = [];
        $vhs    = $this->getService()->getOrderedVolumeHoraire();
        foreach ($vhs as $vh) {
            $vhId = $this->makeSousListeId($vh, $filtres);
            if (!array_key_exists($vhId, $listes)) {
                $listes[$vhId] = $this->createChild()->filterByVolumeHoraire($vh, $filtres);
            }
        }

        return $listes;
    }



    /**
     * retourne une liste fille de volumes horaires
     *
     * @return self
     */
    public function createChild()
    {
        $vhlph              = new ListeFilterHydrator();
        $volumeHoraireListe = new VolumeHoraireListe($this->getService());
        $vhlph->hydrate($vhlph->extract($this), $volumeHoraireListe);
        $volumeHoraireListe->__debug = $this->__debug;

        return $volumeHoraireListe;
    }



    /**
     * @param VolumeHoraire $vh
     * @param array         $filtres
     *
     * @return VolumeHoraireListe
     */
    public function filterByVolumeHoraire(VolumeHoraire $vh, array $filtres = []): VolumeHoraireListe
    {
        if (in_array(self::FILTRE_CONTRAT, $filtres)) {
            $this->setContrat($vh->getContrat());
        }
        if (in_array(self::FILTRE_ETAT_VOLUME_HORAIRE, $filtres)) {
            $this->setEtatVolumeHoraire($vh->getEtatVolumeHoraire());
        }
        if (in_array(self::FILTRE_HISTORIQUE, $filtres)) {
            $this->setFilterByHistorique($vh->estNonHistorise());
        }
        if (in_array(self::FILTRE_NEW, $filtres)) {
            $this->setNew(!$vh->getId());
        }
        if (in_array(self::FILTRE_HORAIRE_DEBUT, $filtres)) {
            $this->setHoraireDebut($vh->getHoraireDebut());
        }
        if (in_array(self::FILTRE_HORAIRE_FIN, $filtres)) {
            $this->setHoraireFin($vh->getHoraireFin());
        }
        if (in_array(self::FILTRE_MOTIF_NON_PAIEMENT, $filtres)) {
            $this->setMotifNonPaiement($vh->getMotifNonPaiement());
        }
        if (in_array(self::FILTRE_PERIODE, $filtres)) {
            $this->setPeriode($vh->getPeriode());
        }
        if (in_array(self::FILTRE_SOURCE, $filtres)) {
            $this->setSource($vh->getSource());
        }
        if (in_array(self::FILTRE_TYPE_INTERVENTION, $filtres)) {
            $this->setTypeIntervention($vh->getTypeIntervention());
        }
        if (in_array(self::FILTRE_TYPE_VOLUME_HORAIRE, $filtres)) {
            $this->setTypeVolumeHoraire($vh->getTypeVolumeHoraire());
        }
        if (in_array(self::FILTRE_VALIDATION, $filtres)) {
            if ($vh->isValide()) {
                $this->setValidation(true);
            }
        }

        return $this;
    }



    /**
     *
     * @return type
     */
    public function getHeures()
    {
        $volumesHoraires = $this->getVolumeHoraires();
        $heures          = 0;
        foreach ($volumesHoraires as $volumeHoraire) {
            $heures += $volumeHoraire->getHeures();
        }

        return $heures;
    }



    public function isVolumeHoraireModifiable(VolumeHoraire $volumeHoraire)
    {
        return !(
            $volumeHoraire->isValide()
            || ($volumeHoraire->getSource() && $volumeHoraire->getSource()->getImportable())
            || $volumeHoraire->getRemove()
            || $volumeHoraire->getHistoDestruction()
        );
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
        if ($this->__debug) $this->__debug->dumpAction('setHeures', $heures);

        if ($heures < 0) {
            throw new LogicException('Le nombre d\'heures ne peut être inférieur à zéro');
        }
        if (!$this->typeVolumeHoraire instanceof TypeVolumeHoraire) {
            throw new LogicException('Le type de volume horaire n\'est pas défini');
        }
        if (!$this->periode instanceof Periode) {
            throw new LogicException('La période n\'est pas définie');
        }
        if (!$this->typeIntervention instanceof TypeIntervention) {
            throw new LogicException('Le type d\'intervention n\'est pas défini');
        }

        $lastHeures = $this->getHeures();
        $newHeures  = $heures - $lastHeures;

        if (0 == $newHeures) return $this; // pas de changement!!

        /** @var VolumeHoraire[] $vhs */
        $vhs = $this->getVolumeHoraires();
        foreach ($vhs as $i => $vh) {
            // Si le volume horaire est déjà validé
            // ou bien qu'il est importé,
            // ou s'il a été détruit
            // => on ne le touchera pas
            if (!$this->isVolumeHoraireModifiable($vh)) {
                unset($vhs[$i]);
            }
        }
        if (0 < count($vhs)) {
            /* D'abord, on trie les volumes horaires pour savoir lesquels devront être modifiés en priorité */
            usort($vhs, function (VolumeHoraire $a, VolumeHoraire $b) use ($heures, $newHeures) {
                $aHeures = $a->getHeures();
                $bHeures = $b->getHeures();

                /* On retire des heures d'abord là ou il y en a!! */
                if ($newHeures < 0) {
                    if ($aHeures >= 0 && $bHeures < 0) {
                        return -1;
                    }
                    if ($bHeures >= 0 && $aHeures < 0) {
                        return 1;
                    }
                }

                /* Sinon on trie par date */
                $hda = $a->getHoraireDebut() ? $a->getHoraireDebut()->getTimestamp() : 0;
                $hdb = $b->getHoraireDebut() ? $b->getHoraireDebut()->getTimestamp() : 0;
                if ($hda != $hdb) return ($hda > $hdb) ? 1 : -1;

                /* Sinon on privilégie sans motif de non paiement */
                $mnpa = $a->getMotifNonPaiement() ? 1 : 0;
                $mnpb = $b->getMotifNonPaiement() ? 1 : 0;
                if ($mnpa != $mnpb) return $mnpa > $mnpb;

                /* Si c'est pareil alors on ne supprime surtout pas pour pouvoir garder!! */
                if ($heures + $aHeures * -1 == 0) {
                    return 1;
                }
                if ($heures + $bHeures * -1 == 0) {
                    return -1;
                }

                /* Si c'est l'exact inverse, alors on supprime direct!! */
                if ($newHeures + $aHeures == 0) {
                    return -1;
                }
                if ($newHeures + $bHeures == 0) {
                    return 1;
                }

                /* Si c'est l'inverse alors on supprime aussi */
                if ($newHeures > 0 && $aHeures <= 0 && $newHeures > ($aHeures * -1)) {
                    return -1;
                }
                if ($newHeures > 0 && $bHeures <= 0 && $newHeures > ($bHeures * -1)) {
                    return 1;
                }
                if ($newHeures <= 0 && $aHeures > 0 && $newHeures <= ($aHeures * -1)) {
                    return -1;
                }
                if ($newHeures <= 0 && $bHeures > 0 && $newHeures <= ($bHeures * -1)) {
                    return 1;
                }

                /* Sinon on supprime les plus petits */
                if ($newHeures > 0) {
                    return $aHeures < $bHeures;
                } else {
                    return $aHeures > $bHeures;
                }

                return $hca < $hcb;
            });

            /* Ensuite on calcule pour obtenir le nouveau nombre d'heures */

            /* Première passe : on met à 0 tout ce qui est possible */
            foreach ($vhs as $volumeHoraire) {
                if ($newHeures == 0) break;

                $vhHeures    = $volumeHoraire->getHeures();
                $vhNewHeures = $vhHeures;

                /* Si les heures sont de signe différent et qu'il y a */
                if ($newHeures < 0) {
                    if ($vhHeures < 0) {
                        $vhNewHeures += $newHeures;
                        $newHeures   = 0;// OK
                    } else {
                        if ($vhHeures <= $newHeures * -1) {
                            $vhNewHeures = 0;
                            $newHeures   += $vhHeures;
                        } else {
                            $vhNewHeures += $newHeures;
                            $newHeures   = 0; // OK
                        }
                    }
                } else {
                    if ($vhHeures < 0) {
                        if ($vhHeures * -1 <= $newHeures) {
                            $vhNewHeures = 0; // OK
                            $newHeures   += $vhHeures;
                        } else {
                            $vhNewHeures += $newHeures;
                            $newHeures   = 0; // OK
                        }
                    } else {
                        $vhNewHeures += $newHeures; // OK
                        $newHeures   = 0;
                    }
                }

                if ($vhHeures != $vhNewHeures) {
                    if ($vhNewHeures == 0) {
                        $volumeHoraire->setRemove(true);
                    } else {
                        $volumeHoraire->setHeures($vhNewHeures);
                    }
                }
            }

            /* Deuxième passe : on met les heures restantes s'il en reste sur le premier volume horaire trouvé */
            if ($newHeures != 0) {
                reset($vhs);
                $volumeHoraire = current($vhs);
                if ($volumeHoraire->getRemove()) {
                    $volumeHoraire->setRemove(false);
                    $volumeHoraire->setHeures(0);
                }
                $volumeHoraire->setHeures($volumeHoraire->getHeures() + $newHeures);
                $newHeures = 0;
            }
        }

        if (0 != $newHeures) {
            $volumeHoraire = new VolumeHoraire();
            $volumeHoraire->setService($this->getService());
            $volumeHoraire->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
            $volumeHoraire->setPeriode($this->getPeriode());
            $volumeHoraire->setTypeIntervention($this->getTypeIntervention());
            if ($this->getHoraireDebut() instanceof \DateTime) {
                $volumeHoraire->setHoraireDebut($this->getHoraireDebut());
            }
            if ($this->getHoraireFin() instanceof \DateTime) {
                $volumeHoraire->setHoraireFin($this->getHoraireFin());
            }
            if ($this->getMotifNonPaiement() instanceof MotifNonPaiement) {
                $volumeHoraire->setMotifNonPaiement($this->getMotifNonPaiement());
            }
            $volumeHoraire->setHeures($newHeures);
            $this->getService()->addVolumeHoraire($volumeHoraire);
        }

        return $this;
    }



    /**
     * @param $heures
     * @param $ancienMotifNonPaiement
     * @param $motifNonPaiement
     *
     * @return $this
     */
    public function moveHeuresFromAncienMotifNonPaiement($heures, $ancienMotifNonPaiement)
    {
        if ($this->__debug) $this->__debug->dumpAction('moveHeuresFromAncienMotifNonPaiement', $heures, $ancienMotifNonPaiement);

        if ($ancienMotifNonPaiement == $this->getMotifNonPaiement()) {
            return $this->setHeures($heures);
        }

        $heuresActuelles = $this->getHeures();

        $vhl = $this->createChild();
        $vhl->setMotifNonPaiement($ancienMotifNonPaiement);
        $heuresAnciennes = $vhl->getHeures();

        $vhl->setHeures(max($heuresAnciennes - $heures, 0));
        $this->setHeures($heuresActuelles + $heures);

        return $this;
    }



    /**
     * @param \DateTime|null        $horaireDebut
     * @param \DateTime|null        $horaireFin
     * @param TypeIntervention      $typeIntervention
     * @param Periode               $periode
     * @param MotifNonPaiement|null $motifNonPaiement
     *
     * @return $this
     */
    public function changeAll($horaireDebut, $horaireFin, TypeIntervention $typeIntervention, Periode $periode, $motifNonPaiement)
    {
        if ($this->__debug) $this->__debug->dumpAction('changeAll', $horaireDebut, $horaireFin, $typeIntervention, $periode, $motifNonPaiement);

        $vhs             = $this->getVolumeHoraires();
        $heuresAReporter = 0;
        $reports         = [];

        $reportFilters = [
            self::FILTRE_HORAIRE_DEBUT,
            self::FILTRE_HORAIRE_FIN,
            self::FILTRE_TYPE_INTERVENTION,
            self::FILTRE_PERIODE,
            self::FILTRE_MOTIF_NON_PAIEMENT,
        ];

        foreach ($vhs as $vh) {
            $change =
                ($vh->getTypeIntervention() != $typeIntervention)
                || ($vh->getPeriode() != $periode)
                || (!is_bool($horaireDebut) && $vh->getHoraireDebut() != $horaireDebut)
                || (!is_bool($horaireFin) && $vh->getHoraireFin() != $horaireFin)
                || (!is_bool($motifNonPaiement) && $vh->getMotifNonPaiement() != $motifNonPaiement);

            if ($change) {
                if ($this->isVolumeHoraireModifiable($vh)) {
                    $vh->setTypeIntervention($typeIntervention);
                    $vh->setPeriode($periode);
                    if (!is_bool($horaireDebut)) {
                        $vh->setHoraireDebut($horaireDebut);
                    }
                    if (!is_bool($horaireFin)) {
                        $vh->setHoraireFin($horaireFin);
                    }
                    if (!is_bool($motifNonPaiement)) {
                        $vh->setMotifNonPaiement($motifNonPaiement);
                    }
                } else {
                    $heuresAReporter += $vh->getHeures();
                    $rid             = $this->makeSousListeId($vh, $reportFilters);
                    if (!isset($reports[$rid])) {
                        $reports[$rid] = [
                            'liste'  => $this->createChild()->filterByVolumeHoraire($vh, $reportFilters),
                            'heures' => 0,
                        ];
                    }
                    $reports[$rid]['heures'] += $vh->getHeures();
                }
            }
        }

        /* On retire les heures sur les listes comportant des VH non modifiables */
        foreach ($reports as $report) {
            $report['liste']->setHeures($report['liste']->getHeures() - $report['heures']);
        }

        /* On met à jour les filtres si besoin */
        if ($this->getHoraireDebut() !== false) {
            $this->setHoraireDebut($horaireDebut);
        }
        if ($this->getHoraireFin() !== false) {
            $this->setHoraireFin($horaireFin);
        }
        if ($this->getTypeIntervention() instanceof TypeIntervention) {
            $this->setTypeIntervention($typeIntervention);
        }
        if ($this->getPeriode() instanceof Periode) {
            $this->setPeriode($periode);
        }
        if ($motifNonPaiement && ($this->getMotifNonPaiement() instanceof MotifNonPaiement || null === $this->getMotifNonPaiement())) {
            $this->setMotifNonPaiement($motifNonPaiement);
        }

        /* On ajoute les heures correspondant aux nouveaux filtres */
        if ($heuresAReporter !== 0) {
            $this->setHeures($this->getHeures() + $heuresAReporter);
        }

        return $this;
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
     * Retourne le nombre de volumes horaires concernés par la liste
     *
     * @return integer
     */
    public function count()
    {
        return count($this->getVolumeHoraires());
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
        if ($typeVolumeHoraire !== $this->typeVolumeHoraire) {
            if ($this->__debug) $this->__debug->dumpAction('setTypeVolumeHoraire', $typeVolumeHoraire);

            if (!(is_bool($typeVolumeHoraire) || null === $typeVolumeHoraire || $typeVolumeHoraire instanceof TypeVolumeHoraire)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->typeVolumeHoraire = $typeVolumeHoraire;
        }

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
        if ($periode !== $this->periode) {
            if ($this->__debug) $this->__debug->dumpAction('setPeriode', $periode);

            if (!(is_bool($periode) || null === $periode || $periode instanceof Periode)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->periode = $periode;
        }

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
     *
     * @param TypeIntervention|boolean $typeIntervention
     *
     * @return self
     */
    public function setTypeIntervention($typeIntervention)
    {
        if ($typeIntervention !== $this->typeIntervention) {
            if ($this->__debug) $this->__debug->dumpAction('setTypeIntervention', $typeIntervention);

            if (!(is_bool($typeIntervention) || null === $typeIntervention || $typeIntervention instanceof TypeIntervention)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->typeIntervention = $typeIntervention;
        }

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
        if ($horaireDebut !== $this->horaireDebut) {
            if ($this->__debug) $this->__debug->dumpAction('setHoraireDebut', $horaireDebut);

            if (!(is_bool($horaireDebut) || null === $horaireDebut || $horaireDebut instanceof \DateTime)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->horaireDebut = $horaireDebut;
        }

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
        if ($horaireFin !== $this->horaireFin) {
            if ($this->__debug) $this->__debug->dumpAction('setHoraireFin', $horaireFin);

            if (!(is_bool($horaireFin) || null === $horaireFin || $horaireFin instanceof \DateTime)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->horaireFin = $horaireFin;
        }

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
        if ($etatVolumeHoraire !== $this->etatVolumeHoraire) {
            if ($this->__debug) $this->__debug->dumpAction('setEtatVolumeHoraire', $etatVolumeHoraire);

            if (!(is_bool($etatVolumeHoraire) || null === $etatVolumeHoraire || $etatVolumeHoraire instanceof EtatVolumeHoraire)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->etatVolumeHoraire = $etatVolumeHoraire;
        }

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
        if ($motifNonPaiement !== $this->motifNonPaiement) {
            if ($this->__debug) $this->__debug->dumpAction('setMotifNonPaiement', $motifNonPaiement);

            if (!(is_bool($motifNonPaiement) || null === $motifNonPaiement || $motifNonPaiement instanceof MotifNonPaiement)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->motifNonPaiement = $motifNonPaiement;
        }

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
        if ($contrat !== $this->contrat) {
            if ($this->__debug) $this->__debug->dumpAction('setContrat', $contrat);

            if (!(is_bool($contrat) || null === $contrat || $contrat instanceof Contrat)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->contrat = $contrat;
        }

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
        if ($validation !== $this->validation) {
            if ($this->__debug) $this->__debug->dumpAction('setValidation', $validation);

            if (!(is_bool($validation) || null === $validation || $validation instanceof Validation)) {
                throw new RuntimeException('Valeur non autorisée');
            }
            $this->validation = $validation;
        }

        return $this;
    }
}