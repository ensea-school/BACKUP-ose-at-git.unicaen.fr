<?php

namespace OffreFormation\Form;

use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;

/**
 * Description of ElementPedagogiqueSynchronisationForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueSynchronisationForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use StructureAwareTrait;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->setAttribute('class', 'element-pedagogique-synchronisation');
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'type'       => 'Select',
            'name'       => 'code',
            'options'    => [
                'label' => 'Code',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Importer dans OSE',
                'class' => 'btn btn-primary importer',
            ],
        ]);
    }



    public function populate()
    {
        $elements = [];
        $sql      = "SELECT code, libelle FROM V_DIFF_ELEMENT_PEDAGOGIQUE WHERE IMPORT_ACTION IN ('insert','undelete') AND ANNEE_ID = :annee AND structure_id = :structure ORDER BY CODE";
        $params   = [
            'annee'     => $this->getServiceContext()->getAnnee()->getId(),
            'structure' => $this->getStructure()->getId(),
        ];
        $data     = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        foreach ($data as $d) {
            $elements[$d['CODE']] = $d['CODE'] . ' : ' . $d['LIBELLE'];
        }
        $this->get('code')->setValueOptions($elements);
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
            'code' => [
                'required' => true,
            ],
        ];
    }
}
