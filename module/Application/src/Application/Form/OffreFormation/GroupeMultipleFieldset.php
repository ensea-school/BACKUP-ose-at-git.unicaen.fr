<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
//use Zend\Form\Element\Hidden;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Description of GroupeMultipleFieldset
 *
 * Permet de saisie tous les groupes d'un même élément en même temps
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GroupeMultipleFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, HydratorInterface
{
    use ServiceLocatorAwareTrait;

    /**
     *
     * @return \Application\Service\TypeIntervention[]
     */
    public function getTypesInterventions()
    {
        $sti = $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervention');
        /* @var $sti \Application\Service\TypeIntervention */
        return $sti->getTypesIntervention();
    }

    public function init()
    {
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'groupe-multiple')
                ->setHydrator($this)
                ->setAllowedObjectBindingClass('Application\Entity\Db\ElementPedagogique')
        ;

        foreach( $this->getTypesInterventions() as $typeIntervention ){
            $this->add(array(
                'name'       => $typeIntervention->getCode(),
                'options'    => array(
                    'label' => '<abbr title="'.$typeIntervention->getLibelle().'">'.$typeIntervention->getCode().'</abbr> :',
                    'label_options' => ['disable_html_escape' => true]
                ),
                'attributes' => array(
                    'title' => $typeIntervention->getLibelle(),
                    'class' => 'groupe groupe-nombre input-sm',
                    'step'  => 1,
                    'min'   => 0,
                ),
                'type'       => 'Text',
            ));
        }
    }

    public function getInputFilterSpecification()
    {
        $filters = array();
        foreach( $this->getTypesInterventions() as $typeIntervention ){
            $filters[$typeIntervention->getCode()] = array(
                'required' => false,
                'filters'    => array(
                    ['name' => 'Zend\Filter\StringTrim'],
                    new \Zend\Filter\PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ),
            );
        }
        return $filters;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\ElementPedagogique $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /*$typeVolumeHoraire = $this->getEntityManager()->find('Application\Entity\Db\TypeVolumeHoraire', (int)$data['type-volume-horaire']);
        $periode = $this->getEntityManager()->find('Application\Entity\Db\Periode', (int)$data['periode']);

        $object->setTypeVolumeHoraire($typeVolumeHoraire);
        $object->setPeriode($periode);
        foreach( $this->getTypesInterventions() as $typeIntervention ){
            $object->setTypeIntervention($typeIntervention);
            if (isset($data[$typeIntervention->getCode()])){
                $heures = (float)$data[$typeIntervention->getCode()];
            }else{
                $heures = 0;
            }
            $object->setHeures($heures, false);
        }*/
        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\ElementPedagogique $object
     * @return array
     */
    public function extract($object)
    {
        //$vhl = $object->getChild();
        $data = array(
/*            'type-volume-horaire' => $object->getTypeVolumeHoraire() ? $object->getTypeVolumeHoraire()->getId() : null,
            'service' => $object->getService() ? $object->getService()->getId() : null,
            'periode' => $object->getPeriode() ? $object->getPeriode()->getId() : null,*/
        );
        /*foreach( $this->getTypesInterventions() as $typeIntervention ){
            $vhl->setTypeIntervention($typeIntervention);
            $data[$typeIntervention->getCode()] = $vhl->getHeures();
        }*/
        return $data;
    }
}