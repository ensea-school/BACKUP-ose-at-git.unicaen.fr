<?php

namespace Paiement\Form;

use Application\Form\AbstractForm;
use Paiement\Entity\Db\JourFerie;


/**
 * Description of JourFerieForm
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class JourFerieForm extends AbstractForm
{

    public function init()
    {
        $this->spec(JourFerie::class);
        $this->addSubmit('Enregistrer');
        $this->build();
    }
}