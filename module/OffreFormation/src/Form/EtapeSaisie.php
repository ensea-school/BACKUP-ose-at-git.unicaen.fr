<?php

namespace OffreFormation\Form;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Laminas\Form\FormInterface;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Service\Traits\TypeFormationServiceAwareTrait;
use Paiement\Service\DomaineFonctionnelServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

/**
 * Description of EtapeSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisie extends AbstractForm
{
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use TypeFormationServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use SchemaServiceAwareTrait;

    private $typesFormation;



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        /* Définition de l'hydrateur */
        $hydrator = new EtapeSaisieHydrator;
        $hydrator->setServiceDomaineFonctionnel($this->getServiceDomaineFonctionnel());
        $hydrator->setServiceStructure($this->getServiceStructure());
        $hydrator->setServiceTypeFormation($this->getServiceTypeFormation());
        $this->setHydrator($hydrator);
        $this->setAttribute('class', 'etape-saisie');
        $this->setAttribute('action', $this->getCurrentUrl());

        /* construction du formulaire */
        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => 'Code',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => 'Libellé',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'       => 'typeFormation',
            'options'    => [
                'label' => 'Type de formation',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'    => 'niveau',
            'options' => [
                'label' => 'Niveau',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'specifiqueEchanges',
            'options' => [
                'label' => 'Spécifique aux échanges',
                'use_hidden_element' => true,
                'checked_value'      => '1',
                'unchecked_value'    => '0',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'structure',
            'type'    => Structure::class,
            'options' => [
                'enseignement' => true,
            ],
        ]);

        $this->add([
            'name'       => 'domaineFonctionnel',
            'options'    => [
                'label' => 'Domaine fonctionnel',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        /** Hack pour formule ASSAS : @todo à remplacer par une vraie gestion des champs autres */
        $formuleId = (int)$this->getServiceParametres()->get('formule');
        if (29 == $formuleId){ // formule d'ASSAS
            $this->add([
                'type'       => 'Select',
                'name'       => 'autre1',
                'options'    => [
                    'label' => 'Diplôme national',
                    'value_options' => ['Oui' => 'Oui', 'Non' => 'Non'],
                    'empty_option'  => 'Veuillez préciser...',
                ],

            ]);
        }else{
            $this->add([
                'name' => 'autre1',
                'type' => 'Hidden',
            ]);
        }


        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        // peuplement liste des types de formation
        $valueOptions = \UnicaenApp\Util::collectionAsOptions($this->getTypesFormation());
        $this->get('typeFormation')
            ->setEmptyOption(count($valueOptions) > 1 ? "(Sélectionnez un type...)" : null)
            ->setValueOptions($valueOptions);

        // peuplement liste des domaines fonctionnels
        $this->get('domaineFonctionnel')
            ->setEmptyOption("(Aucun)")
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceDomaineFonctionnel()->getList()));

        $localContext = $this->getServiceLocalContext();

        if ($structure = $localContext->getStructure()) {
            // si un filtre structure est positionné dans le contexte local, on l'utilise
            $this->get('structure')->setValue($structure->getId());
        }

        // init niveau
        if ($localContext->getNiveau()) {
            // si un filtre niveau est positionné dans le contexte local, on l'utilise
            $this->get('niveau')->setValue($localContext->getNiveau()->getNiv());
        }
    }




    public function bind ($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object \Lieu\Entity\Db\Structure */
        parent::bind($object, $flags);

        if ($object->getSource() && $object->getSource()->getImportable()) {
            foreach ($this->getElements() as $element) {
                if ($this->getServiceSchema()->isImportedProperty($object, $element->getName())) {
                    $element->setAttribute('readonly', true);
                    $element->setAttribute('disabled', true);
                }
            }
        }

        return $this;
    }




    /**
     * @return \OffreFormation\Entity\Db\TypeFormation[]
     */
    private function getTypesFormation()
    {
        if (null === $this->typesFormation) {
            $qb = null;
            if (($niveau = $this->getServiceLocalContext()->getNiveau())) {
                $qb = $this->getServiceTypeFormation()->finderByNiveau($niveau);
            }
            $this->typesFormation = $this->getServiceTypeFormation()->getList($qb);
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
        foreach ($this->getTypesFormation() as $tf) {
            /* @var $tf \OffreFormation\Entity\Db\TypeFormation */
            $groupe = $tf->getGroupe();
            if($groupe){
                $pertinencesNiveau[$tf->getId()] = (bool)$tf->getGroupe()->getPertinenceNiveau();
            }
            else{
                $pertinencesNiveau[$tf->getId()] = false;
            }
        }

        return $pertinencesNiveau;
    }



    /**
     *
     * @return bool
     */
    private function getRequiredNiveau()
    {
        $typeFormation = $this->get('typeFormation')->getValue();
        $pertinencesNiveau = $this->getPertinencesNiveau();
        $pertinent = isset($pertinencesNiveau[$typeFormation]) && (bool)$pertinencesNiveau[$typeFormation];

        return $pertinent;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'code'                => [
                'required' => true,
            ],
            'libelle'             => [
                'required' => true,
            ],
            'typeFormation'      => [
                'required' => true,
            ],
            'niveau'              => [
                'required'   => $this->getRequiredNiveau(),
                'validators' => [
                    ['name' => 'Int'],
                ],
            ],
            'structure'           => [
                'required' => false,
            ],
            'domaineFonctionnel' => [
                'required' => false,
            ],
            'specifiqueEchanges' => [
                'required' => false,
            ],
        ];
    }
}


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisieHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;
    use DomaineFonctionnelServiceAwareTrait;
    use TypeFormationServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param \OffreFormation\Entity\Db\Etape $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setSourceCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setTypeFormation($this->getServiceTypeFormation()->get($data['typeFormation']));
        if (array_key_exists('niveau', $data)) {
            $object->setNiveau($data['niveau']);
        }
        $object->setSpecifiqueEchanges($data['specifiqueEchanges'] ?? false);
        if (array_key_exists('structure', $data)) {
            $object->setStructure($this->getServiceStructure()->get($data['structure']));
        }
        if (array_key_exists('domaineFonctionnel', $data)) {
            $object->setDomaineFonctionnel($this->getServiceDomaineFonctionnel()->get($data['domaineFonctionnel']));
        }
        $object->setAutre1($data['autre1']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \OffreFormation\Entity\Db\Etape $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'code'                => $object->getCode(),
            'libelle'             => $object->getLibelle(),
            'id'                  => $object->getId(),
            'typeFormation'      => ($tf = $object->getTypeFormation()) ? $tf->getId() : null,
            'niveau'              => $object->getNiveau(),
            'specifiqueEchanges' => $object->getSpecifiqueEchanges() ? '1' : '0',
            'structure'           => ($s = $object->getStructure()) ? $s->getId() : null,
            'domaineFonctionnel' => ($s = $object->getDomaineFonctionnel()) ? $s->getId() : null,
            'autre1' => $object->getAutre1(),
        ];

        return $data;
    }
}