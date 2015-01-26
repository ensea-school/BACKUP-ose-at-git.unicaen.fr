<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\VolumeHoraire;
use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\StructureAwareTrait;

/**
 * Classe mère abstraite des règles métier déterminant si un intervenant peut faire l'objet d'une création de contrat initial ou d'avenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class PeutCreerContratAbstractRule extends AbstractRule
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    
    /**
     * @var VolumeHoraire[]
     */
    protected $volumesHorairesDispos;
    
    /**
     * @return VolumeHoraire[]
     */
    public function getVolumesHorairesDispos()
    {
        return $this->volumesHorairesDispos ?: [];
    }
    
    /**
     * @var ServiceValideRule 
     */
    protected $serviceValideRule;
    
    /**
     * 
     * @return ServiceValideRule
     */
    protected function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            $this->serviceValideRule = $this->getServiceLocator()->get('ServiceValideRule');
        }
        $this->serviceValideRule
                ->setMemePartiellement() // une validation partielle des services suffit
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure());
        
        return $this->serviceValideRule;
    }
}
