<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\IndicModifDossier;

/**
 * Description of IndicModifDossierAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicModifDossierAwareTrait
{
    /**
     * @var IndicModifDossier
     */
    private $indicModifDossier;





    /**
     * @param IndicModifDossier $indicModifDossier
     * @return self
     */
    public function setIndicModifDossier( IndicModifDossier $indicModifDossier = null )
    {
        $this->indicModifDossier = $indicModifDossier;
        return $this;
    }



    /**
     * @return IndicModifDossier
     */
    public function getIndicModifDossier()
    {
        return $this->indicModifDossier;
    }
}