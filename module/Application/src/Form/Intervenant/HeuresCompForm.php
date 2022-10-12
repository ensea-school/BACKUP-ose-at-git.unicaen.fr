<?php

namespace Application\Form\Intervenant;

use Application\Form\AbstractForm;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Laminas\Form\Element\Select;


/**
 * Description of HeuresCompForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class HeuresCompForm extends AbstractForm
{
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;

    /**
     *
     */
    public function init()
    {
        $this->setAttribute('method', 'get');

        $typeVolumeHoraire = new Select('type-volume-horaire');
        $typeVolumeHoraire->setLabel('Type de service :');
        $typeVolumeHoraire->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeVolumeHoraire()->getList()));
        $typeVolumeHoraire->setValue($this->getServiceTypeVolumeHoraire()->getPrevu()->getId());
        $this->add($typeVolumeHoraire);

        $etatVolumeHoraire = new Select('etat-volume-horaire');
        $etatVolumeHoraire->setLabel('État :');
        $etatVolumeHoraire->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceEtatVolumeHoraire()->getList()));
        $etatVolumeHoraire->setValue($this->getServiceEtatVolumeHoraire()->getSaisi()->getId());
        $this->add($etatVolumeHoraire);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Appliquer',
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
        return [];
    }

}