<?php

namespace Application\Form\ServiceReferentiel;

use Application\Entity\Db\ServiceReferentiel;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\FonctionReferentielAwareTrait;
use Application\Service\Traits\LocalContextAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Filter\PregReplace;
use Zend\Validator\Callback;
use Zend\Validator\LessThan;
use Zend\Validator\NotEmpty;


/**
 * Description of SaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldset extends AbstractFieldset
{
    use ContextAwareTrait;
    use LocalContextAwareTrait;
    use StructureAwareTrait;
    use FonctionReferentielAwareTrait;



    public function __construct($name = null, $options = [])
    {
        parent::__construct('service', $options);
    }



    public function init()
    {

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceReferentielSaisieFieldsetHydrator'))
            ->setAllowedObjectBindingClass(ServiceReferentiel::class);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        if (!$this->getServiceContext()->getSelectedIdentityRole()->getIntervenant()) {
            $intervenant = new SearchAndSelect('intervenant');
            $intervenant->setRequired(true)
                ->setSelectionRequired(true)
                ->setAutocompleteSource(
                    $this->getUrl('recherche', ['action' => 'intervenantFind'])
                )
                ->setLabel("Intervenant :")
                ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
            $this->add($intervenant);
        }

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => "Structure :",
            ],
            'attributes' => [
                'title' => "Structure concernée",
                'class' => 'fonction-referentiel fonction-referentiel-structure input-sm',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'fonction',
            'options'    => [
                'label' => "Fonction :",
            ],
            'attributes' => [
                'title' => "Fonction référentielle",
                'class' => 'fonction-referentiel fonction-referentiel-fonction input-sm',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'heures',
            'options'    => [
                'label' => "Heures :",
            ],
            'attributes' => [
                'value' => "0",
                'title' => "Nombre d'heures",
                'class' => 'fonction-referentiel fonction-referentiel-heures input-sm',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'commentaires',
            'options'    => [
                'label' => "Commentaires :",
            ],
            'attributes' => [
                'title' => "Commentaires éventuels",
                'class' => 'fonction-referentiel fonction-referentiel-commentaires input-sm',
            ],
            'type'       => 'Textarea',
        ]);

        // liste déroulante des structures
        $options     = [];
        $options[''] = "(Sélectionnez une structure...)"; // setEmptyOption() pas utilisé car '' n'est pas compris dans le validateur InArray
        foreach ($this->getStructures() as $item) {
            $options[$item->getId()] = "" . $item;
        }
        asort($options);
        $this->get('structure')->setValueOptions($options);//->setEmptyOption("(Sélectionnez une structure...)");

        // liste déroulante des fonctions
        $options     = [];
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
            $qb = $this->getServiceStructure()->finderByEnseignement();
            $this->getServiceStructure()->finderByNiveau(2, $qb);
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
        /* Peuple le formulaire avec les valeurs issues du contexte local */
        $cl = $this->getServiceLocalContext();
        if ($this->has('intervenant') && $cl->getIntervenant()) {
            $this->get('intervenant')->setValue([
                'id'    => $cl->getIntervenant()->getSourceCode(),
                'label' => (string)$cl->getIntervenant(),
            ]);
        }

        if ($structure = $this->getServiceContext()->getSelectedIdentityRole()->getStructure() ?: $cl->getStructure()) {
            $this->get('structure')->setValue($structure->getId());
        }
    }



    public function saveToContext()
    {
        $cl = $this->getServiceLocalContext();

        if (($structureId = $this->get('structure')->getValue())) {
            $cl->setStructure($this->getServiceStructure()->get($structureId));
        } else {
            $cl->setStructure(null);
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
        $structures      = $this->getStructures();
        $value           = $this->get('structure')->getValue();
        $structureSaisie = isset($structures[$value]) ? $structures[$value] : null;
        if (!$structureSaisie) {
            return null;
        }

        // structure éventuelle associée à la fonction
        $structureFonction = $fonctionSaisie->getStructure();

        // si aucune structure n'est associée à la fonction, on vérifie simplement que la structure sélectionnée est de niveau 2
        if (!$structureFonction) {
            $callback = function () use ($structureSaisie) {
                return $structureSaisie->getNiveau() === 2;
            };
            $message  = "Composante d'enseignement requise";
        } // si une structure est associée à la fonction, la structure sélectionnée soit être celle-là
        else {
            $callback = function () use ($structureSaisie, $structureFonction) {
                return $structureSaisie === $structureFonction;
            };
            $message  = sprintf("Structure obligatoire : '%s'", $structureFonction);
        }

        $v = new Callback($callback);
        $v->setMessages([Callback::INVALID_VALUE => $message]);

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
            $v = new LessThan(['max' => $max = (float)$fonction->getPlafond(), 'inclusive' => true]);
            $v->setMessages([LessThan::NOT_LESS_INCLUSIVE => "Le plafond pour cette fonction est de $max"]);
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
        $specs = [
            'structure'    => [
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'Zend\Validator\NotEmpty',
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => "La structure est requise",
                            ],
                        ],
                    ],
                ],
            ],
            'fonction'     => [
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'Zend\Validator\NotEmpty',
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => "La fonction référentielle est requise",
                            ],
                        ],
                    ],
                ],
            ],
            'heures'       => [
                'required' => true,
                'filters'  => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    new PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ],
            'commentaires' => [
                'required' => false,
                'filters'  => [
                    ['name' => 'Zend\Filter\StringTrim'],
                ],
            ],
        ];

        if (($validator = $this->getValidatorStructure())) {
            $specs['structure']['validators'][] = $validator;
        }

        if (($validator = $this->getValidatorHeures())) {
            $specs['heures']['validators'][] = $validator;
        }

        return $specs;
    }

}