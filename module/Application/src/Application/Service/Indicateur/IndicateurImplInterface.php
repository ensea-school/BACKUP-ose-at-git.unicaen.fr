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
     */
    public function getTitle();
    
    /**
     * 
     */
    public function getResultUrl($result);
}