<?php

namespace Application\Form\Intervenant;

use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Description of DossierFieldset
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierFieldset extends Fieldset implements ServiceLocatorAwareInterface, InputFilterProviderInterface
{
    use ServiceLocatorAwareTrait;
    use \Application\Service\Traits\ContextAwareTrait;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $anneePrec = $this->getServiceContext()->getAnneePrecedente();

        $this
                ->setObject(new \Application\Entity\Db\Dossier())
                ->setHydrator(new DossierFieldsetHydrator(false));

        $this->add( [
            'name' => 'id',
            'type' => 'Hidden'
        ] );

        $this->add( [
            'name' => 'nomUsuel',
            'options' => [
                'label' => 'Nom usuel',
            ],
            'type' => 'Text'
        ] );

        $this->add( [
            'name' => 'nomPatronymique',
            'options' => [
                'label' => 'Nom de naissance',
            ],
            'type' => 'Text'
        ] );

        $this->add( [
            'name' => 'prenom',
            'options' => [
                'label' => 'Prénom',
            ],
            'type' => 'Text'
        ] );

        $civilite = new CiviliteFieldset('civilite');
        $civilite
                ->setCivilites($this->getCivilites())
                ->init();
        $this->add($civilite);

        $this->add( [
            'name' => 'numeroInsee',
            'options' => [
                'label' => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> (clé incluse)',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0,
                'label_options' => [
                    'disable_html_escape' => true
                ],
            ],
            'attributes' => [
                'title' => "Numéro INSEE (sécurité sociale) avec la clé de contrôle",
            ],
            'type' => 'Text',
        ] );

        $this->add( [
            'name' => 'numeroInseeEstProvisoire',
            'options' => [
                'label' => 'Numéro <abbr title="Numéro de sécurité sociale">INSEE</abbr> provisoire',
                'label_options' => [
                    'disable_html_escape' => true
                ],
            ],
            'attributes' => [
            ],
            'type' => 'Checkbox',
        ] );

        $this->add( [
            'name' => 'adresse',
            'options' => [
                'label' => 'Adresse postale',
            ],
            'attributes' => [
                'rows' => 5,
            ],
            'type' => 'Textarea',
        ] );

        $this->add( [
            'name' => 'email',
            'options' => [
                'label' => 'Adresse mail',
            ],
            'attributes' => [
                'readonly' => true
            ],
            'type' => 'Text',
        ] );

        $this->add( [
            'name' => 'telephone',
            'options' => [
                'label' => 'Téléphone',
            ],
            'attributes' => [
                'size' => 13,
            ],
            'type' => 'Text',
        ] );

        $this->add( [
            'name' => 'rib',
            'options' => [
                'label' => 'RIB',
            ],
            'attributes' => [
            ],
            'type' => 'UnicaenApp\Form\Element\RIBFieldset',
        ] );

        $this->add( [
            'name' => 'premierRecrutement',
            'options' => [
//                'label' => "S'agit-il de votre 1er recrutement en qualité de vacataire à l'Université de Caen ?",
//                'value_options' => array('0' => "Non", '1' => "Oui"),
                'value_options' => ['0' => "Oui", '1' => "Non"], // ATTENTION! La logique de la question a changé !
                'label' => "Avez-vous enseigné en tant que vacataire à l'Université de Caen depuis le 01/09/2012 ?",
                'empty_option'  => "(Sélectionnez...)",
            ],
            'attributes' => [
            ],
            'type' => 'Radio',
        ] );

        $this->add( [
            'name' => 'perteEmploi',
            'options' => [
                'label' => "Avez-vous perdu votre emploi en $anneePrec ?",
                'value_options' => ['1' => "Oui", '0' => "Non"],
                'empty_option'  => "(Sélectionnez...)",
            ],
            'attributes' => [
            ],
            'type' => 'Radio',
        ] );

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
        /* @var $serviceStatut \Application\Service\StatutIntervenant */

        return $serviceStatut->getList(
                    $serviceStatut->finderByPeutChoisirDansDossier(true)
               );
    }

    /**
     *
     * @return \Application\Entity\Db\Civilite[] id => Civilite
     */
    private function getCivilites()
    {
        $serviceCivilite = $this->getServiceLocator()->getServiceLocator()->get('applicationCivilite');
        /* @var $serviceCivilite \Application\Service\Civilite */

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

        $spec = [
            'nomUsuel' => [
                'required' => true,
            ],
            'nomPatronymique' => [
                'required' => true,
            ],
            'prenom' => [
                'required' => true,
            ],
            'numeroInsee' => [
                'required' => true,
                'validators' => [
                    ['name' => 'UnicaenApp\Validator\NumeroINSEE', 'options' => ['provisoire' => $numeroInseeProvisoire]],
                ],
            ],
            'adresse' => [
                'required' => true,
            ],
            'email' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress']
                ],
            ],
            'telephone' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToUpper'],
                ],
                'validators' => [
//                    new \Zend\I18n\Validator\PhoneNumber(), // les formats de numéros ne tolèrent pas le 0 de tête!!
                ],
            ],
            'premierRecrutement' => [
                'required' => true,
            ],
            'perteEmploi' => [
                'required' => true,
                'allow_empty' => true,
            ],
        ];

        $statutNotRequired = !('1' === $premierRecrutement || '0' === $perteEmploi);
        if ($statutNotRequired) {
            $spec['statut'] = ['required' => false]; // ainsi l'input filter du fieldset n'est pas pris en compte
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

        $this->add([
            'name' => 'id',
            'options' => [
                'label' => 'Civilité',
                'value_options' => \UnicaenApp\Util::collectionAsOptions($this->getCivilites()),
                'empty_option' => "(Sélectionnez...)",
            ],
            'type' => 'Select',
        ] );
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
            'id' => [
                'required' => true,
            ],
        ];
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

        $this->add([
            'name' => 'id',
            'options' => [
                'label' => "Quel est votre statut ?",
                'value_options' => \UnicaenApp\Util::collectionAsOptions($this->getStatuts()),
                'empty_option'  => "(Sélectionnez...)",
            ],
            'type' => 'Select',
        ] );
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
            'id' => [
                'required' => true,
            ],
        ];
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