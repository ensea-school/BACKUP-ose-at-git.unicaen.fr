<?php

namespace Paiement\Form\Paiement;

use Application\Entity\Db\Periode;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Laminas\Form\Element\Select;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\Db\MiseEnPaiement;
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

    private string $id = '';

    private array $typesIntervenants = [];

    private bool $periodeFilter = true;

    private bool $structureFilter = true;



    /**
     * Retourne un identifiant unique de formulaire.
     * Une fois ce dernier initialisé, il ne change plus pour l'instance en cours
     *
     * @return string
     */
    public function getId()
    {
        if ('' === $this->id) $this->id = uniqid();

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
            ->setAttribute('class', 'paiement-mise-en-paiement-recherche-form no-intranavigation')
            ->setAttribute('id', $this->getId());

        $this->add([
            'type'       => 'Laminas\Form\Element\Radio',
            'name'       => 'typeIntervenant',
            'options'    => [
                'label' => 'Statut des intervenants',
            ],
            'attributes' => [
                'class' => 'input-sm',
            ],
        ]);

        $this->add([
            'name' => 'structure',
            'type' => Structure::class,
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



    public function populateAll(): void
    {
        /** @var MiseEnPaiementRecherche $recherche */
        $recherche = $this->getObject();

        $params = [
            'annee' => $recherche->getAnnee()->getId(),
        ];

        $filters = [
            'tp.annee_id = :annee'
        ];

        // Filtre des paiements
        if ($recherche->getEtat() == MiseEnPaiement::A_METTRE_EN_PAIEMENT) {
            $filters[] = 'AND tp.mise_en_paiement_id IS NOT NULL';
            $filters[] = 'AND tp.periode_paiement_id IS NULL';
        }
        if ($recherche->getEtat() == MiseEnPaiement::MIS_EN_PAIEMENT) {
            $filters[] = 'AND tp.mise_en_paiement_id IS NOT NULL';
            $filters[] = 'AND tp.periode_paiement_id IS NOT NULL';
        }

        $this->populateTypeIntervenants($params, $filters);
        $this->checkAndAutoSelect('typeIntervenant');
        if (!$this->get('typeIntervenant')->getValue()) {
            return;
        } else {
            $params['typeIntervenant'] = $this->get('typeIntervenant')->getValue();
            if (99999 != $params['typeIntervenant']) {
                $filters[] = 'AND tp.type_intervenant_id = :typeIntervenant';
            }
        }

        if ($this->hasStructureFilter()) {
            $this->populateStructures($params, $filters);
            $this->checkAndAutoSelect('structure');
            if (!$this->get('structure')->getValue()) {
                return;
            } else {
                $params['structure'] = $this->get('structure')->getValue();
                $filters[] = 'AND tp.structure_id = :structure';
            }
        }

        if ($this->hasPeriodeFilter()) {
            $this->populatePeriodes($params, $filters);
            $this->checkAndAutoSelect('periode');

            if (!$this->get('periode')->getValue()) {
                return;
            } else {
                $params['periode'] = $this->get('periode')->getValue();
                $filters[] = 'AND tp.periode_paiement_id = :periode';
            }
        }

        $this->populateIntervenants($params, $filters);
    }



    protected function checkAndAutoSelect(string $elementName): void
    {
        /** @var Select $element */
        $element = $this->get($elementName);

        $values = $element->getValueOptions();
        $value = $element->getValue();

        if (count($values) == 1 && !$value) {
            $value = key($values);
            $element->setValue($value);
        } elseif (!array_key_exists($value, $values)) {
            $element->setValue(null);
        }
    }



    protected function populateTypeIntervenants(array $params, array $filters)
    {
        $sql = "
          WITH p AS (
              SELECT DISTINCT
                ti.id, 
                ti.libelle 
              FROM 
                tbl_paiement tp
                JOIN type_intervenant ti ON ti.id = tp.type_intervenant_id
              WHERE
                " . implode("\n", $filters) . "
              ORDER BY
                ti.id
          )
          SELECT 99999 id, '(Tous)' libelle FROM dual
          UNION ALL SELECT * FROM p
        ";
        $this->setValueOptionsSql('typeIntervenant', $sql, $params);
    }



    public function hasTypesIntervenants(): bool
    {
        return count($this->get('typeIntervenant')->getValueOptions()) > 0;
    }



    protected function populateStructures(array $params, array $filters)
    {
        // Filtre des rôles
        if ($structure = $this->getServiceContext()->getStructure()) {
            $filters[] = 'AND s.ids LIKE \'' . $structure->idsFilter() . "'";
        }

        $sql = "
          SELECT DISTINCT
            s.id, 
            s.libelle_court 
          FROM 
            tbl_paiement tp
            JOIN structure s ON s.id = tp.structure_id 
          WHERE
            " . implode("\n", $filters) . "
          ORDER BY
            s.libelle_court
        ";
        $this->setValueOptionsSql('structure', $sql, $params);
    }



    public function hasStructures(): bool
    {
        return $this->get('typeIntervenant')->getValue() && count($this->get('structure')->getValueOptions()) > 0;
    }



    public function hasStructureFilter(): bool
    {
        return $this->structureFilter;
    }



    public function setStructureFilter(bool $structureFilter): MiseEnPaiementRechercheForm
    {
        $this->structureFilter = $structureFilter;

        return $this;
    }



    protected function populatePeriodes(array $params, array $filters)
    {
        $sql = "
          SELECT DISTINCT
            p.id, p.ordre 
          FROM 
            tbl_paiement tp
            JOIN periode p ON p.id = tp.periode_paiement_id 
          WHERE
            " . implode("\n", $filters) . "
          ORDER BY
            p.ordre DESC
        ";
        $periodes = [];
        $periodesBdd = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        foreach($periodesBdd as $i => $p){
            $periode = $this->getEntityManager()->find(Periode::class, $p['ID']);
            $periodes[$periode->getId()] = $periode->getLibelleAnnuel($this->getObject()->getAnnee());
        }

        $this->setValueOptions('periode', $periodes);
    }



    public function hasPeriodes(): bool
    {
        return count($this->get('periode')->getValueOptions()) > 0;
    }



    public function hasPeriodeFilter(): bool
    {
        return $this->periodeFilter;
    }



    public function setPeriodeFilter(bool $periodeFilter): MiseEnPaiementRechercheForm
    {
        $this->periodeFilter = $periodeFilter;
        return $this;
    }



    protected function populateIntervenants(array $params, array $filters)
    {
        $sql = "
          SELECT DISTINCT
            i.id, 
            i.nom_usuel || ' ' || i.prenom label 
          FROM 
            tbl_paiement tp
            JOIN intervenant i ON i.id = tp.intervenant_id 
          WHERE
            " . implode("\n", $filters) . "
          ORDER BY
            label
        ";
        $this->setValueOptionsSql('intervenants', $sql, $params);
    }



    public function hasIntervenants(): bool
    {
        return count($this->get('intervenants')->getValueOptions()) > 0;
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
            'typeIntervenant' => [
                'required' => false,
            ],
            'structure'       => [
                'required' => false,
            ],
            'periode'         => [
                'required' => false,
            ],
            'intervenants'    => [
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
        $id = isset($data['typeIntervenant']) ? (int)$data['typeIntervenant'] : null;
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
            'typeIntervenant' => $object->getTypeIntervenant() ? $object->getTypeIntervenant()->getId() : null,
            'structure'       => $object->getStructure() ? $object->getStructure()->getId() : null,
            'periode'         => $object->getPeriode() ? $object->getPeriode()->getId() : null,
            'intervenants'    => [],
        ];
        foreach ($object->getIntervenants() as $intervenant) {
            $data['intervenants'][] = $intervenant->getId();
        }

        return $data;
    }

}