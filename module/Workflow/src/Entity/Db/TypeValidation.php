<?php


namespace Workflow\Entity\Db;
/**
 * TypeValidation
 */
class TypeValidation
{
    const CODE_CANDIDATURE       = 'CANDIDATURE';
    const CODE_CLOTURE_REALISE   = 'CLOTURE_REALISE';
    const CODE_CONTRAT           = 'CONTRAT_PAR_COMP';
    const CODE_DECLARATION_PRIME = 'DECLARATION_PRIME';
    const CODE_DONNEES_PERSO     = 'DONNEES_PERSO_PAR_COMP';
    const CODE_ENSEIGNEMENT      = 'SERVICES_PAR_COMP';
    const CODE_FICHIER           = 'FICHIER';
    const CODE_MISSION           = 'MISSION';
    const CODE_MISSION_REALISE   = 'MISSION_REALISE';
    const CODE_OFFRE_EMPLOI      = 'OFFRE_EMPLOI';
    const CODE_PIECE_JOINTE      = 'PIECE_JOINTE';
    const CODE_REFERENTIEL       = 'REFERENTIEL';

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;



    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeValidation
     */
    public function setCode($code)
    {
        $this->code = $code;

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



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeValidation
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }
}
