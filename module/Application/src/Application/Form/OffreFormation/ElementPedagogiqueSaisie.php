<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of ElementPedagogiqueSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueSaisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    
    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        /* Définition de l'hydrateur */
        $hydrator = new ElementPedagogiqueSaisieHydrator();
        $hydrator->setServiceLocator($this->getServiceLocator()->getServiceLocator());
        $this->setHydrator($hydrator);

        $this->add(array(
            'name'    => 'etape',
            'options' => array(
                'label' => 'Formation',
            ),
            'attributes' => array(
                'disabled' => true,
            ),
            'type'    => 'Select',
        ));
        
        $this->add(array(
            'name'    => 'source-code',
            'options' => array(
                'label' => 'Code',
            ),
            'type'    => 'Text'
        ));

        $this->add(array(
            'name'    => 'libelle',
            'options' => array(
                'label' => 'Libellé',
            ),
            'type'    => 'Text'
        ));

        $this->add(array(
            'name'    => 'periode',
            'options' => array(
                'label'         => 'Période',
            ),
            'type'    => 'Select',
        ));

        $this->add(array(
            'name'       => 'taux-foad',
            'options'    => array(
                'label' => 'FOAD',
            ),
            'attributes' => array(
                'title' => "Formation ouverte à distance",
            ),
            'type'       => 'Checkbox',
        ));

        $this->add(array(
            'name'    => 'structure',
            'options' => array(
                'label' => 'Structure',
            ),
            'attributes' => array(
                'disabled' => true,
            ),
            'type'    => 'Select',
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));

        $this->add(array(
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));
        
        $localContext = $this->getContextProvider()->getLocalContext();
        
        // init étape
        if (($etape = $localContext->getEtape())) {
            // si un filtre étape est positionné dans le contexte local, on l'utilise
            $this->get('etape')
                    ->setValueOptions(array($id = $etape->getId() => (string) $etape))
                    ->setValue($id);
        }
        else {
            $serviceEtape = $this->getServiceLocator()->getServiceLocator()->get('ApplicationEtape');
            $this->get('etape')
                    ->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $serviceEtape->getList() ) )
                    ->setAttribute('disabled', false);
        }
        
        // peuplement liste des périodes
        $servicePeriode = $this->getServiceLocator()->getServiceLocator()->get('ApplicationPeriode');
        $this->get('periode')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($servicePeriode->getList()));
        
        // peuplement liste des structures
        if ($localContext->getStructure()) {
            // si un filtre structure est positionné dans le contexte local, on l'utilise
            $this->get('structure')
                    ->setValueOptions(array($id = $localContext->getStructure()->getId() => (string) $localContext->getStructure()))
                    ->setValue($id);
        }
        else {
            $serviceStructure = $this->getServiceLocator()->getServiceLocator()->get('ApplicationStructure');
            $qb = $serviceStructure->finderByEnseignement( $serviceStructure->finderByNiveau(2) );
            $this->get('structure')
                    ->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $serviceStructure->getList($qb) ) )
                    ->setAttribute('disabled', false);
        }
    }
    
    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'taux-foad' => array(
                'required' => true,
//                'validators' => array(
//                    array('name' => 'Float'),
//                ),
            ),
            'source-code' => array(
                'required' => true,
            ),
            'libelle' => array(
                'required' => true,
            ),
            'periode' => array(
                'required' => true,
            ),
            'etape' => array(
                'required' => false,
            ),
            'structure' => array(
                'required' => false,
            ),
        );
    }
}