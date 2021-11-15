<?php

namespace Plafond\Entity\Db;

/**
 * Description of PlafondPerimetreAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondPerimetreAwareTrait
{
    /**
     * @var PlafondPerimetre
     */
    protected $plafondPerimetre;



    /**
     * @param PlafondPerimetre $plafondPerimetre
     *
     * @return self
     */
    public function setPlafondPerimetre(PlafondPerimetre $plafondPerimetre)
    {
        $this->plafondPerimetre = $plafondPerimetre;

        return $this;
    }



    /**
     * @return PlafondPerimetre
     */
    public function getPlafondPerimetre(): ?PlafondPerimetre
    {
        return $this->plafondPerimetre;
    }
}