<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\IndicModifDossier;

/**
 * Description of IndicModifDossierAwareInterface
 *
 * @author UnicaenCode
 */
interface IndicModifDossierAwareInterface
{
    /**
     * @param IndicModifDossier $indicModifDossier
     * @return self
     */
    public function setIndicModifDossier( IndicModifDossier $indicModifDossier = null );



    /**
     * @return IndicModifDossier
     */
    public function getIndicModifDossier();
}