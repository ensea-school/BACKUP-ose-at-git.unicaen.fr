<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;

/**
 * Description of EtapeSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function init()
    {
        /* Définition de l'hydrateur */
        $hydrator = new EtapeSaisieHydrator;
        $hydrator->setServiceLocator($this->getServiceLocator()->getServiceLocator());
        $this->setHydrator($hydrator);

        /* construction du formulaire */
        $this->add( array(
            'name' => 'source-code',
            'options' => array(
                'label' => 'Code',
            ),
            'type' => 'Text'
        ) );

        $this->add( array(
            'name' => 'libelle',
            'options' => array(
                'label' => 'Libellé',
            ),
            'type' => 'Text'
        ) );

        $this->add( array(
            'name' => 'type-formation',
            'options' => array(
                'label' => 'Type de formation',
            ),
            'type' => 'Select',
        ) );
        $serviceTypeFormation = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeFormation');
        $this->get('type-formation')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $serviceTypeFormation->getList() ) );

        $this->add( array(
            'name' => 'niveau',
            'options' => array(
                'label' => 'Niveau',
            ),
            'validators' => array(
                'integer',
            ),
            'type' => 'Text',
        ) );

        $this->add( array(
            'name' => 'specifique-echanges',
            'options' => array(
                'label' => 'Spécifique aux échanges',
            ),
            'type' => 'Checkbox',
        ) );

        $this->add( array(
            'name' => 'structure',
            'options' => array(
                'label' => 'Structure',
            ),
            'type' => 'Select',
        ) );
        $serviceStructure = $this->getServiceLocator()->getServiceLocator()->get('ApplicationStructure');
        $qb = $serviceStructure->finderByEnseignement( $serviceStructure->finderByNiveau(2) );
        $this->get('structure')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $serviceStructure->getList($qb) ) );

        $this->add( array(
            'name' => 'id',
            'type' => 'Hidden'
        ) );

        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));
    }


    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification(){
        return array(
        );
    }
}