<?php

namespace Application\Entity\Db;

/**
 * EtatVolumeHoraire
 */
class EtatVolumeHoraire implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const CODE_SAISI         = 'saisi';
    const CODE_VALIDE        = 'valide';
    const CODE_CONTRAT_EDITE = 'contrat-edite';
    const CODE_CONTRAT_SIGNE = 'contrat-signe';

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
    private $ordre;

    /**
     * @var integer
     */
    private $id;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return EtatVolumeHoraire
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return EtatVolumeHoraire
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return EtatVolumeHoraire
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
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



    public function __toString()
    {
        return $this->getLibelle();
    }
}
