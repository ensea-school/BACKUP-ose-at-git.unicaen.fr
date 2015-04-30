<?php

namespace Application\Form\VolumeHoraire;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Hidden;

/**
 * Description of SaisieMultiple
 *
 * Permet de saisie tous les volumes horaires d'une période en même temps
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieMultipleFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     *
     * @return \Application\Entity\Db\TypeIntervention[]
     */
    public function getTypesInterventions()
    {
        return $this->getServiceTypeIntervention()->getList();
    }

    /**
     *
     */
    public function init()
    {
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'volume-horaire-multiple')
                ->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormVolumeHoraireSaisieMultipleHydrator'))
                ->setAllowedObjectBindingClass('Application\Entity\VolumeHoraireListe')
        ;

        foreach( $this->getTypesInterventions() as $typeIntervention ){
            $this->add([
                'name'       => $typeIntervention->getCode(),
                'options'    => [
                    'label' => '<abbr title="'.$typeIntervention->getLibelle().'">'.$typeIntervention->getCode().'</abbr> :',
                    'label_options' => ['disable_html_escape' => true]
                ],
                'attributes' => [
                    'title' => $typeIntervention->getLibelle(),
                    'class' => 'volume-horaire volume-horaire-heures input-sm',
                    'step'  => 'any',
                    'min'   => 0,
                ],
                'type'       => 'Text',
            ]);

        }
        $this->add( new Hidden('type-volume-horaire') );
        $this->add( new Hidden('service') );
        $this->add( new Hidden('periode') );
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = [];
        foreach( $this->getTypesInterventions() as $typeIntervention ){
            $filters[$typeIntervention->getCode()] = [
                'required' => false,
                'filters'    => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    new \Zend\Filter\PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ];
        }
        return $filters;
    }

    /**
     * @return \Application\Service\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
    }
}