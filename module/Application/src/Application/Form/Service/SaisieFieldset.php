<?php

namespace Application\Form\Service;

use Application\Entity\Db\Service;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\LocalContextAwareTrait;
use Application\Service\Traits\NiveauEtapeAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Application\Entity\Db\Etablissement;




/**
 * Description of SaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldset extends AbstractFieldset
{
    use ContextAwareTrait;
    use LocalContextAwareTrait;
    use EtapeAwareTrait;
    use NiveauEtapeAwareTrait;
    use StructureAwareTrait;

    /**
     * etablissement par défaut
     *
     * @var Etablissement
     */
    protected $etablissement;



    public function __construct($name = null, $options = [])
    {
        parent::__construct('service', $options);
    }



    public function init()
    {
        $this->etablissement = $this->getServiceContext()->getEtablissement();

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceSaisieFieldsetHydrator'))
            ->setAllowedObjectBindingClass(Service::class);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $identityRole = $this->getServiceContext()->getSelectedIdentityRole();

        if (!$identityRole->getIntervenant()) {
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

        if (!($identityRole->getIntervenant() && !$identityRole->getIntervenant()->estPermanent())) {
            $this->add([
                'type'       => 'Radio',
                'name'       => 'interne-externe',
                'options'    => [
                    'label'         => "Enseignement effectué :",
                    'value_options' => [
                        'service-interne' => 'en interne',
                        'service-externe' => 'hors ' . $this->etablissement,
                    ],
                ],
                'attributes' => [
                    'value' => 'service-interne',
                ],
            ]);
        }

        $fs = $this->getServiceLocator()->get('FormElementPedagogiqueRechercheFieldset');
        $fs->setName('element-pedagogique');
        $this->add($fs);

        $etablissement = new SearchAndSelect('etablissement');
        $etablissement->setRequired(true)
            ->setSelectionRequired(true)
            ->setAutocompleteSource(
                $this->getUrl('etablissement/recherche')
            )
            ->setLabel("Établissement :")
            ->setAttributes(['title' => "Saisissez le libellé (2 lettres au moins)"]);
        $this->add($etablissement);

        return $this;
    }



    public function initFromContext()
    {
        /* Peuple le formulaire avec les valeurs par défaut issues du contexte global */
        $fs = $this->get('element-pedagogique');
        /* @var $fs \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset */

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        if ($this->has('intervenant') && $this->getServiceLocalContext()->getIntervenant()) {
            $this->get('intervenant')->setValue([
                'id'    => $this->getServiceLocalContext()->getIntervenant()->getSourceCode(),
                'label' => (string)$this->getServiceLocalContext()->getIntervenant(),
            ]);
        }

        if ($structure = $this->getServiceContext()->getSelectedIdentityRole()->getStructure() ?: $this->getServiceLocalContext()->getStructure()) {
            $fs->get('structure')->setValue($structure->getId());
        }

        if ($niveau = $this->getServiceLocalContext()->getNiveau()) {
            $fs->get('niveau')->setValue($niveau->getId());
        }

        if ($etape = $this->getServiceLocalContext()->getEtape()) {
            $fs->get('etape')->setValue($etape->getId());
        }

        if ($element = $this->getServiceLocalContext()->getElementPedagogique()) {
            $fs->get('element')->setValue([
                'id'    => $element->getId(),
                'label' => (string)$element,
            ]);
        }

        if ($this->has('interne-externe')) {
            $this->get('interne-externe')->setValue('service-interne');
        }

        return $this;
    }



    public function saveToContext()
    {
        /* Met à jour le contexte local en fonction des besoins... */
        $fs = $this->get('element-pedagogique');
        /* @var $fs \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset */

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        if ($structureId = $fs->get('structure')->getValue()) {
            $this->getServiceLocalContext()->setStructure($this->getServiceStructure()->get($structureId));
        } else {
            $this->getServiceLocalContext()->setStructure(null);
        }

        if ($niveauId = $fs->get('niveau')->getValue()) {
            $this->getServiceLocalContext()->setNiveau($this->getServiceNiveauEtape()->get($niveauId));
        } else {
            $this->getServiceLocalContext()->setNiveau(null);
        }

        if ($etapeId = $fs->get('etape')->getValue()) {
            $this->getServiceLocalContext()->setEtape($this->getServiceEtape()->get($etapeId));
        } else {
            $this->getServiceLocalContext()->setEtape(null);
        }

        return $this;
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
            'interne-externe'     => [
                'required' => false,
            ],
            'etablissement'       => [
                'required' => false,
            ],
            'element-pedagogique' => [
                'required' => false,
            ],
        ];
    }
}