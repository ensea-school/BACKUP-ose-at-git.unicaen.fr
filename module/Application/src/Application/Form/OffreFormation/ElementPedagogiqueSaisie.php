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

        $this->add([
            'name'    => 'etape',
            'options' => [
                'label' => 'Formation',
            ],
            'attributes' => [
                'disabled' => true,
            ],
            'type'    => 'Select',
        ]);

        $this->add([
            'name'    => 'source-code',
            'options' => [
                'label' => 'Code',
            ],
            'type'    => 'Text'
        ]);

        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => 'Libellé',
            ],
            'type'    => 'Text'
        ]);

        $this->add([
            'name'    => 'periode',
            'options' => [
                'label'         => 'Période',
            ],
            'type'    => 'Select',
        ]);

        $this->add([
            'name'       => 'taux-foad',
            'options'    => [
                'label' => 'FOAD',
            ],
            'attributes' => [
                'title' => "Formation ouverte à distance",
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'taux-fc',
            'options'    => [
                'label' => 'Taux FC',
            ],
            'attributes' => [
                'title' => "Taux de formation continue",
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'max'   => 1,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'taux-fi',
            'options'    => [
                'label' => 'Taux FI',
            ],
            'attributes' => [
                'title' => "Taux de formation initiale",
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'max'   => 1,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'taux-fa',
            'options'    => [
                'label' => 'Taux FA',
            ],
            'attributes' => [
                'title' => "Taux de formation en apprentissage",
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'max'   => 1,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'    => 'structure',
            'options' => [
                'label' => 'Structure',
            ],
            'attributes' => [
                'disabled' => true,
            ],
            'type'    => 'Select',
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden'
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $localContext = $this->getContextProvider()->getLocalContext();

        // init étape
        if (($etape = $localContext->getEtape())) {
            // si un filtre étape est positionné dans le contexte local, on l'utilise
            $this->get('etape')
                    ->setValueOptions([$id = $etape->getId() => (string) $etape])
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
        $this->get('periode')
                ->setEmptyOption("")
                ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($servicePeriode->getEnseignement()));

        // peuplement liste des structures
        if ($localContext->getStructure()) {
            // si un filtre structure est positionné dans le contexte local, on l'utilise
            $this->get('structure')
                    ->setValueOptions([$id = $localContext->getStructure()->getId() => (string) $localContext->getStructure()])
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
        return [
            'taux-foad' => [
                'required' => true,
//                'validators' => array(
//                    array('name' => 'Float'),
//                ),
            ],
            'taux-fc' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    new \Zend\Filter\PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ],
            'taux-fi' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    new \Zend\Filter\PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ],
            'taux-fa' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    new \Zend\Filter\PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ],
            'source-code' => [
                'required' => true,
            ],
            'libelle' => [
                'required' => true,
            ],
            'periode' => [
                'required' => false,
            ],
            'etape' => [
                'required' => false,
            ],
            'structure' => [
                'required' => false,
            ],
        ];
    }
}