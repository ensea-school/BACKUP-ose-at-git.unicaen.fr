<?php

namespace Application\Form\Intervenant;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of Dossier
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Dossier extends Form implements ServiceLocatorAwareInterface, ContextProviderAwareInterface, InputFilterProviderInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    
    static public $typesEmployeur = array(
        'salarie' => array(
            'label' => "Salarié",
            'options' => array(
                'salarie_privé'        => "Salarié secteur privé",
                'salarie_public_ucbn'  => "Salarié secteur public UCBN",
                'salarie_public_autre' => "Salarié secteur public hors UCBN",
            ),
        ),
        'non_salarie' => "Non salarié",
        'retraite' => array(
            'label' => "Retraité",
            'options' => array(
                'retraite_privé'  => "Retraité UCBN",
                'retraite_public' => "Retraité hors UCBN",
            ),
        ),
        'etudiant' => array(
            'label' => "Étudiant",
            'options' => array(
                'etudiant_privé'  => "Étudiant UCBN",
                'etudiant_public' => "Étudiant Hors UCBN",
            ),
        ),
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
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        
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
                'label' => 'Numéro sécu (clé incluse)',
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

        $this->add( array(
            'name' => 'telephone',
            'options' => array(
                'label' => 'Téléphone',
            ),
            'attributes' => array(
                'size' => 13,
            ),
            'type' => 'Text',
        ) );

        $this->add( array(
            'name' => 'rib',
            'options' => array(
                'label' => 'RIB',
            ),
            'attributes' => array(
            ),
            'type' => 'UnicaenApp\Form\Element\RIBFieldset',
        ) );

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
            'name' => 'premierRecrutement',
            'options' => array(
                'label' => "S'agit-il d'un premier recrutement à l'Université de Caen ?",
                'value_options' => array('1' => "Oui", '0' => "Non"),
                'empty_option'  => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Radio',
        ) );

        $this->add( array(
            'name' => 'perteEmploi',
            'options' => array(
                'label' => "Avez-vous perdu votre emploi en $annee ?",
                'value_options' => array('1' => "Oui", '0' => "Non"),
                'empty_option'  => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Radio',
        ) );

        $this->add( array(
            'name' => 'statut',
            'options' => array(
                'label' => "Quel est votre statut ?",
                'value_options' => self::$statuts,
                'empty_option'  => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Select',
        ) );
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
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'EmailAddress')
                ),
            ),
            'telephone' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StringToUpper'),
                ),
                'validators' => array(
//                    new \Zend\I18n\Validator\PhoneNumber(), // les formats de numéros ne tolèrent pas le 0 de tête!!
                ),
            ),
            'typeEmployeur' => array(
                'required' => true,
            ),
            'statut' => array(
                'required' => true,
            ),
            'premierRecrutement' => array(
                'required' => true,
            ),
            'perteEmploi' => array(
                'required' => true,
            ),
        );
    }
}