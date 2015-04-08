<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of EtapeSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

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
        $this->add( [
            'name' => 'source-code',
            'options' => [
                'label' => 'Code',
            ],
            'type' => 'Text'
        ] );

        $this->add( [
            'name' => 'libelle',
            'options' => [
                'label' => 'Libellé',
            ],
            'type' => 'Text'
        ] );

        $this->add( [
            'name' => 'type-formation',
            'options' => [
                'label' => 'Type de formation',
            ],
            'type' => 'Select',
        ] );

        $this->add( [
            'name' => 'niveau',
            'options' => [
                'label' => 'Niveau',
            ],
            'type' => 'Text',
        ] );

        $this->add( [
            'name' => 'specifique-echanges',
            'options' => [
                'label' => 'Spécifique aux échanges',
            ],
            'type' => 'Checkbox',
        ] );

        $this->add( [
            'name' => 'structure',
            'options' => [
                'label' => 'Structure',
            ],
            'type' => 'Select',
        ] );

        $this->add( [
            'name' => 'domaine-fonctionnel',
            'options' => [
                'label' => 'Domaine fonctionnel',
            ],
            'type' => 'Select',
        ] );

        $this->add( [
            'name' => 'id',
            'type' => 'Hidden'
        ] );

        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $localContext = $this->getServiceLocator()->getServiceLocator()->get('applicationLocalContext');
        /* @var $localContext \Application\Service\LocalContext */

        // peuplement liste des structures
        if ($localContext->getStructure()) {
            // si un filtre structure est positionné dans le contexte local, on l'utilise
            $this->get('structure')
                    ->setValueOptions([$id = $localContext->getStructure()->getId() => (string) $localContext->getStructure()])
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

        // peuplement liste des domaines fonctionnels
        $serviceDomaineFonctionnel = $this->getServiceLocator()->getServiceLocator()->get('ApplicationDomaineFonctionnel');
        $this->get('domaine-fonctionnel')
                ->setEmptyOption("(Aucun)")
                ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceDomaineFonctionnel->getList()));

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
            $localContext = $this->getServiceLocator()->getServiceLocator()->get('applicationLocalContext');
            /* @var $localContext \Application\Service\LocalContext */
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
        $pertinencesNiveau = [];
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
        return [
            'source-code' => [
                'required' => true,
            ],
            'libelle' => [
                'required' => true,
            ],
            'type-formation' => [
                'required' => true,
            ],
            'niveau' => [
                'required' => $this->getRequiredNiveau(),
                'validators' => [
                    ['name' => 'Int'],
                ],
            ],
            'structure' => [
                'required' => false,
            ],
            'domaine-fonctionnel' => [
                'required' => false,
            ],
        ];
    }
}