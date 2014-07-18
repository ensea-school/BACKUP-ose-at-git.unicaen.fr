<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of EtapeSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    private $typesFormation;
    
    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        /* Définition de l'hydrateur */
        $hydrator = new EtapeSaisieHydrator;
        $hydrator->setServiceLocator($this->getServiceLocator()->getServiceLocator());
        $this->setHydrator($hydrator);

        /* construction du formulaire */
        $this->add( array(
            'name' => 'source-code',
            'options' => array(
                'label' => 'Code',
            ),
            'type' => 'Text'
        ) );

        $this->add( array(
            'name' => 'libelle',
            'options' => array(
                'label' => 'Libellé',
            ),
            'type' => 'Text'
        ) );

        $this->add( array(
            'name' => 'type-formation',
            'options' => array(
                'label' => 'Type de formation',
            ),
            'type' => 'Select',
        ) );

        $this->add( array(
            'name' => 'niveau',
            'options' => array(
                'label' => 'Niveau',
            ),
            'type' => 'Text',
        ) );

        $this->add( array(
            'name' => 'specifique-echanges',
            'options' => array(
                'label' => 'Spécifique aux échanges',
            ),
            'type' => 'Checkbox',
        ) );

        $this->add( array(
            'name' => 'structure',
            'options' => array(
                'label' => 'Structure',
            ),
            'type' => 'Select',
        ) );

        $this->add( array(
            'name' => 'id',
            'type' => 'Hidden'
        ) );

        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));
        
        $localContext = $this->getContextProvider()->getLocalContext();
        
        // peuplement liste des structures
        if ($localContext->getStructure()) {
            // si un filtre structure est positionné dans le contexte local, on l'utilise
            $this->get('structure')
                    ->setValueOptions(array($id = $localContext->getStructure()->getId() => (string) $localContext->getStructure()))
                    ->setValue($id)
                    ->setAttribute('disabled', true);
        }
        else {
            $serviceStructure = $this->getServiceLocator()->getServiceLocator()->get('ApplicationStructure');
            $qb = $serviceStructure->finderByEnseignement( $serviceStructure->finderByNiveau(2) );
            $this->get('structure')
                    ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceStructure->getList($qb)));
        }
        
        // peuplement liste des types de formation
        $valueOptions = \UnicaenApp\Util::collectionAsOptions($this->getTypesFormation());
        $this->get('type-formation')
                ->setEmptyOption(count($valueOptions) > 1 ? "(Sélectionnez un type...)" : null)
                ->setValueOptions($valueOptions);
        
        // init niveau
        if ($localContext->getNiveau()) {
            // si un filtre niveau est positionné dans le contexte local, on l'utilise
            $this->get('niveau')
                    ->setValue($localContext->getNiveau()->getNiv())
                    ->setAttribute('readonly', true);
        }
    }
    
    /**
     * @return \Application\Entity\Db\TypeFormation[]
     */
    private function getTypesFormation()
    {
        if (null === $this->typesFormation) {
            $serviceTypeFormation = $this->getServiceLocator()->getServiceLocator()->get('ApplicationTypeFormation');
            $localContext         = $this->getContextProvider()->getLocalContext();
            $qb                   = null;
            
            if (($niveau = $localContext->getNiveau())) {
                $qb = $serviceTypeFormation->finderByNiveau($niveau);
            }
            
            $this->typesFormation = $serviceTypeFormation->getList($qb);
        }
        
        return $this->typesFormation;
    }
    
    /**
     * Retourne pour chaque type de formation le flag indiquant si la saisie d'un niveau est pertienent ou non.
     * 
     * @return array id => bool
     */
    public function getPertinencesNiveau()
    {
        $pertinencesNiveau = array();
        foreach ($this->getTypesFormation() as $tf) { /* @var $tf \Application\Entity\Db\TypeFormation */
            $pertinencesNiveau[$tf->getId()] = (bool) $tf->getGroupe()->getPertinenceNiveau();
        }
        
        return $pertinencesNiveau;
    }
    
    /**
     * 
     * @return bool
     */
    private function getRequiredNiveau()
    {
        $typeFormation     = $this->get('type-formation')->getValue();
        $pertinencesNiveau = $this->getPertinencesNiveau();
        $pertinent         = isset($pertinencesNiveau[$typeFormation]) && (bool) $pertinencesNiveau[$typeFormation];
        
        return $pertinent;
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
            'source-code' => array(
                'required' => true,
            ),
            'libelle' => array(
                'required' => true,
            ),
            'type-formation' => array(
                'required' => true,
            ),
            'niveau' => array(
                'required' => $this->getRequiredNiveau(),
                'validators' => array(
                    array('name' => 'Int'),
                ),
            ),
            'structure' => array(
                'required' => false,
            ),
        );
    }
}