<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * DemoOf
 */
class DemoOf
{
    /**
     * @var string
     */
    protected $codAnu;

    /**
     * @var string
     */
    protected $codElp;

    /**
     * @var integer
     */
    protected $codNivVet;

    /**
     * @var string
     */
    protected $codPel;

    /**
     * @var string
     */
    protected $codStr;

    /**
     * @var string
     */
    protected $codUe;

    /**
     * @var string
     */
    protected $codVet;

    /**
     * @var string
     */
    protected $libElp;

    /**
     * @var string
     */
    protected $libNivVet;

    /**
     * @var string
     */
    protected $libStr;

    /**
     * @var string
     */
    protected $libWebVet;

    /**
     * @var string
     */
    protected $licStr;

    /**
     * @var string
     */
    protected $licUe;

    /**
     * @var integer
     */
    protected $id;


    /**
     * Set codAnu
     *
     * @param string $codAnu
     * @return DemoOf
     */
    public function setCodAnu($codAnu)
    {
        $this->codAnu = $codAnu;

        return $this;
    }

    /**
     * Get codAnu
     *
     * @return string 
     */
    public function getCodAnu()
    {
        return $this->codAnu;
    }

    /**
     * Set codElp
     *
     * @param string $codElp
     * @return DemoOf
     */
    public function setCodElp($codElp)
    {
        $this->codElp = $codElp;

        return $this;
    }

    /**
     * Get codElp
     *
     * @return string 
     */
    public function getCodElp()
    {
        return $this->codElp;
    }

    /**
     * Set codNivVet
     *
     * @param integer $codNivVet
     * @return DemoOf
     */
    public function setCodNivVet($codNivVet)
    {
        $this->codNivVet = $codNivVet;

        return $this;
    }

    /**
     * Get codNivVet
     *
     * @return integer 
     */
    public function getCodNivVet()
    {
        return $this->codNivVet;
    }

    /**
     * Set codPel
     *
     * @param string $codPel
     * @return DemoOf
     */
    public function setCodPel($codPel)
    {
        $this->codPel = $codPel;

        return $this;
    }

    /**
     * Get codPel
     *
     * @return string 
     */
    public function getCodPel()
    {
        return $this->codPel;
    }

    /**
     * Set codStr
     *
     * @param string $codStr
     * @return DemoOf
     */
    public function setCodStr($codStr)
    {
        $this->codStr = $codStr;

        return $this;
    }

    /**
     * Get codStr
     *
     * @return string 
     */
    public function getCodStr()
    {
        return $this->codStr;
    }

    /**
     * Set codUe
     *
     * @param string $codUe
     * @return DemoOf
     */
    public function setCodUe($codUe)
    {
        $this->codUe = $codUe;

        return $this;
    }

    /**
     * Get codUe
     *
     * @return string 
     */
    public function getCodUe()
    {
        return $this->codUe;
    }

    /**
     * Set codVet
     *
     * @param string $codVet
     * @return DemoOf
     */
    public function setCodVet($codVet)
    {
        $this->codVet = $codVet;

        return $this;
    }

    /**
     * Get codVet
     *
     * @return string 
     */
    public function getCodVet()
    {
        return $this->codVet;
    }

    /**
     * Set libElp
     *
     * @param string $libElp
     * @return DemoOf
     */
    public function setLibElp($libElp)
    {
        $this->libElp = $libElp;

        return $this;
    }

    /**
     * Get libElp
     *
     * @return string 
     */
    public function getLibElp()
    {
        return $this->libElp;
    }

    /**
     * Set libNivVet
     *
     * @param string $libNivVet
     * @return DemoOf
     */
    public function setLibNivVet($libNivVet)
    {
        $this->libNivVet = $libNivVet;

        return $this;
    }

    /**
     * Get libNivVet
     *
     * @return string 
     */
    public function getLibNivVet()
    {
        return $this->libNivVet;
    }

    /**
     * Set libStr
     *
     * @param string $libStr
     * @return DemoOf
     */
    public function setLibStr($libStr)
    {
        $this->libStr = $libStr;

        return $this;
    }

    /**
     * Get libStr
     *
     * @return string 
     */
    public function getLibStr()
    {
        return $this->libStr;
    }

    /**
     * Set libWebVet
     *
     * @param string $libWebVet
     * @return DemoOf
     */
    public function setLibWebVet($libWebVet)
    {
        $this->libWebVet = $libWebVet;

        return $this;
    }

    /**
     * Get libWebVet
     *
     * @return string 
     */
    public function getLibWebVet()
    {
        return $this->libWebVet;
    }

    /**
     * Set licStr
     *
     * @param string $licStr
     * @return DemoOf
     */
    public function setLicStr($licStr)
    {
        $this->licStr = $licStr;

        return $this;
    }

    /**
     * Get licStr
     *
     * @return string 
     */
    public function getLicStr()
    {
        return $this->licStr;
    }

    /**
     * Set licUe
     *
     * @param string $licUe
     * @return DemoOf
     */
    public function setLicUe($licUe)
    {
        $this->licUe = $licUe;

        return $this;
    }

    /**
     * Get licUe
     *
     * @return string 
     */
    public function getLicUe()
    {
        return $this->licUe;
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
