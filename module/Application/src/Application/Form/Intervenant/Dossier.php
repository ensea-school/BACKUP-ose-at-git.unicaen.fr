<?php

namespace Application\Form\Intervenant;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Description of Dossier
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Dossier extends Form implements ServiceLocatorAwareInterface, InputFilterProviderInterface
{
    use ServiceLocatorAwareTrait;
    
    static public $typesEmployeur = array(
        'privé'  => "Secteur privé",
        'public' => "Secteur public",
    );
    static public $statuts = array(
        'ens_1er' => "Enseignant du 1er degré",
        'ens_2nd' => "Enseignant du 2nd degré",
    );
    
    /**
     * 
     * @param type $name
     * @param type $options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->setHydrator(new DossierHydrator(false));
    }
    
    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->add( array(
            'name' => 'id',
            'type' => 'Hidden'
        ) );

        $this->add( array(
            'name' => 'nomUsuel',
            'options' => array(
                'label' => 'Nom usuel',
            ),
            'type' => 'Text'
        ) );

        $this->add( array(
            'name' => 'nomPatronymique',
            'options' => array(
                'label' => 'Nom patronymique',
            ),
            'type' => 'Text'
        ) );

        $this->add( array(
            'name' => 'prenom',
            'options' => array(
                'label' => 'Prénom',
            ),
            'type' => 'Text'
        ) );

        $this->add( array(
            'name' => 'civilite',
            'options' => array(
                'label' => 'Civilité',
                'value_options' => array(
                    'Mme' => "Mme",
                    'M.'  => "M.",
                ),
                'empty_option' => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Select',
        ) );

        $this->add( array(
            'name' => 'numeroInsee',
            'options' => array(
                'label' => 'Numéro sécu',
            ),
            'attributes' => array(
            ),
            'type' => 'Text',
        ) );

        $this->add( array(
            'name' => 'adresse',
            'options' => array(
                'label' => 'Adresse postale',
            ),
            'attributes' => array(
                'rows' => 5,
            ),
            'type' => 'Textarea',
        ) );

        $this->add( array(
            'name' => 'email',
            'options' => array(
                'label' => 'Adresse mail',
            ),
            'attributes' => array(
            ),
            'type' => 'Text',
        ) );

        $rib = $this->add( array(
            'name' => 'rib',
            'options' => array(
                'label' => 'RIB',
            ),
            'attributes' => array(
            ),
//            'type' => 'UnicaenApp\Form\Element\RIB',
            'type' => 'UnicaenApp\Form\Element\RIBFieldset',
        ) );
        /* @var $rib \UnicaenApp\Form\Element\RIB */

        $this->add( array(
            'name' => 'typeEmployeur',
            'options' => array(
                'label' => "Type d'employeur",
                'value_options' => self::$typesEmployeur,
                'empty_option'  => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Select',
        ) );

        $this->add( array(
            'name' => 'statut',
            'options' => array(
                'label' => "Statut",
                'value_options' => self::$statuts,
                'empty_option'  => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Select',
        ) );
    }
    
    public function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }
        
        // validation globale du RIB
//        $v = \UnicaenApp\Form\Element\RIBFieldset::getDefaultValidator();
//        if (!$v->isValid($this->get('rib')->getValue())) {
//            $this->get('rib')->setMessages(array('hidden' => array('notvalid' => "Le RIB n'est pas valide")));
//            return false;
//        }
        
        return true;
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
            'nomUsuel' => array(
                'required' => true,
            ),
            'nomPatronymique' => array(
                'required' => true,
            ),
            'prenom' => array(
                'required' => true,
            ),
            'civilite' => array(
                'required' => true,
            ),
            'numeroInsee' => array(
                'required' => true,
                'validators' => array(
                    array('name' => 'UnicaenApp\Validator\NumeroINSEE'),
                ),
            ),
            'adresse' => array(
                'required' => true,
            ),
            'email' => array(
                'required' => true,
            ),
            'typeEmployeur' => array(
                'required' => true,
            ),
            'statut' => array(
                'required' => true,
            ),
            'rib' => array(
                'required' => true,
            ),
        );
    }
}