<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\VolumeHoraire;
use Application\Rule\AbstractRule;
use Application\Service\Initializer\VolumeHoraireServiceAwareTrait;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\StructureAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Classe mère abstraite des règles métier déterminant si un intervenant peut faire l'objet d'une création de contrat initial ou d'avenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class PeutCreerContratAbstractRule extends AbstractRule implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    
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
    
    protected $serviceValideRule;
    
    /**
     * 
     * @return ServiceValideRule
     */
    protected function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            $this->serviceValideRule = new ServiceValideRule();
        }
        $this->serviceValideRule
                ->setMemePartiellement() // une validation partielle des services suffit
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure())
                ->setTypeValidation($this->getTypeValidationEns())
                ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        
        return $this->serviceValideRule;
    }
    
    /**
     * 
     * @return TypeValidation
     */
    private function getTypeValidationEns()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation')->getRepo()
                ->findOneByCode(TypeValidation::CODE_SERVICES_PAR_COMP);
    }
}
