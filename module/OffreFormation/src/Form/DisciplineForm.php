<?php

namespace OffreFormation\Form;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Form\AbstractForm;
use Laminas\Hydrator\HydratorInterface;

/**
 * Description of DisciplineForm
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class DisciplineForm extends AbstractForm
{
    use ParametresServiceAwareTrait;


    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());
        $hydrator = new DisciplineFormHydrator;
        $this->setHydrator($hydrator);

        $this->add([
            'type'    => 'Text',
            'name'    => 'source-code',
            'options' => [
                'label' => 'Code',
            ],
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle-long',
            'options' => [
                'label' => 'Libellé long',
            ],
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle-court',
            'options' => [
                'label' => 'Libellé court',
            ],
        ]);

        for ($i = 1; $i <= 4; $i++) {
            $lcc = $this->getServiceParametres()->get('discipline_codes_corresp_' . $i . '_libelle');
            if ($lcc) {
                $this->add([
                    'type'    => 'Text',
                    'name'    => 'codes-corresp-' . $i,
                    'options' => [
                        'label' => $lcc,
                    ],
                ]);
            }
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
            'source-code'     => [
                'required' => true,
            ],
            'libelle-long'    => [
                'required' => true,
            ],
            'libelle-court'   => [
                'required' => true,
            ],
            'codes-corresp-1' => [
                'required' => false,
            ],
            'codes-corresp-2' => [
                'required' => false,
            ],
            'codes-corresp-3' => [
                'required' => false,
            ],
            'codes-corresp-4' => [
                'required' => false,
            ],
        ];
    }
}





class DisciplineFormHydrator implements HydratorInterface
{

    /**
     * @param array                                    $data
     * @param \OffreFormation\Entity\Db\Discipline $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setSourceCode($data['source-code']);
        $object->setLibelleLong($data['libelle-long']);
        $object->setLibelleCourt($data['libelle-court']);
        if (isset($data['codes-corresp-1'])) $object->setCodesCorresp1($data['codes-corresp-1']);
        if (isset($data['codes-corresp-2'])) $object->setCodesCorresp2($data['codes-corresp-2']);
        if (isset($data['codes-corresp-3'])) $object->setCodesCorresp3($data['codes-corresp-3']);
        if (isset($data['codes-corresp-4'])) $object->setCodesCorresp4($data['codes-corresp-4']);

        return $object;
    }



    /**
     * @param \OffreFormation\Entity\Db\Discipline $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'              => $object->getId(),
            'source-code'     => $object->getSourceCode(),
            'libelle-long'    => $object->getLibelleLong(),
            'libelle-court'   => $object->getLibelleCourt(),
            'codes-corresp-1' => $object->getCodesCorresp1(),
            'codes-corresp-2' => $object->getCodesCorresp2(),
            'codes-corresp-3' => $object->getCodesCorresp3(),
            'codes-corresp-4' => $object->getCodesCorresp4(),
        ];

        return $data;
    }
}