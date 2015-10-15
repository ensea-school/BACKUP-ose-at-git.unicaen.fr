<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use Application\Entity\Db\Traits\SourceAwareTrait;
use Application\Entity\Db\Traits\TypeInterventionAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * VolumeHoraireEns
 */
class VolumeHoraireEns implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use SourceAwareTrait;
    use TypeInterventionAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var float
     */
    protected $heures;

    /**
     * @var string
     */
    protected $sourceCode;



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
     * Set heures
     *
     * @param float $heures
     *
     * @return VolumeHoraireEns
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return VolumeHoraireEns
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }



    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }
}
