<?php

namespace Application\Form\MotifNonPaiement;

use Application\Entity\Db\MotifNonPaiement;
use Application\Form\AbstractForm;
use Laminas\Form\Element\Csrf;

/**
 * Description of MotifNonPaiementSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class MotifNonPaiementSaisieForm extends AbstractForm
{

    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->spec(MotifNonPaiement::class);
        $this->spec([
            'libelleCourt' => ['element' => ['options' => ['label' => 'Libellé court']]],
            'libelleLong'  => ['element' => ['options' => ['label' => 'Libellé long']]],
        ]);
        $this->specBuild();

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }
}