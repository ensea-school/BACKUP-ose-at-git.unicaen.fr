<?php

namespace Plafond\Entity\Db;

/**
 * PlafondPerimetre
 */
class PlafondPerimetre
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return PlafondPerimetre
     */
    public function setCode($code): PlafondPerimetre
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return PlafondPerimetre
     */
    public function setLibelle($libelle): PlafondPerimetre
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
