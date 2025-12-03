<?php

namespace Plafond\Form;

use Application\Form\AbstractForm;
use Plafond\Entity\Db\Plafond;
use Plafond\Service\PlafondServiceAwareTrait;
use Laminas\InputFilter\InputFilterProviderInterface;


/**
 * Description of PlafondForm
 *
 * @author UnicaenCode
 */
class PlafondForm extends AbstractForm implements InputFilterProviderInterface
{
    use PlafondServiceAwareTrait;


    public function init()
    {
        $this->setAttribute('class', 'plafond-form');

        $this->spec(Plafond::class, ['ok', 'messageErreur']);
        $this->build();

        $this->setLabels([
            'numero'  => 'Numéro (3 chiffres max.)',
            'libelle' => 'Libellé',
        ]);

        $this->remove('plafondPerimetre');
        $this->add([
            'name'       => 'plafondPerimetre',
            'options'    => [
                'label'         => 'Périmètre',
                'value_options' => \UnicaenApp\Util::collectionAsOptions($this->getServicePlafond()->getPerimetres()),
                'empty_option'  => 'Sélectionner un périmètre...',
            ],
            'type'       => 'Select',
        ]);

        $this->remove('requete');
        $this->add([
            'type'       => 'Textarea',
            'name'       => 'requete',
            'options'    => [
                'label' => "Requête SQL",
            ],
            'attributes' => [
                'id'   => 'requete',
                'rows' => '20',
            ],
        ]);

        $this->addSubmit();
    }
}