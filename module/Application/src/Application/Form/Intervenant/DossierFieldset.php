<?php

namespace Application\Form\Intervenant;

use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of DossierFieldset
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierFieldset extends Fieldset implements ServiceLocatorAwareInterface, ContextProviderAwareInterface, InputFilterProviderInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    
    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $anneePrec = $this->getContextProvider()->getGlobalContext()->getAnneePrecedente();
        
        $this
                ->setObject(new \Application\Entity\Db\Dossier())
                ->setHydrator(new DossierFieldsetHydrator(false));
        
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
                'label' => 'Nom de naissance',
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

        $civilite = new CiviliteFieldset('civilite');
        $civilite
                ->setCivilites($this->getCivilites())
                ->init();
        $this->add($civilite);

        $this->add( array(
            'name' => 'numeroInsee',
            'options' => array(
                'label' => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> (clé incluse)',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0,
                'label_options' => array(
                    'disable_html_escape' => true
                ),
            ),
            'attributes' => array(
                'title' => "Numéro INSEE (sécurité sociale) avec la clé de contrôle",
            ),
            'type' => 'Text',
        ) );

        $this->add( array(
            'name' => 'numeroInseeEstProvisoire',
            'options' => array(
                'label' => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> provisoire',
                'label_options' => array(
                    'disable_html_escape' => true
                ),
            ),
            'attributes' => array(
            ),
            'type' => 'Checkbox',
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
                'readonly' => true
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
            'name' => 'premierRecrutement',
            'options' => array(
//                'label' => "S'agit-il de votre 1er recrutement en qualité de vacataire à l'Université de Caen ?",
//                'value_options' => array('0' => "Non", '1' => "Oui"),
                'value_options' => array('0' => "Oui", '1' => "Non"), // ATTENTION! La logique de la question a changé !
                'label' => "Avez-vous enseigné à l'Université de Caen depuis le 01/09/2012 ?",
                'empty_option'  => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Radio',
        ) );

        $this->add( array(
            'name' => 'perteEmploi',
            'options' => array(
                'label' => "Avez-vous perdu votre emploi en $anneePrec ?",
                'value_options' => array('1' => "Oui", '0' => "Non"),
                'empty_option'  => "(Sélectionnez...)",
            ),
            'attributes' => array(
            ),
            'type' => 'Radio',
        ) );
        
        $statut = new StatutFieldset('statut');
        $statut
                ->setStatuts($this->getStatutsIntervenant())
                ->init();
        $this->add($statut);
    }
    
    /**
     * 
     * @return \Application\Entity\Db\StatutIntervenant[] id => StatutIntervenant
     */
    private function getStatutsIntervenant() 
    {
        $serviceStatut   = $this->getServiceLocator()->getServiceLocator()->get('applicationStatutIntervenant');
        
        $qb = $serviceStatut->finderByVacatairesNonChargeEns1An();
        $serviceStatut->finderByVacatairesNonBiatss($qb);
        $serviceStatut->finderByNonAutorise(false, $qb);
        $serviceStatut->finderByPeutSaisirService(true, $qb);
        
        $statuts = $serviceStatut->getList($qb);

        return $statuts;
    }
    
    /**
     * 
     * @return \Application\Entity\Db\Civilite[] id => Civilite
     */
    private function getCivilites()
    {
        $serviceCivilite = $this->getServiceLocator()->getServiceLocator()->get('applicationCivilite');
        
        $civilites = $serviceCivilite->getList();

        return $civilites;
    }
    
    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $numeroInseeProvisoire = (bool) $this->get('numeroInseeEstProvisoire')->getValue();
        $premierRecrutement    = $this->get('premierRecrutement')->getValue();
        $perteEmploi           = $this->get('perteEmploi')->getValue();

        // la réponse à la question "perte d'emploi" n'est (visible et donc) obligatoire que 
        // si la réponse à la question "1er recrutement" est NON
        $perteEmploiRequired = ('0' === $premierRecrutement);
        
        $spec = array(
            'nomUsuel' => array(
                'required' => true,
            ),
            'nomPatronymique' => array(
                'required' => true,
            ),
            'prenom' => array(
                'required' => true,
            ),
            'numeroInsee' => array(
                'required' => true,
                'validators' => array(
                    array('name' => 'UnicaenApp\Validator\NumeroINSEE', 'options' => array('provisoire' => $numeroInseeProvisoire)),
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
            'premierRecrutement' => array(
                'required' => true,
            ),
            'perteEmploi' => array(
                'required' => true,
                'allow_empty' => true,
            ),
        );
        
        $statutNotRequired = !('1' === $premierRecrutement || '0' === $perteEmploi);
        if ($statutNotRequired) {
            $spec['statut'] = array('required' => false); // ainsi l'input filter du fieldset n'est pas pris en compte
        }
        
        return $spec;
    }
}

class CiviliteFieldset extends \Zend\Form\Fieldset implements InputFilterProviderInterface
{
    public function init()
    {
        $this
                ->setObject(new \Application\Entity\Db\Civilite())
                ->setHydrator(new \Application\Entity\Db\Hydrator\CiviliteHydrator($this->getCivilites()));
        
        $this->add(array(
            'name' => 'id',
            'options' => array(
                'label' => 'Civilité',
                'value_options' => \UnicaenApp\Util::collectionAsOptions($this->getCivilites()),
                'empty_option' => "(Sélectionnez...)",
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
            'id' => array(
                'required' => true,
            ),
        );
    }
    
    private $civilites;
    
    public function getCivilites()
    {
        return $this->civilites;
    }

    public function setCivilites($civilites)
    {
        $this->civilites = $civilites;
        return $this;
    }
}

class StatutFieldset extends \Zend\Form\Fieldset implements InputFilterProviderInterface
{
    public function init()
    {
        $this
                ->setObject(new \Application\Entity\Db\StatutIntervenant())
                ->setHydrator(new \Application\Entity\Db\Hydrator\StatutIntervenantHydrator($this->getStatuts()));
        
        $this->add(array(
            'name' => 'id',
            'options' => array(
                'label' => "Quel est votre statut ?",
                'value_options' => \UnicaenApp\Util::collectionAsOptions($this->getStatuts()),
                'empty_option'  => "(Sélectionnez...)",
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
            'id' => array(
                'required' => true,
            ),
        );
    }
    
    private $statuts;
    
    public function getStatuts()
    {
        return $this->statuts;
    }

    public function setStatuts($statuts)
    {
        $this->statuts = $statuts;
        return $this;
    }
}