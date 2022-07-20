<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Parametre
 */
class Parametre implements HistoriqueAwareInterface
{
    const SERVICES_MODALITE_SEMESTRIEL = 'semestriel';
    const SERVICES_MODALITE_CALENDAIRE = 'calendaire';

    const CONTRAT_FRANCHI_VALIDATION  = 'validation';
    const CONTRAT_FRANCHI_DATE_RETOUR = 'date-retour';

    const AVENANT_AUTORISE  = 'avenant_autorise';
    const AVENANT_STRUCT    = 'avenant_struct';
    const AVENANT_DESACTIVE = 'avenant_desactive';

    const CONTRAT_DIRECT    = 'contrat_direct';
    const CONTRAT_DATE      = 'contrat_date';

    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $nom;

    /**
     * @var string
     */
    protected $valeur;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Parametre
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }



    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Parametre
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }



    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }



    /**
     * Set valeur
     *
     * @param string $valeur
     *
     * @return Parametre
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }



    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
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

}
