<?php

namespace Application\Form\Workflow;

use Application\Entity\Db\WfEtapeDep;
use Application\Form\AbstractForm;
use Application\Service\Traits\WfEtapeAwareTrait;
use UnicaenApp\Util;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Description of DependanceForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DependanceForm extends AbstractForm
{
    use WfEtapeAwareTrait;



    public function init()
    {
        $hydrator = new DependanceFormHydrator;
        $hydrator->setServiceWfEtape($this->getServiceWfEtape());
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $etapes = $this->getServiceWfEtape()->getList();

        $this->add([
            'name'       => 'etape-suivante',
            'options'    => [
                'label'         => 'Etape suivante',
                'value_options' => Util::collectionAsOptions($etapes),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'etape-precedante',
            'options'    => [
                'label'         => 'Etape précédante',
                'value_options' => Util::collectionAsOptions($etapes),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'active',
            'options'    => [
                'label'              => '<abbr title="Définit si la dépendance est prise en compte par le Workflow ou non">Active</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
                'checked_value'      => 'true',
                'unchecked_value'    => 'false',
            ],
            'attributes' => [
                'title' => "Définit si la dépendance est prise en compte par le Workflow ou non",
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'locale',
            'options'    => [
                'label'              => '<abbr title="Le test ne se fait qu\'au sein d\'une même composante ou sur des étapes non attachées à des composantes">Locale</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
                'checked_value'      => 'true',
                'unchecked_value'    => 'false',
            ],
            'attributes' => [
                'title' => "La dépendance ne joue que si une des étapes n'a pas de composante ou bien si les composantes des étapes sont identiques",
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'integrale',
            'options'    => [
                'label'              => '<abbr title="Franchissement impératif pour toutes les composantes concernées">Intégrale</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
                'checked_value'      => 'true',
                'unchecked_value'    => 'false',
            ],
            'attributes' => [
                'title' => "Toutes les règles de dépendances doivent être satisfaites. A défaut, une seule dépendance respectant les critères suffit à rendre l'étape courante atteignable",
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'partielle',
            'options'    => [
                'label'              => '<abbr title="L\'étape peut n\'être que partiellement franchie">Partielle</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
                'checked_value'      => 'true',
                'unchecked_value'    => 'false',
            ],
            'attributes' => [
                'title' => "L'étape n'est atteignable que si ses dépendances ont été partiellement franchies",
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'obligatoire',
            'options'    => [
                'label'              => '<abbr title="L\'étape peut n\'être franchie que si l\'étape qui en dépend a été testée">Obligatoire</abbr>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'use_hidden_element' => true,
                'checked_value'      => 'true',
                'unchecked_value'    => 'false',
            ],
            'attributes' => [
                'title' => "L'étape peut n'être franchie que si l'étape qui en dépend a été testée",
            ],
            'type'       => 'Checkbox',
        ]);
        
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
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
            'etape-suivante'   => ['required' => true],
            'etape-precedante' => ['required' => true],
            'active'           => ['required' => true],
            'locale'           => ['required' => true],
            'integrale'        => ['required' => true],
            'partielle'        => ['required' => true],
            'obligatoire'      => ['required' => true],
        ];
    }

}





class DependanceFormHydrator implements HydratorInterface
{
    use WfEtapeAwareTrait;



    /**
     * @param  array      $data
     * @param  WfEtapeDep $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* on peuple l'objet à partir du tableau de données */
        $object->setEtapeSuiv($this->getServiceWfEtape()->get($data['etape-suivante']));
        $object->setEtapePrec($this->getServiceWfEtape()->get($data['etape-precedante']));
        $object->setActive($data['active'] == 'true');
        $object->setLocale($data['locale'] == 'true');
        $object->setIntegrale($data['integrale'] == 'true');
        $object->setPartielle($data['partielle'] == 'true');
        $object->setObligatoire($data['obligatoire'] == 'true');

        return $object;
    }



    /**
     * @param WfEtapeDep $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'etape-suivante'   => $object->getEtapeSuiv()   ? $object->getEtapeSuiv()->getId() : null,
            'etape-precedante' => $object->getEtapePrec()   ? $object->getEtapePrec()->getId() : null,
            'active'           => $object->getActive()      ? 'true' : 'false',
            'locale'           => $object->getLocale()      ? 'true' : 'false',
            'integrale'        => $object->getIntegrale()   ? 'true' : 'false',
            'partielle'        => $object->getPartielle()   ? 'true' : 'false',
            'obligatoire'      => $object->getObligatoire() ? 'true' : 'false',
        ];

        return $data;
    }
}