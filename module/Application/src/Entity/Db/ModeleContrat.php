<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\StatutAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;

/**
 * ModeleContrat
 */
class ModeleContrat
{
    use StatutAwareTrait;
    use StructureAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $fichier;

    /**
     * @var string
     */
    protected $requete;

    /**
     * @var string
     */
    protected $bloc1Nom;

    /**
     * @var string
     */
    protected $bloc1Requete;

    /**
     * @var string
     */
    protected $bloc2Nom;

    /**
     * @var string
     */
    protected $bloc2Requete;

    /**
     * @var string
     */
    protected $bloc3Nom;

    /**
     * @var string
     */
    protected $bloc3Requete;

    /**
     * @var string
     */
    protected $bloc4Nom;

    /**
     * @var string
     */
    protected $bloc4Requete;

    /**
     * @var string
     */
    protected $bloc5Nom;

    /**
     * @var string
     */
    protected $bloc5Requete;

    /**
     * @var string
     */
    protected $bloc6Nom;

    /**
     * @var string
     */
    protected $bloc6Requete;

    /**
     * @var string
     */
    protected $bloc7Nom;

    /**
     * @var string
     */
    protected $bloc7Requete;

    /**
     * @var string
     */
    protected $bloc8Nom;

    /**
     * @var string
     */
    protected $bloc8Requete;

    /**
     * @var string
     */
    protected $bloc9Nom;

    /**
     * @var string
     */
    protected $bloc9Requete;

    /**
     * @var string
     */
    protected $bloc10Nom;

    /**
     * @var string
     */
    protected $bloc10Requete;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return ModeleContrat
     */
    public function setId($id): ModeleContrat
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return ModeleContrat
     */
    public function setLibelle($libelle): ModeleContrat
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string
     */
    public function getFichier()
    {
        return $this->fichier;
    }



    /**
     * @return bool
     */
    public function hasFichier(): bool
    {
        if (is_resource($this->fichier)) {
            return !empty(stream_get_contents($this->fichier, 1));
        } else {
            return !empty($this->fichier);
        }
    }



    /**
     * @param string $fichier
     *
     * @return ModeleContrat
     */
    public function setFichier($fichier): ModeleContrat
    {
        $this->fichier = $fichier;

        return $this;
    }



    /**
     * @return string
     */
    public function getRequete()
    {
        return $this->requete;
    }



    /**
     * @param string $requete
     *
     * @return ModeleContrat
     */
    public function setRequete($requete): ModeleContrat
    {
        $this->requete = $requete;

        return $this;
    }



    public function getBlocs(): array
    {
        $blocs = [];

        for ($i = 1; $i <= 10; $i++) {
            $nomVar     = "bloc$i" . "Nom";
            $requeteVar = "bloc$i" . "Requete";
            if ($this->{$nomVar} && $this->{$requeteVar}) {
                $blocs[$this->{$nomVar}] = $this->{$requeteVar};
            }
        }

        return $blocs;
    }



    public function setBlocs(array $blocs): ModeleContrat
    {
        $i = 1;
        foreach ($blocs as $nom => $requete) {
            $nomVar              = "bloc$i" . "Nom";
            $requeteVar          = "bloc$i" . "Requete";
            $this->{$nomVar}     = $nom;
            $this->{$requeteVar} = $requete;
            $i++;
        }
        for (null; $i <= 10; $i++) { // on vide le reste!!
            $nomVar              = "bloc$i" . "Nom";
            $requeteVar          = "bloc$i" . "Requete";
            $this->{$nomVar}     = null;
            $this->{$requeteVar} = null;
        }

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
