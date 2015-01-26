<?php

namespace Application\Service\Indicateur;

/**
 * Description of SaisieServiceApresContratAvenantIndicateur
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface IndicateurImplInterface
{
    /**
     * 
     */
    public function getResult();
    
    /**
     * 
     */
    public function getResultCount();

    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true);
    
    /**
     * 
     */
    public function getResultUrl($result);
}