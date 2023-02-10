<?php

namespace OffreFormation\Form;

use Application\Entity\Db\VolumeHoraireEns;
use Application\Filter\StringFromFloat;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;


/**
 * Description of VolumeHoraireEnsForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireEnsForm extends Form implements InputFilterProviderInterface
{

    public function init()
    {

        /* Ajoutez vos éléments de formulaire ici */

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
     * @param VolumeHoraireEns[] $vhes
     */
    public function build(array $vhes)
    {
        foreach ($vhes as $vhe) {
            $this->add([
                'name'       => $this->getElementName($vhe, 'heures'),
                'type'       => 'Text',
                'attributes' => [
                    'value' => StringFromFloat::run($vhe->getHeures(), false),
                ],
            ]);

            $this->add([
                'name'       => $this->getElementName($vhe, 'groupes'),
                'type'       => 'Text',
                'attributes' => [
                    'value' => StringFromFloat::run($vhe->getGroupes(), false),
                ],
            ]);
        }
    }



    public function getElement(VolumeHoraireEns $volumeHoraireEns, $type)
    {
        return $this->get($this->getElementName($volumeHoraireEns, $type));
    }



    private function getElementName(VolumeHoraireEns $volumeHoraireEns, $type)
    {
        return 'vhes[' . $volumeHoraireEns->getTypeIntervention()->getId() . '][' . $type . ']';
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
            /* Filtres et validateurs */
        ];
    }

}