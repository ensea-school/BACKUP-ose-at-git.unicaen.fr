<?php

namespace OffreFormation\Entity\Db;

use Application\Entity\Db\Traits\SourceAwareTrait;
use OffreFormation\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;


/**
 * ElementPedagogique
 */
class ElementTauxRegimes implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use ElementPedagogiqueAwareTrait;


    /**
     * @var integer
     */
    protected $id;

    /**
     * FI
     *
     * @var float
     */
    protected $tauxFi;

    /**
     * FC
     *
     * @var float
     */
    protected $tauxFc;

    /**
     * FA
     *
     * @var float
     */
    protected $tauxFa;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return float
     */
    public function getTauxFi()
    {
        return $this->tauxFi;
    }



    /**
     * @param float $tauxFi
     *
     * @return ElementPedagogique
     */
    public function setTauxFi($tauxFi)
    {
        $this->tauxFi = $tauxFi;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxFc()
    {
        return $this->tauxFc;
    }



    /**
     * @param float $tauxFc
     *
     * @return ElementPedagogique
     */
    public function setTauxFc($tauxFc)
    {
        $this->tauxFc = $tauxFc;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxFa()
    {
        return $this->tauxFa;
    }



    /**
     * @param float $tauxFa
     *
     * @return ElementPedagogique
     */
    public function setTauxFa($tauxFa)
    {
        $this->tauxFa = $tauxFa;

        return $this;
    }



}