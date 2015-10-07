<?php

namespace Application\Entity\Db;


/**
 * IntervenantExterieur
 */
class IntervenantExterieur extends Intervenant
{
    /**
     * @var \Application\Entity\Db\TypeIntervenantExterieur
     */
    protected $typeIntervenantExterieur;

    /**
     * @var \Application\Entity\Db\SituationFamiliale
     */
    protected $situationFamiliale;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $dossier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $contrat;



    /**
     *
     */
    public function __construct()
    {
        $this->contrat = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Get estUneFemme
     *
     * @return bool
     */
    public function estUneFemme()
    {
        $civilite = $this->getDossier() ? $this->getDossier()->getCivilite() : $this->getCivilite();

        return Civilite::SEXE_F === $civilite->getSexe();
    }



    /**
     * Set typeIntervenantExterieur
     *
     * @param \Application\Entity\Db\TypeIntervenantExterieur $typeIntervenantExterieur
     *
     * @return IntervenantExterieur
     */
    public function setTypeIntervenantExterieur(\Application\Entity\Db\TypeIntervenantExterieur $typeIntervenantExterieur = null)
    {
        $this->typeIntervenantExterieur = $typeIntervenantExterieur;

        return $this;
    }



    /**
     * Get typeIntervenantExterieur
     *
     * @return \Application\Entity\Db\TypeIntervenantExterieur
     */
    public function getTypeIntervenantExterieur()
    {
        return $this->typeIntervenantExterieur;
    }



    /**
     * Set situationFamiliale
     *
     * @param \Application\Entity\Db\SituationFamiliale $situationFamiliale
     *
     * @return IntervenantExterieur
     */
    public function setSituationFamiliale(\Application\Entity\Db\SituationFamiliale $situationFamiliale = null)
    {
        $this->situationFamiliale = $situationFamiliale;

        return $this;
    }



    /**
     * Get situationFamiliale
     *
     * @return \Application\Entity\Db\SituationFamiliale
     */
    public function getSituationFamiliale()
    {
        return $this->situationFamiliale;
    }



    /**
     * Set dossier
     *
     * @param Dossier $dossier
     *
     * @return IntervenantExterieur
     */
    public function setDossier(Dossier $dossier)
    {
        $this->dossier->clear();
        $this->dossier->add($dossier);

        return $this;
    }



    /**
     * Get dossier
     *
     * @return Dossier|null
     */
    public function getDossier()
    {
        $dossiers = $this->dossier->filter(function (Dossier $d) {
            return $d->getHistoDestruction() === null;
        });

        return $dossiers->first() ?: null;
    }



    /**
     * Add contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     *
     * @return Intervenant
     */
    public function addContrat(\Application\Entity\Db\Contrat $contrat)
    {
        $this->contrat[] = $contrat;

        return $this;
    }



    /**
     * Remove contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     */
    public function removeContrat(\Application\Entity\Db\Contrat $contrat)
    {
        $this->contrat->removeElement($contrat);
    }



    /**
     * Get contrat
     *
     * @param \Application\Entity\Db\TypeContrat $typeContrat
     * @param \Application\Entity\Db\Structure   $structure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrat(TypeContrat $typeContrat = null, Structure $structure = null)
    {
        if (null === $this->contrat) {
            return null;
        }

        $filter   = function (Contrat $contrat) use ($typeContrat, $structure) {
            if ($typeContrat && $typeContrat !== $contrat->getTypeContrat()) {
                return false;
            }
            if ($structure && $structure !== $contrat->getStructure()) {
                return false;
            }

            return true;
        };
        $contrats = $this->contrat->filter($filter);

        return $contrats;
    }



    /**
     * Get contrat initial
     *
     * @return Contrat|null
     */
    public function getContratInitial()
    {
        if (!count($this->getContrat())) {
            return null;
        }

        $type = TypeContrat::CODE_CONTRAT;

        $filter   = function ($contrat) use ($type) {
            return $type === $contrat->getTypeContrat()->getCode();
        };
        $contrats = $this->getContrat()->filter($filter);

        return count($contrats) ? $contrats->first() : null;
    }



    /**
     * Get avenants
     *
     * @return Contrat[]|null
     */
    public function getAvenants()
    {
        $type = TypeContrat::CODE_AVENANT;

        $filter   = function (Contrat $contrat) use ($type) {
            return $type === $contrat->getTypeContrat()->getCode();
        };
        $contrats = $this->getContrat()->filter($filter);

        return $contrats;
    }



    /**
     * Retourne l'adresse mail personnelle éventuelle.
     * Si elle est null et que le paramètre le demande, retourne l'adresse par défaut.
     *
     * @param bool $fallbackOnDefault
     *
     * @return string
     */
    public function getEmailPerso($fallbackOnDefault = false)
    {
        $mail = null;

        if ($this->getDossier()) {
            $mail = $this->getDossier()->getEmailPerso();
        }

        if (!$mail && $fallbackOnDefault) {
            $mail = $this->getEmail();
        }

        return $mail;
    }
}