<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Form\Fieldset;
use Zend\Validator\LessThan;
use Doctrine\Common\Collections\Collection;
use Zend\InputFilter\InputFilterProviderInterface;
use Application\Entity\Db\ServiceReferentiel;

/**
 * Description of FonctionServiceReferentiel
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class FonctionServiceReferentielFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * @var Collection
     */
    static protected $fonctionsPossibles;
    
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();

        $this->setHydrator(new ServiceReferentielHydrator(static::$fonctionsPossibles))
             ->setObject(new \Application\Entity\Db\ServiceReferentiel());

        $this->setLabel("Fonction référentiel");

        $this->add(array(
            'name'       => 'fonction',
            'options'    => array(
                'label' => "Fonction :",
            ),
            'attributes' => array(
                'title' => "Fonction référentiel",
                'class' => 'fonction-referentiel fonction-referentiel-fonction input-sm',
            ),
            'type'       => 'Select',
        ));

        $this->add(array(
            'name'       => 'heures',
            'options'    => array(
                'label' => "Nombre d'heures :",
            ),
            'attributes' => array(
                'title' => "Nombre d'heures",
                'class' => 'fonction-referentiel fonction-referentiel-heures input-sm'
            ),
            'type'       => 'Text',
        ));

        $this->add(array(
            'name'       => 'remove',
            'options'    => array(
                'label' => "Supprimer",
            ),
            'attributes' => array(
                'title' => "Supprimer cette fonction",
                'class' => 'fonction-referentiel fonction-referentiel-supprimer btn btn-default btn-xs'
            ),
            'type'       => 'Button',
        ));

        $options = array();
        foreach (static::$fonctionsPossibles as $item) {
            $options[$item->getId()] = "" . $item;
        }

        $this->get('fonction')
                ->setEmptyOption("(Sélectionnez une fonction...)")
                ->setValueOptions($options);

        return $this;
    }
    
    /**
     * 
     * @param Collection $collection
     */
    static public function setFonctionsPossibles(Collection $collection)
    {
        static::$fonctionsPossibles = $collection;
    }

    /**
     * 
     * @return \Zend\Validator\LessThan|null
     */
    protected function getValidatorHeures()
    {
        // recherche de la FonctionReferentiel sélectionnée pour plafonner le nombre d'heures
        $v  = null;
        $id = $this->get('fonction')->getValue();
        $p  = function($item) use ($id) {
            return $id == $item->getId();
        };
        $fonction = static::$fonctionsPossibles->filter($p)->first(); /* @var $fonction FonctionReferentiel */
        if ($fonction) {
            $v = new LessThan(array('max' => $max = (float)$fonction->getPlafond(), 'inclusive' => true));
            $v->setMessages(array(LessThan::NOT_LESS_INCLUSIVE => "Le plafond pour cette fonction est de $max"));
        }
        
        return $v;
    }
    
    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $specs = array(
            'fonction' => array(
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => "La fonction référentiel est requise",
                            ),
                        ),
                    ),
                ),
            ),
            'heures'   => array(
                'required' => true,
                'filters'    => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => "Le nombre d'heures est requis",
                            ),
                        ),
                    ),
                ),
            ),
        );
        
        if (($validator = $this->getValidatorHeures())) {
            $specs['heures']['validators'][] = $validator;
        }
        
        return $specs;
    }
}

class ServiceReferentielHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{
    /**
     * @var \Application\Entity\Db\FonctionReferentiel[]
     */
    protected $fonctionsPossibles;
    
    /**
     * 
     * @param \Doctrine\Common\Collections\Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->fonctionsPossibles = array();
        foreach ($collection as $f) {
            $this->fonctionsPossibles[$f->getId()] = $f;
        }
    }
    
    /**
     * Extract values from an object
     *
     * @param  ServiceReferentiel $object
     * @return array
     */
    public function extract($object)
    {
        return array(
            'fonction' => $object->getFonction()->getId(),
            'heures'   => $object->getHeures(),
        );
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  ServiceReferentiel $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setFonction($this->fonctionsPossibles[$data['fonction']])
               ->setHeures((float) $data['heures']);
        
        return $object;
    }
}