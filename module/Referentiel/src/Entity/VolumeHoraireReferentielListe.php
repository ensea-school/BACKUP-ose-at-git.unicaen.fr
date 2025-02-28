<?php

namespace Referentiel\Entity;

use LogicException;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Referentiel\Hydrator\ListeFilterHydrator;
use Referentiel\Service\VolumeHoraireReferentielServiceAwareTrait;
use RuntimeException;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenImport\Entity\Db\Source;
use Workflow\Entity\Db\Validation;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireReferentielListe
{
    const FILTRE_ETAT_VOLUME_HORAIRE = 'etat-volume-horaire';
    const FILTRE_HORAIRE_DEBUT       = 'horaire-debut';
    const FILTRE_HORAIRE_FIN         = 'horaire-fin';
    const FILTRE_TYPE_VOLUME_HORAIRE = 'type-volume-horaire';
    const FILTRE_VALIDATION          = 'validation';
    const FILTRE_SOURCE              = 'source';
    const FILTRE_HISTORIQUE          = 'historique';
    const FILTRE_NEW                 = 'new';
    const FILTRE_LIST                = [
        self::FILTRE_ETAT_VOLUME_HORAIRE, self::FILTRE_HORAIRE_DEBUT, self::FILTRE_HORAIRE_FIN,
        self::FILTRE_TYPE_VOLUME_HORAIRE, self::FILTRE_VALIDATION, self::FILTRE_SOURCE, self::FILTRE_HISTORIQUE,
        self::FILTRE_NEW,
    ];

    const FILTRES_LIST = [
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

    use VolumeHoraireReferentielServiceAwareTrait;

    /**
     * @var ServiceReferentiel|bool
     */
    protected $service;

    /**
     * @var TypeVolumeHoraire|bool
     */
    protected $typeVolumeHoraire = false;

    /**
     *
     * @var EtatVolumeHoraire|bool
     */
    protected $etatVolumeHoraire = false;

    /**
     * @var Validation|bool
     */
    protected $validation = false;

    /**
     * @var \DateTime|bool
     */
    protected $horaireDebut = false;

    /**
     * @var \DateTime|bool
     */
    protected $horaireFin = false;

    /**
     * @var Source|bool
     */
    protected      $source             = false;

    protected bool $filterByHistorique = true;

    protected bool $new                = false;



    function __construct(ServiceReferentiel $service)
    {
        $this->setService($service);
    }



    /**
     * Retourne la liste des volumes horaires du service (sans les motifs de non paiement)
     * Les clés sont les codes des types d'intervention des volumes horaires
     *
     * @return VolumeHoraireReferentiel[]
     */
    public function getVolumeHorairesReferentiel(): array
    {
        $data = [];

        $vhrs = $this->getService()->getVolumeHoraireReferentiel();
        foreach ($vhrs as $volumeHoraire) {
            if ($this->match($volumeHoraire)) {
                $data[$volumeHoraire->getId()] = $volumeHoraire;
            }
        }

        return $data;
    }



    public function getService(): ServiceReferentiel
    {
        return $this->service;
    }



    public function setService(ServiceReferentiel $service): VolumeHoraireReferentielListe
    {
        $this->service = $service;

        return $this;
    }



    /**
     *
     * @return TypeVolumeHoraire|bool
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }



    /**
     *
     * @param TypeVolumeHoraire|bool $typeVolumeHoraire
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
     * @return EtatVolumeHoraire|bool
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire;
    }



    /**
     *
     * @param EtatVolumeHoraire|bool $etatVolumeHoraire
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
     * @return Validation|bool
     */
    public function getValidation()
    {
        return $this->validation;
    }



    /**
     *
     * @param Validation|bool $validation
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
     * @return VolumeHoraireReferentielListe
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
     * @return VolumeHoraireReferentielListe
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
     * @return VolumeHoraireReferentielListe
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }



    public function getFilterByHistorique(): bool
    {
        return $this->filterByHistorique;
    }



    public function setFilterByHistorique(bool $filterByHistorique): VolumeHoraireReferentielListe
    {
        $this->filterByHistorique = $filterByHistorique;

        return $this;
    }



    public function getNew(): bool
    {
        return $this->new;
    }



    public function setNew(bool $new): VolumeHoraireReferentielListe
    {
        $this->new = $new;

        return $this;
    }



    /**
     * Détermine si un volume horaire répond aux critères de la liste ou non
     */
    public function match(VolumeHoraireReferentiel $volumeHoraire): bool
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
            $etatVolumeHoraire = $volumeHoraire->getEtatVolumeHoraireReferentiel();
            if (true === $this->etatVolumeHoraire) {
                if (null === $etatVolumeHoraire) return false;
            } else {
                if ($etatVolumeHoraire->getOrdre() < $this->etatVolumeHoraire->getOrdre()) return false;
            }
        }
        if (false !== $this->validation && !$volumeHoraire->isAutoValidation()) {
            $validation = $volumeHoraire->getValidation();
            if (true === $this->validation) {
                if ($validation->isEmpty()) {
                    return false;
                }
            } elseif (null === $this->validation) {
                if (!$validation->isEmpty()) {
                    return false;
                }
            } else {
                if (!$validation->contains($this->validation)) {
                    return false;
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



    private function makeSousListeId(VolumeHoraireReferentiel $vh, array $filtres = []): string
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
     * @return array|VolumeHoraireReferentielListe[]
     */
    public function getSousListes(array $filtres = []): array
    {
        $listes = [];
        $vhs    = $this->getService()->getVolumeHoraireReferentiel();
        foreach ($vhs as $vh) {
            $vhId = $this->makeSousListeId($vh, $filtres);
            if (!array_key_exists($vhId, $listes)) {
                $listes[$vhId] = $this->createChild()->filterByVolumeHoraireReferentiel($vh, $filtres);
            }
        }

        return $listes;
    }



    /**
     * retourne une liste fille de volumes horaires
     */
    public function createChild(): VolumeHoraireReferentielListe
    {
        $vhlph              = new ListeFilterHydrator();
        $volumeHoraireListe = new VolumeHoraireReferentielListe($this->getService());
        $vhlph->hydrate($vhlph->extract($this), $volumeHoraireListe);

        //$volumeHoraireListe->__debug = $this->__debug;

        return $volumeHoraireListe;
    }



    public function filterByVolumeHoraireReferentiel(VolumeHoraireReferentiel $vh, array $filtres = []): VolumeHoraireReferentielListe
    {
        if (in_array(self::FILTRE_ETAT_VOLUME_HORAIRE, $filtres)) {
            $this->setEtatVolumeHoraire($vh->getEtatVolumeHoraireReferentiel());
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
        if (in_array(self::FILTRE_SOURCE, $filtres)) {
            $this->setSource($vh->getSource());
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
     * Retourne la liste des volumes horaires du service.
     *
     * @return array|VolumeHoraireReferentiel[]
     */
    public function get(): array
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
     */
    public function count(): int
    {
        return count($this->get());
    }



    /**
     * Détermine si la liste est vide ou non
     */
    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }



    /**
     * retourne une liste fille de volumes horaires
     */
    public function getChild(): VolumeHoraireReferentielListe
    {
        $volumeHoraireListe = new VolumeHoraireReferentielListe($this->getService());
        $volumeHoraireListe->setTypeVolumeHoraire($this->typeVolumeHoraire);
        $volumeHoraireListe->setEtatVolumeHoraire($this->etatVolumeHoraire);
        $volumeHoraireListe->setValidation($this->validation);
        $volumeHoraireListe->setHoraireDebut($this->horaireDebut);
        $volumeHoraireListe->setHoraireFin($this->horaireFin);
        $volumeHoraireListe->setSource($this->source);

        return $volumeHoraireListe;
    }



    public function getHeures(): float
    {
        $volumesHoraires = $this->get();
        $heures          = 0;
        foreach ($volumesHoraires as $volumeHoraire) {
            $heures += $volumeHoraire->getHeures();
        }

        return $heures;
    }



    public function isVolumeHoraireModifiable(VolumeHoraireReferentiel $volumeHoraire): bool
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
     * @return VolumeHoraireReferentielListe
     * @throws LogicException
     */
    public function setHeures(float $heures): VolumeHoraireReferentielListe
    {
        if ($heures < 0) {
            throw new LogicException('Le nombre d\'heures ne peut être inférieur à zéro');
        }
        if (!$this->typeVolumeHoraire instanceof TypeVolumeHoraire) {
            throw new LogicException('Le type de volume horaire n\'est pas défini');
        }

        $lastHeures = $this->getHeures();
        $newHeures  = $heures - $lastHeures;

        if (0 == $newHeures) return $this; // pas de changement!!

        $vhs = $this->getVolumeHorairesReferentiel();
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
            usort($vhs, function (VolumeHoraireReferentiel $a, VolumeHoraireReferentiel $b) use ($heures, $newHeures) {
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
                    return $bHeures - $aHeures;
                } else {
                    return $aHeures - $bHeures;
                }

                return $hcb - $hca;
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
            $volumeHoraire = $this->getServiceVolumeHoraireReferentiel()->newEntity();
            $volumeHoraire->setServiceReferentiel($this->getService());
            $volumeHoraire->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
            if ($this->getHoraireDebut() instanceof \DateTime) {
                $volumeHoraire->setHoraireDebut($this->getHoraireDebut());
            }
            if ($this->getHoraireFin() instanceof \DateTime) {
                $volumeHoraire->setHoraireFin($this->getHoraireFin());
            }
            $volumeHoraire->setHeures($newHeures);
            $this->getService()->addVolumeHoraireReferentiel($volumeHoraire);
        }

        return $this;
    }



    /**
     * Vérifie l'éligibilité d'un volume horaire à la liste
     *
     * @param VolumeHoraireReferentiel $volumeHoraire
     *
     * @return bool
     * @throws LogicException
     */
    protected function checkVolumeHoraireEligibilite(VolumeHoraireReferentiel $volumeHoraire): bool
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



    public function filtersToArray(): array
    {
        $result = [];
        if ($this->getTypeVolumeHoraire() instanceof TypeVolumeHoraire) {
            $result[self::FILTRE_TYPE_VOLUME_HORAIRE] = $this->getTypeVolumeHoraire()->getId();
        }
        if ($this->getEtatVolumeHoraire() instanceof EtatVolumeHoraire) {
            $result[self::FILTRE_ETAT_VOLUME_HORAIRE] = $this->getEtatVolumeHoraire()->getId();
        }
        if ($this->getValidation() instanceof Validation) {
            $result[self::FILTRE_VALIDATION] = $this->getValidation()->getId();
        }
        if ($this->getHoraireDebut() instanceof \DateTime) {
            $result[self::FILTRE_HORAIRE_DEBUT] = $this->getHoraireDebut();
        }
        if ($this->getHoraireFin() instanceof \DateTime) {
            $result[self::FILTRE_HORAIRE_FIN] = $this->getHoraireFin();
        }
        if ($this->getSource() instanceof Source) {
            $result[self::FILTRE_SOURCE] = $this->getSource()->getId();
        }
        if ($this->getFilterByHistorique()) {
            $result[self::FILTRE_HISTORIQUE] = $this->getFilterByHistorique();
        }

        return $result;
    }
}