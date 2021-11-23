<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * Contrat
 */
class Contrat implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeContrat
     */
    private $typeContrat;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var integer
     */
    private $numeroAvenant;

    /**
     * @var \Application\Entity\Db\Contrat
     */
    protected $contrat;

    /**
     * @var \DateTime
     */
    private $dateRetourSigne;

    /**
     * @var \DateTime
     */
    private $dateEnvoiEmail;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $fichier;

    /**
     * @var float
     */
    private $totalHetd;



    /**
     * Libellé de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }



    /**
     * Libellé de cet objet.
     *
     * @param $avecArticle boolean Inclure l'article défini (utile pour inclure le libellé dans une phrase)
     * @param $deLe        boolean Activer la formulation "du"/"de l'" ou non
     *
     * @return string
     */
    public function toString($avecArticle = false, $deLe = false)
    {
        if ($this->estUnAvenant()) {
            if ($this->getValidation()) {
                $template = ($avecArticle ? ($deLe ? "de l'avenant n°%s" : "l'avenant n°%s") : "Avenant n°%s");
            } else {
                $template = ($avecArticle ? ($deLe ? "du projet d'avenant" : "le projet d'avenant") : "Projet d'avenant");
            }
        } else {
            if ($this->getValidation()) {
                $template = ($avecArticle ? ($deLe ? "du contrat n°%s" : "le contrat n°%s") : "Contrat n°%s");
            } else {
                $template = ($avecArticle ? ($deLe ? "du projet de contrat" : "le projet de contrat") : "Projet de contrat");
            }
        }

        return sprintf($template, $this->getReference());
    }



    /**
     * Retourne la référence (numéro) du contrat ou de l'avenant.
     *
     * @return string
     */
    public function getReference()
    {
        if ($this->estUnAvenant()) {
            if (!$this->getContrat()) {
                throw new \LogicException("Anomalie rencontrée: l'avenant {$this->getId()} n'est associé à aucun contrat.");
            }

            return sprintf("%s.%s", $this->getContrat()->getReference(), $this->getNumeroAvenant());
        } else {
            return sprintf("%s", $this->getId());
        }
    }



    /**
     * Indique s'il s'agit d'un avenant.
     *
     * @return boolean
     */
    public function estUnAvenant()
    {
        return $this->getTypeContrat() && $this->getTypeContrat()->estUnAvenant();
    }



    /**
     * Indique s'il s'agit d'un projet de contrat/avenant.
     *
     * @return boolean
     */
    public function estUnProjet()
    {
        return null === $this->getValidation();
    }



    /**
     * Set numeroAvenant
     *
     * @param integer $numeroAvenant
     *
     * @return Contrat
     */
    public function setNumeroAvenant($numeroAvenant)
    {
        $this->numeroAvenant = $numeroAvenant;

        return $this;
    }



    /**
     * Get numeroAvenant
     *
     * @return integer
     */
    public function getNumeroAvenant()
    {
        return $this->numeroAvenant;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set typeContrat
     *
     * @param \Application\Entity\Db\TypeContrat $typeContrat
     *
     * @return Contrat
     */
    public function setTypeContrat(\Application\Entity\Db\TypeContrat $typeContrat = null)
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }



    /**
     * Get typeContrat
     *
     * @return \Application\Entity\Db\TypeContrat
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }



    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return self
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return Intervenant
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     *
     * @return Contrat
     */
    public function setValidation(\Application\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }



    /**
     * Get validation
     *
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }



    /**
     * Set contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     *
     * @return Contrat
     */
    public function setContrat(\Application\Entity\Db\Contrat $contrat = null)
    {
        $this->contrat = $contrat;

        return $this;
    }



    /**
     * Get contrat
     *
     * @return \Application\Entity\Db\Contrat
     */
    public function getContrat()
    {
        return $this->contrat;
    }



    /**
     * Set dateRetourSigne
     *
     * @param \DateTime $dateRetourSigne
     *
     * @return Contrat
     */
    public function setDateRetourSigne($dateRetourSigne)
    {
        $this->dateRetourSigne = $dateRetourSigne;

        return $this;
    }



    /**
     * Get dateRetourSigne
     *
     * @return \DateTime
     */
    public function getDateRetourSigne()
    {
        return $this->dateRetourSigne;
    }



    /**
     * Set dateEnvoiEmail
     *
     * @param \DateTime $dateEnvoiEmail
     *
     * @return Contrat
     */
    public function setDateEnvoiEmail($dateEnvoiEmail)
    {
        $this->dateEnvoiEmail = $dateEnvoiEmail;

        return $this;
    }



    /**
     * Get dateEnvoiEmail
     *
     * @return \DateTime
     */
    public function getDateEnvoiEmail()
    {
        return $this->dateEnvoiEmail;
    }



    /**
     * Add fichier
     *
     * @param \Application\Entity\Db\Fichier $fichier
     *
     * @return self
     */
    public function addFichier(\Application\Entity\Db\Fichier $fichier)
    {
        $this->fichier[] = $fichier;

        return $this;
    }



    /**
     * Remove fichier
     *
     * @param \Application\Entity\Db\Fichier $fichier
     */
    public function removeFichier(\Application\Entity\Db\Fichier $fichier)
    {
        $this->fichier->removeElement($fichier);
    }



    /**
     * Get fichier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichier()
    {
        return $this->fichier;
    }



    /**
     * Retourne le total Hetd enregistré au moment de la création de ce contrat/avenant.
     *
     * Attention: il est null pour ceux créés avant l'ajout de cette colonne dans la table CONTRAT.
     *
     * @return float|null
     * @since 1.5
     */
    public function getTotalHetd()
    {
        return $this->totalHetd;
    }



    /**
     * Spécifie le total Hetd à enregistrer au moment de la création de ce contrat/avenant.
     *
     * @param float $totalHetd
     *
     * @return self
     * @since 1.5
     */
    public function setTotalHetd($totalHetd)
    {
        $this->totalHetd = $totalHetd;

        return $this;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        return 'Contrat';
    }
}
