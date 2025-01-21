<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Parametre
 */
class Parametre implements HistoriqueAwareInterface
{
    const AVENANT_AUTORISE  = 'avenant_autorise';
    const AVENANT_DESACTIVE = 'avenant_desactive';

    const CONTRAT_DATE   = 'contrat_date';
    const CONTRAT_DIRECT = 'contrat_direct';

    const CONTRAT_ENS_COMPOSANTE = 'contrat_ens_composante';
    const CONTRAT_ENS_GLOBALE    = 'contrat_ens_globale';

    const CONTRAT_FRANCHI_DATE_RETOUR = 'date-retour';

    const CONTRAT_FRANCHI_VALIDATION = 'validation';

    const CONTRAT_MIS_COMPOSANTE = 'contrat_mis_composante';
    const CONTRAT_MIS_GLOBALE    = 'contrat_mis_globale';
    const CONTRAT_MIS_MISSION    = 'contrat_mis_mission';

    const SERVICES_MODALITE_CALENDAIRE = 'calendaire';
    const SERVICES_MODALITE_SEMESTRIEL = 'semestriel';

    /* Nécessaire pour la migration de 23 a 24 */
    const OLD_AVENANT_AUTORISE  = 'avenant_autorise';
    const OLD_AVENANT_STRUCT    = 'avenant_struct';
    const OLD_AVENANT_DESACTIVE = 'avenant_desactive';

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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }



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
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
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
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
