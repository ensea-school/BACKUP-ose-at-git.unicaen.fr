<?php

namespace Plafond\Entity\Db;


/**
 * Description of PlafondPerimetreAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondPerimetreAwareTrait
{
    protected ?PlafondPerimetre $plafondPerimetre = null;



    /**
     * @param PlafondPerimetre $plafondPerimetre
     *
     * @return self
     */
    public function setPlafondPerimetre( PlafondPerimetre $plafondPerimetre )
    {
        $this->plafondPerimetre = $plafondPerimetre;

        return $this;
    }



    public function getPlafondPerimetre(): ?PlafondPerimetre
    {
        return $this->plafondPerimetre;
    }
}