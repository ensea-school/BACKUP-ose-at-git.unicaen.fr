<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FonctionReferentiel
 */
class FonctionReferentiel implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var float
     */
    protected $plafond;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var DomaineFonctionnel
     */
    protected $domaineFonctionnel;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return FonctionReferentiel
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
     * @return FonctionReferentiel
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
     * @return FonctionReferentiel
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
     * Set plafond
     *
     * @param float $plafond
     *
     * @return FonctionReferentiel
     */
    public function setPlafond($plafond)
    {
        $this->plafond = $plafond;

        return $this;
    }



    /**
     * Get plafond
     *
     * @return float
     */
    public function getPlafond()
    {
        return $this->plafond;
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return self
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
     *
     * @return DomaineFonctionnel
     */
    function getDomaineFonctionnel()
    {
        return $this->domaineFonctionnel;
    }



    /**
     *
     * @param DomaineFonctionnel $domaineFonctionnel
     *
     * @return self
     */
    function setDomaineFonctionnel(DomaineFonctionnel $domaineFonctionnel)
    {
        $this->domaineFonctionnel = $domaineFonctionnel;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        $str = $this->getLibelleCourt();

        if ($this->getStructure()) {
            $str .= " (" . $this->getStructure() . ")";
        }

        return $str;
    }



    /**
     * @since PHP 5.6.0
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [
            'id' => $this->id,
            'libelleCourt' => $this->libelleCourt,
        ];
    }

}
