<?php

namespace Chargens\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;

/**
 * Description of DifferentielForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DifferentielForm extends AbstractForm
{
    use ContextServiceAwareTrait;

    public function init()
    {
        $this->setAttributes([
            'action'  => $this->getCurrentUrl(),
            'class'   => 'differentiel',
            'enctype' => 'multipart/form-data',
        ]);

        $this->add([
            'type'       => 'File',
            'name'       => 'avant-fichier',
            'options'    => [

            ],
            'attributes' => [
                'id'       => 'fichier',
                'multiple' => false,
                'accept'   => 'application/csv',
            ],
        ]);

        $this->add([
            'type'       => 'File',
            'name'       => 'apres-fichier',
            'options'    => [

            ],
            'attributes' => [
                'id'       => 'fichier',
                'multiple' => false,
                'accept'   => 'application/csv',
            ],
        ]);

        $this->add([
            'name'       => 'avant',
            'options'    => [
                'label' => "Premier export des charges",
            ],
            'attributes' => [
                'class'            => 'input-sm selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'apres',
            'options'    => [
                'label' => "Export des charges le plus récent",
            ],
            'attributes' => [
                'class'            => 'input-sm selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Comparer',
                'class' => 'btn btn-primary btn-save',
            ],
        ]);

        $scenarios = $this->getScenarioValues();

        $this->get('avant')->setValueOptions($scenarios);
        $this->get('apres')->setValueOptions($scenarios);
    }



    protected function getScenarioValues(): array
    {
        $scenarios = ['export' => ['label' => 'A partir d\'un fichier d\'export', 'options' => ['export' => 'Veuillez téléverser un fichier ci-dessous ou bien choisir un scénario']]];

        $structure = $this->getServiceContext()->getStructure();
        if ($structure) {
            $where  = 'WHERE s.structure_id IS NULL OR str.ids LIKE :structure';
            $params = ['structure' => $structure->idsFilter()];
        } else {
            $where  = '';
            $params = [];
        }
        $sql = "
        SELECT 
          DISTINCT c.annee_id, a.LIBELLE annee, s.id scenario_id, s.libelle scenario, s.type, s.structure_id 
        FROM 
          tbl_chargens c
          JOIN scenario s ON s.id = c.scenario_id
          JOIN annee a ON a.id = c.annee_id
          LEFT JOIN structure str ON str.id = s.structure_id 
        $where
        ORDER BY 
          annee_id, type DESC, scenario
        ";

        $ss = $this->getServiceContext()->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        foreach ($ss as $s) {
            if (!isset($scenarios[(int)$s['ANNEE_ID']])) {
                $scenarios[(int)$s['ANNEE_ID']] = [
                    'label' => $s['ANNEE'], 'options' => [],
                ];
            }
            $scenarios[(int)$s['ANNEE_ID']]['options'][$s['ANNEE_ID'] . '-' . $s['SCENARIO_ID']] = $s['SCENARIO'];
        }

        return $scenarios;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = [
        ];

        return $filters;
    }
}
