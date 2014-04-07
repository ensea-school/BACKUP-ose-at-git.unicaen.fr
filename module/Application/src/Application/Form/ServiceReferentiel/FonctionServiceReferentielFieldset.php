<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Form\Fieldset;
use Zend\Validator\LessThan;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilterProviderInterface;;
use Doctrine\Common\Collections\Collection;
use Application\Entity\Db\ServiceReferentiel;
use Common\Exception\LogicException;

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
    static protected $structuresPossibles;
    
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

        if (!static::$structuresPossibles instanceof Collection) {
            throw new LogicException("Les structures possibles doivent être spécifiées avant l'appel au constructeur.");
        }
        if (!static::$fonctionsPossibles instanceof Collection) {
            throw new LogicException("Les fonctions possibles doivent être spécifiées avant l'appel au constructeur.");
        }
        
        $this->setHydrator(new FonctionServiceReferentielHydrator(static::$structuresPossibles, static::$fonctionsPossibles))
             ->setObject(new ServiceReferentiel());

        $this->setLabel("Fonction référentielle");

        $this->add(array(
            'name'       => 'structure',
            'options'    => array(
                'label' => "Structure :",
            ),
            'attributes' => array(
                'title' => "Structure concernée",
                'class' => 'fonction-referentiel fonction-referentiel-structure input-sm',
            ),
            'type'       => 'Select',
        ));

        $this->add(array(
            'name'       => 'fonction',
            'options'    => array(
                'label' => "Fonction :",
            ),
            'attributes' => array(
                'title' => "Fonction référentielle",
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
                'value' => "0",
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

        // liste déroulante des structures
        $options = array();
        $options[''] = "(Aucune)"; // setEmptyOption() pas utilisé car '' n'est pas compris dans le validateur InArray
        foreach (static::$structuresPossibles as $item) {
            $options[$item->getId()] = "" . $item;
        }
        $this->get('structure')->setValueOptions($options);//->setEmptyOption("(Sélectionnez une structure...)");

        // liste déroulante des fonctions
        $options = array();
        $options[''] = "(Sélectionnez une fonction...)"; // setEmptyOption() pas utilisé car '' n'est pas compris dans le validateur InArray
        foreach (static::$fonctionsPossibles as $item) {
            $options[$item->getId()] = "" . $item;
        }
        $this->get('fonction')->setValueOptions($options);//->setEmptyOption("(Sélectionnez une fonction...)");

        return $this;
    }
    
    /**
     * 
     * @param Collection $collection
     */
    static public function setStructuresPossibles(Collection $collection)
    {
        static::$structuresPossibles = $collection;
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
            'structure' => array(
                'required'   => false,
            ),
            'fonction' => array(
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => "La fonction référentielle est requise",
                            ),
                        ),
                    ),
                ),
            ),
            'heures'   => array(
                'required' => true,
                'filters'    => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    new \Zend\Filter\PregReplace(array('pattern' => '/,/', 'replacement' => '.')),
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

class FonctionServiceReferentielHydrator implements HydratorInterface
{
    /**
     * @var Collection
     */
    protected $structuresPossibles;
    
    /**
     * @var \Application\Entity\Db\FonctionReferentiel[]
     */
    protected $fonctionsPossibles;
    
    /**
     * 
     * @param \Doctrine\Common\Collections\Collection $structuresPossibles
     * @param \Doctrine\Common\Collections\Collection $fonctionsPossibles
     */
    public function __construct(Collection $structuresPossibles, Collection $fonctionsPossibles)
    {
        $this->structuresPossibles = array();
        foreach ($structuresPossibles as $v) {
            $this->structuresPossibles[$v->getId()] = $v;
        }
        
        $this->fonctionsPossibles = array();
        foreach ($fonctionsPossibles as $v) {
            $this->fonctionsPossibles[$v->getId()] = $v;
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
            'structure' => $object->getStructure() ? $object->getStructure()->getId() : null,
            'fonction'  => $object->getFonction()->getId(),
            'heures'    => floatval($object->getHeures()),
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
        $object->setStructure(isset($this->structuresPossibles[$data['structure']]) ? $this->structuresPossibles[$data['structure']] : null)
               ->setFonction($this->fonctionsPossibles[intval($data['fonction'])])
               ->setHeures(floatval($data['heures']));
        
        return $object;
    }
}