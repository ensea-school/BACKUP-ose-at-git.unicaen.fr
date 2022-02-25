<?php

namespace Application\Form\MotifNonPaiement;

use Application\Entity\Db\MotifNonPaiement;
use Application\Form\AbstractForm;

/**
 * Description of MotifNonPaiementSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class MotifNonPaiementSaisieForm extends AbstractForm
{

    public function init()
    {
        $this->spec(MotifNonPaiement::class);
        $this->build();

        $this->get('libelleCourt')->setLabel('Libellé court');
        $this->get('libelleLong')->setLabel('Libellé long');

        $this->addSubmit();

        return $this;
    }
}