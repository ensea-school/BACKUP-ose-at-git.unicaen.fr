<?php

namespace Paiement\Form\MotifNonPaiement;

use Application\Form\AbstractForm;
use Paiement\Entity\Db\MotifNonPaiement;

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

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }
}