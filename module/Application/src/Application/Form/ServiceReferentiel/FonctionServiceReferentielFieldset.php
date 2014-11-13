<?php

namespace Application\Form\ServiceReferentiel;;

use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Structure;
use Common\Exception\LogicException;
use Doctrine\Common\Collections\Collection;
use Zend\Filter\PregReplace;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Validator\Callback;
use Zend\Validator\LessThan;
use Zend\Validator\NotEmpty;

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
             ->setObject(new ServiceReferentiel())
             ->setAttribute('class', "fonction-referentiel");

//        $this->setLabel("Fonction référentielle");

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
                'label' => "Nb d'heures :",
            ),
            'attributes' => array(
                'value' => "0",
                'title' => "Nombre d'heures",
                'class' => 'fonction-referentiel fonction-referentiel-heures input-sm'
            ),
            'type'       => 'Text',
        ));

        $this->add(array(
            'name'       => 'commentaires',
            'options'    => array(
                'label' => "Commentaires :",
            ),
            'attributes' => array(
                'title' => "Commentaires éventuels",
                'class' => 'fonction-referentiel fonction-referentiel-commentaires input-sm'
            ),
            'type'       => 'Textarea',
        ));

        $this->add(array(
            'name'       => 'remove',
            'options'    => array(
                'label' => "<span class=\"glyphicon glyphicon-minus\"></span> Supprimer",
                'label_options' => array(
                    'disable_html_escape' => true,
                ),
            ),
            'attributes' => array(
                'title' => "Supprimer cette fonction",
                'class' => 'fonction-referentiel fonction-referentiel-supprimer btn btn-default btn-xs'
            ),
            'type'       => 'Button',
        ));

        // liste déroulante des structures
        $options = array();
        $options[''] = "(Sélectionnez une structure...)"; // setEmptyOption() pas utilisé car '' n'est pas compris dans le validateur InArray
        foreach (static::$structuresPossibles as $item) {
            $options[$item->getId()] = "" . $item;
        }
        asort($options);
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
     * @return Callback|null
     */
    protected function getValidatorStructure()
    {
        // recherche de la FonctionReferentiel sélectionnée pour connaître la structure associée éventuelle
        $fonctionSaisie = $this->getFonctionPossible($this->get('fonction')->getValue());
        if (!$fonctionSaisie) {
            return null;
        }
        
        // recherche de la Structure sélectionnée
        $structureSaisie = $this->getStructurePossible($this->get('structure')->getValue());
        if (!$structureSaisie) {
            return null;
        }
        
        // structure éventuelle associée à la fonction
        $structureFonction = $fonctionSaisie->getStructure();
                
        // si aucune structure n'est associée à la fonction, on vérifie simplement que la structure sélectionnée est de niveau 2
        if (!$structureFonction) {
            $callback = function() use ($structureSaisie) { return $structureSaisie->getNiveau() === 2; };
            $message  = "Composante d'enseignement requise";
        }
        // si une structure est associée à la fonction, la structure sélectionnée soit être celle-là
        else {
            $callback = function() use ($structureSaisie, $structureFonction) { return $structureSaisie === $structureFonction; };
            $message  = sprintf("Structure obligatoire : '%s'", $structureFonction);
        }
            
        $v = new Callback($callback);
        $v->setMessages(array(Callback::INVALID_VALUE => $message));
        
        return $v;
    }
    
    /**
     * 
     * @return LessThan|null
     */
    protected function getValidatorHeures()
    {
        // recherche de la FonctionReferentiel sélectionnée pour plafonner le nombre d'heures
        $v        = null;
        $id       = $this->get('fonction')->getValue();
        $fonction = $this->getFonctionPossible($id);
        
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
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => "La structure est requise",
                            ),
                        ),
                    ),
                ),
            ),
            'fonction' => array(
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => "La fonction référentielle est requise",
                            ),
                        ),
                    ),
                ),
            ),
            'heures'   => array(
                'required' => true,
                'filters'    => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                    new PregReplace(array('pattern' => '/,/', 'replacement' => '.')),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\NotEmpty',
                        'options' => array(
                            'string',
                            'integer',
                            'zero',
                            'messages' => array(
                                NotEmpty::IS_EMPTY => "Un nombre d'heures non nul est requis",
                            ),
                        ),
                    ),
                ),
            ),
            'commentaires' => array(
                'required' => false,
                'filters'    => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
        );
        
        if (($validator = $this->getValidatorStructure())) {
            $specs['structure']['validators'][] = $validator;
        }
        
        if (($validator = $this->getValidatorHeures())) {
            $specs['heures']['validators'][] = $validator;
        }
        
        return $specs;
    }

    /**
     * Recherche une structure par son id dans la liste des structures possibles.
     * 
     * @param integer $id
     * @return Structure
     */
    private function getStructurePossible($id)
    {
        foreach (static::$structuresPossibles as $s) { /* @var $s Structure */
            if (intval($id) === $s->getId()) {
                return $s;
            }
        }
        
        return null;
    }

    /**
     * Recherche une fonction par son id dans la liste des fonctions possibles.
     * 
     * @param integer $id
     * @return FonctionReferentiel
     */
    private function getFonctionPossible($id)
    {
        foreach (static::$fonctionsPossibles as $fonction) { /* @var $fonction FonctionReferentiel */
            if (intval($id) === $fonction->getId()) {
                return $fonction;
            }
        }
        
        return null;
    }
}

class FonctionServiceReferentielHydrator implements HydratorInterface
{
    /**
     * @var Collection
     */
    protected $structuresPossibles;
    
    /**
     * @var FonctionReferentiel[]
     */
    protected $fonctionsPossibles;
    
    /**
     * 
     * @param Collection $structuresPossibles
     * @param Collection $fonctionsPossibles
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
            'structure'    => $object->getStructure() ? $object->getStructure()->getId() : null,
            'fonction'     => $object->getFonction()->getId(),
            'heures'       => floatval($object->getHeures()),
            'commentaires' => $object->getCommentaires(),
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
               ->setHeures(floatval($data['heures']))
               ->setCommentaires(isset($data['commentaires']) ? $data['commentaires'] : null);
        
        return $object;
    }
}