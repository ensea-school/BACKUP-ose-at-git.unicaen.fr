<?php

namespace Application\Form\Service;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Service;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Form\AbstractFieldset;
use Application\Form\OffreFormation\Traits\ElementPedagogiqueRechercheFieldsetAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\NiveauEtapeServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Application\Entity\Db\Etablissement;
use UnicaenAuth\Service\Traits\AuthorizeServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtablissementServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;


/**
 * Description of SaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldset extends AbstractFieldset
{
    use IntervenantAwareTrait;
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use NiveauEtapeServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ElementPedagogiqueRechercheFieldsetAwareTrait;
    use AuthorizeServiceAwareTrait;

    /**
     * etablissement par défaut
     *
     * @var Etablissement
     */
    protected $etablissement;



    public function init()
    {
        $this->etablissement = $this->getServiceContext()->getEtablissement();

        $hydrator = new SaisieFieldsetHydrator();

        $this->setName('service')
            ->setHydrator($hydrator)
            ->setAllowedObjectBindingClass(Service::class);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $intervenant = new SearchAndSelect('intervenant');
        $intervenant->setRequired(true)
            ->setSelectionRequired(true)
            ->setAutocompleteSource(
                $this->getUrl('recherche', ['action' => 'intervenantFind'])
            )
            ->setLabel("Intervenant :")
            ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
        $this->add($intervenant);

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

        $fs = $this->getFieldsetOffreFormationElementPedagogiqueRecherche();
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

        $this->add([
            'name'    => 'description',
            'options' => [
                'label' => 'Description :',
            ],
            'type'    => 'Text',
        ]);

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
                'id'    => $this->getServiceLocalContext()->getIntervenant()->getId(),
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
            'description'         => [
                'required' => false,
            ],
        ];
    }



    public function removeUnusedElements()
    {
        if ($this->getIntervenant()) {
            $canSaisieExterieur = $this->getServiceAuthorize()->isAllowed($this->getIntervenant(), Privileges::ENSEIGNEMENT_EXTERIEUR);
            $this->remove('intervenant');
            $this->add([
                'name'       => 'intervenant-id',
                'type'       => 'Hidden',
                'attributes' => [
                    'value' => $this->getIntervenant()->getId(),
                ],
            ]);
        } else {
            $canSaisieExterieur = $this->getServiceAuthorize()->isAllowed(Privileges::getResourceId(Privileges::ENSEIGNEMENT_EXTERIEUR));
        }

        if (!$canSaisieExterieur) {
            $this->remove('interne-externe');
            $this->remove('etablissement');
        }
    }

}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldsetHydrator implements HydratorInterface
{
    use ContextServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use EtablissementServiceAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array   $data
     * @param Service $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $intervenant = isset($data['intervenant']['id']) ? $data['intervenant']['id'] : null;
        if ($intervenant) {
            $object->setIntervenant($this->getServiceIntervenant()->getBySourceCode($intervenant));
        }

        if (isset($data['element-pedagogique']) && $data['element-pedagogique'] instanceof ElementPedagogique) {
            $object->setElementPedagogique($data['element-pedagogique']);
        } else {
            $elementPedagogique = isset($data['element-pedagogique']['element']['id']) ? $data['element-pedagogique']['element']['id'] : null;
            $object->setElementPedagogique($elementPedagogique ? $this->getServiceElementPedagogique()->get($elementPedagogique) : null);
        }

        $etablissement = isset($data['etablissement']['id']) ? (int)$data['etablissement']['id'] : null;
        $object->setEtablissement($etablissement ? $this->getServiceEtablissement()->get($etablissement) : null);

        $object->setDescription(isset($data['description']) ? $data['description'] : null);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param Service $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                  => $object->getId(),
            'element-pedagogique' => $object->getElementPedagogique(),
            'description'         => $object->getDescription(),
        ];

        if ($object->getIntervenant()) {
            $data['intervenant'] = [
                'id'    => $object->getIntervenant()->getId(),
                'label' => (string)$object->getIntervenant(),
            ];
        } else {
            $data['intervenant'] = null;
        }

        if ($object->getEtablissement()) {
            $data['etablissement'] = [
                'id'    => $object->getEtablissement()->getId(),
                'label' => (string)$object->getEtablissement(),
            ];
        } else {
            $data['etablissement'] = null;
        }

        if ($object->getEtablissement() === $this->getServiceContext()->getEtablissement()) {
            $data['interne-externe'] = 'service-interne';
        } else {
            $data['interne-externe'] = 'service-externe';
        }

        return $data;
    }
}
