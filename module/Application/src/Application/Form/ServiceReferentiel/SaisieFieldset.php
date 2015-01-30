<?php

namespace Application\Form\ServiceReferentiel;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\FonctionReferentiel;
use Application\Service\Structure;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Filter\PregReplace;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Validator\Callback;
use Zend\Validator\LessThan;
use Zend\Validator\NotEmpty;
use Zend\View\Helper\Url;

/**
 * Description of SaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    public function __construct($name = null, $options = array())
    {
        parent::__construct('service', $options);
    }

    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Url */

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceReferentielSaisieFieldsetHydrator'))
                ->setAllowedObjectBindingClass('Application\Entity\Db\ServiceReferentiel');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $identityRole       = $this->getContextProvider()->getSelectedIdentityRole();
        $contextIntervenant = $this->getContextProvider()->getGlobalContext()->getIntervenant();

        if (!$identityRole instanceof IntervenantRole) {
            $intervenant = new SearchAndSelect('intervenant');
            $intervenant->setRequired(true)
                    ->setSelectionRequired(true)
                    ->setAutocompleteSource(
                            $url('recherche', array('action' => 'intervenantFind'))
                    )
                    ->setLabel("Intervenant :")
                    ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
            $this->add($intervenant);
        }

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

        // liste déroulante des structures
        $options = array();
        $options[''] = "(Sélectionnez une structure...)"; // setEmptyOption() pas utilisé car '' n'est pas compris dans le validateur InArray
        foreach ($this->getStructures() as $item) {
            $options[$item->getId()] = "" . $item;
        }
        asort($options);
        $this->get('structure')->setValueOptions($options);//->setEmptyOption("(Sélectionnez une structure...)");
        
        // liste déroulante des fonctions
        $options = array();
        $options[''] = "(Sélectionnez une fonction...)"; // setEmptyOption() pas utilisé car '' n'est pas compris dans le validateur InArray
        foreach ($this->getFonctions() as $item) {
            $options[$item->getId()] = "" . $item;
        }
        $this->get('fonction')->setValueOptions($options);//->setEmptyOption("(Sélectionnez une fonction...)");

    }

    protected $structures;
    
    public function getStructures()
    {
        if (null === $this->structures) {
            $qb   = $this->getServiceStructure()->finderByEnseignement();
            $univ = $this->getServiceStructure()->getRacine();
            
            $this->structures = [$univ->getId() => $univ] + $this->getServiceStructure()->getList($qb);
        }
        
        return $this->structures;
    }

    protected $fonctions;
    
    public function getFonctions()
    {
        if (null === $this->fonctions) {
            $this->fonctions = $this->getServiceFonctionReferentiel()->getList();
        }
        
        return $this->fonctions;
    }
    
    public function initFromContext()
    {
        /* Peuple le formulaire avec les valeurs par défaut issues du contexte global */
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        $cl = $this->getContextProvider()->getLocalContext();

        if ($this->has('intervenant') && $cl->getIntervenant()) {
            $this->get('intervenant')->setValue(array(
                'id'    => $cl->getIntervenant()->getSourceCode(),
                'label' => (string) $cl->getIntervenant()
            ));
        }

        if (!$role instanceof IntervenantRole) {
            if ($cl->getStructure()) {
                $structure    = $cl->getStructure();
                $valueOptions = array($structure->getId() => (string) $structure);
                $this->get('structure')->setValue($structure->getId());
            }
        }

        // la structure de responsabilité du gestionnaire écrase celle du contexte local
        if ($role instanceof ComposanteRole) { // Si c'est un membre d'une composante
            $structure    = $role->getStructure();
            $valueOptions = array($structure->getId() => (string) $structure);
            //    $this->getServiceStructure()->finderById($structure->getId(), $fs->getQueryBuilder());
            $this->get('structure')->setValue($structure->getId());
        }
    }

    public function saveToContext()
    {
        /* Met à jour le contexte local en fonction des besoins... */
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        $cl = $this->getContextProvider()->getLocalContext();

        if (!$role instanceof IntervenantRole) {
            if (($structureId = $this->get('structure')->getValue())) {
                $cl->setStructure($this->getServiceStructure()->get($structureId));
            }
            else {
                $cl->setStructure(null);
            }
        }
    }

    /**
     *
     * @return Callback|null
     */
    protected function getValidatorStructure()
    {
        // recherche de la FonctionReferentiel sélectionnée pour connaître la structure associée éventuelle
        $fonctions      = $this->getFonctions();
        $value          = $this->get('fonction')->getValue();
        $fonctionSaisie = isset($fonctions[$value]) ? $fonctions[$value] : null;
        if (!$fonctionSaisie) {
            return null;
        }

        // recherche de la Structure sélectionnée
        $structures     = $this->getStructures();
        $value          = $this->get('structure')->getValue();
        $structureSaisie = isset($structures[$value]) ? $structures[$value] : null;
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
        $v         = null;
        $fonctions = $this->getFonctions();
        $id        = $this->get('fonction')->getValue();
        $fonction  = isset($fonctions[$id]) ? $fonctions[$id] : null;
       
        if ($fonction) {
            $v = new LessThan(array('max' => $max = (float)$fonction->getPlafond(), 'inclusive' => true));
            $v->setMessages(array(LessThan::NOT_LESS_INCLUSIVE => "Le plafond pour cette fonction est de $max"));
        }
       
        return $v;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.

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
//                'validators' => array(
//                    array(
//                        'name' => 'Zend\Validator\NotEmpty',
//                        'options' => array(
//                            'string',
//                            'integer',
//                            'zero',
//                            'messages' => array(
//                                NotEmpty::IS_EMPTY => "Un nombre d'heures non nul est requis",
//                            ),
//                        ),
//                    ),
//                ),
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
     * @return Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return FonctionReferentiel
     */
    protected function getServiceFonctionReferentiel()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationFonctionReferentiel');
    }
}