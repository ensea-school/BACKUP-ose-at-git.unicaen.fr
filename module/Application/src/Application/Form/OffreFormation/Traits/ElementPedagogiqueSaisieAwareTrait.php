<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementPedagogiqueSaisie;
use Application\Module;
use RuntimeException;

/**
 * Description of ElementPedagogiqueSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueSaisieAwareTrait
{
    /**
     * @var ElementPedagogiqueSaisie
     */
    private $formOffreFormationElementPedagogiqueSaisie;





    /**
     * @param ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSaisie( ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie )
    {
        $this->formOffreFormationElementPedagogiqueSaisie = $formOffreFormationElementPedagogiqueSaisie;
        return $this;
    }



    /**
     * @return ElementPedagogiqueSaisie
     * @throws RuntimeException
     */
    public function getFormOffreFormationElementPedagogiqueSaisie()
    {
        if (empty($this->formOffreFormationElementPedagogiqueSaisie)){
            $serviceLocator = Module::$serviceLocator;
            if (! $serviceLocator) {
                if (!method_exists($this, 'getServiceLocator')) {
                    throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
                }

                $serviceLocator = $this->getServiceLocator();
                if (method_exists($serviceLocator, 'getServiceLocator')) {
                    $serviceLocator = $serviceLocator->getServiceLocator();
                }
            }
            $this->formOffreFormationElementPedagogiqueSaisie = $serviceLocator->getServiceLocator('FormElementManager')->get('ElementPedagogiqueSaisie');
        }
        return $this->formOffreFormationElementPedagogiqueSaisie;
    }
}