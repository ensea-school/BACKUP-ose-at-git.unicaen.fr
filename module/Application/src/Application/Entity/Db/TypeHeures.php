<?php

namespace Application\Entity\Db;

/**
 * TypeHeures
 */
class TypeHeures implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const FI          = 'fi';
    const FA          = 'fa';
    const FC          = 'fc';
    const FC_MAJOREES = 'fc_majorees';
    const REFERENTIEL = 'referentiel';

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelleCourt;

    /**
     * @var string
     */
    private $libelleLong;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var boolean
     */
    private $eligibleCentreCoutEp;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $centreCout;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $elementPedagogique;

    /**
     *
     * @var TypeHeures
     */
    private $typeHeuresElement;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeHeures
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
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return TypeHeures
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    /**
     * Get libelleCourt
     *
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }



    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     *
     * @return TypeHeures
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    /**
     * Get libelleLong
     *
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }



    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return TypeHeures
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
     * Set eligibleCentreCoutEp
     *
     * @param boolean $eligibleCentreCoutEp
     *
     * @return TypeHeures
     */
    public function setEligibleCentreCoutEp($eligibleCentreCoutEp)
    {
        $this->eligibleCentreCoutEp = $eligibleCentreCoutEp;

        return $this;
    }



    /**
     * Get eligibleCentreCoutEp
     *
     * @return boolean
     */
    public function getEligibleCentreCoutEp()
    {
        return $this->eligibleCentreCoutEp;
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
     * Get centreCout
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }



    /**
     * Get elementPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     *
     * @return type
     */
    function getTypeHeuresElement()
    {
        return $this->typeHeuresElement;
    }



    /**
     *
     * @param \Application\Entity\Db\TypeHeures $typeHeuresElement
     *
     * @return \Application\Entity\Db\TypeHeures
     */
    function setTypeHeuresElement(TypeHeures $typeHeuresElement)
    {
        $this->typeHeuresElement = $typeHeuresElement;

        return $this;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleCourt();
    }



    /**
     *
     * @return string
     */
    public function toHtml()
    {
        return '<abbr title="' . $this->getLibelleLong() . '">' . $this->getLibelleCourt() . '</abbr>';
    }
}