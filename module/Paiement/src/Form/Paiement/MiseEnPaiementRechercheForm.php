<?php

namespace Paiement\Form\Paiement;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\MiseEnPaiementRecherche;

/**
 * Description of MiseEnPaiementRechercheForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementRechercheForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;
    use ContextServiceAwareTrait;

    /**
     *
     * @var string
     */
    private $id;



    /**
     * Retourne un identifiant unique de formulaire.
     * Une fois ce dernier initialisé, il ne change plus pour l'instance en cours
     *
     * @return string
     */
    public function getId()
    {
        if (null === $this->id) $this->id = uniqid();

        return $this->id;
    }



    /**
     *
     */
    public function init()
    {
        $hydrator = new MiseEnPaiementRechercheFormHydrator;

        $this->setHydrator($hydrator)
            ->setAllowedObjectBindingClass(MiseEnPaiementRecherche::class);

        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'paiement-mise-en-paiement-recherche-form')
            ->setAttribute('id', $this->getId());

        $this->add([
            'type'       => 'Laminas\Form\Element\Radio',
            'name'       => 'type-intervenant',
            'options'    => [
                'label'         => 'Statut des intervenants',
                'value_options' => [
                    ''                                                          => "Peu importe",
                    $this->getServiceTypeIntervenant()->getPermanent()->getId() => "Permanent",
                    $this->getServiceTypeIntervenant()->getExterieur()->getId() => "Vacataire",
                    $this->getServiceTypeIntervenant()->getEtudiant()->getId()  => "Étudiant",
                ],
            ],
            'attributes' => [
                'class' => 'input-sm',
            ],
        ]);

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => "Composante",
            ],
            'attributes' => [
                'class'            => 'input-sm selectpicker',
                'data-live-search' => true,
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'type'    => 'Select',
            'name'    => 'periode',
            'options' => [
                'label' => 'Période',
            ],
        ]);

        $this->add([
            'type'       => 'Laminas\Form\Element\MultiCheckbox',
            'attributes' => [
                'multiple' => 'multiple',
            ],
            'name'       => 'intervenants',
            'options'    => [
                'label' => 'Intervenants',
            ],
        ]);

        $this->add([
            'name'       => 'suite',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Suite...',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->add([
            'name'       => 'afficher',
            'type'       => 'Submit',
            'attributes' => [
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->add([
            'name'       => 'exporter-pdf',
            'type'       => 'Submit',
            'attributes' => [
                'class' => 'btn btn-secondary',
            ],
        ]);

        $this->add([
            'name'       => 'exporter-csv-etat',
            'type'       => 'Submit',
            'attributes' => [
                'class' => 'btn btn-secondary',
            ],
        ]);

        $this->add([
            'name'       => 'exporter-csv-imputation',
            'type'       => 'Submit',
            'attributes' => [
                'class' => 'btn btn-secondary',
            ],
        ]);
    }



    /**
     *
     * @param array $structures
     */
    public function populateStructures($structures)
    {
        $this->get('structure')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($structures));
    }



    /**
     *
     * @param array $periodes
     */
    public function populatePeriodes($periodes)
    {
        $annee = $this->getServiceContext()->getAnnee();
        $this->get('periode')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($periodes, false, function ($p) use ($annee) {
            return $p->getLibelleAnnuel($annee);
        }));
    }



    /**
     *
     * @param array $intervenants
     */
    public function populateIntervenants($intervenants)
    {
        $this->get('intervenants')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($intervenants));
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
            'type-intervenant' => [
                'required' => false,
            ],
            'structure'        => [
                'required' => false,
            ],
            'periode'          => [
                'required' => false,
            ],
            'intervenants'     => [
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
class MiseEnPaiementRechercheFormHydrator implements HydratorInterface
{
    use IntervenantServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use StructureServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param \Paiement\Entity\MiseEnPaiementRecherche $object
     *
     * @return \Paiement\Entity\MiseEnPaiementRecherche
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['type-intervenant']) ? (int)$data['type-intervenant'] : null;
        $object->setTypeIntervenant($this->getServiceTypeIntervenant()->get($id));

        $id = isset($data['structure']) ? (int)$data['structure'] : null;
        $object->setStructure($this->getServiceStructure()->get($id));

        $id = isset($data['periode']) ? (int)$data['periode'] : null;
        $object->setPeriode($this->getServicePeriode()->get($id));

        if (isset($data['intervenants']) && is_array($data['intervenants'])) {
            foreach ($data['intervenants'] as $id) {
                $object->getIntervenants()->add($this->getServiceIntervenant()->get($id));
            }
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Paiement\Entity\MiseEnPaiementRecherche $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'type-intervenant' => $object->getTypeIntervenant() ? $object->getTypeIntervenant()->getId() : null,
            'structure'        => $object->getStructure() ? $object->getStructure()->getId() : null,
            'periode'          => $object->getPeriode() ? $object->getPeriode()->getId() : null,
            'intervenants'     => [],
        ];
        foreach ($object->getIntervenants() as $intervenant) {
            $data['intervenants'][] = $intervenant->getId();
        }

        return $data;
    }

}