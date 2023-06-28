<?php

namespace Paiement\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Paiement\Entity\Db\TauxRemuValeur;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class TauxValeurForm extends AbstractForm
{
    use SchemaServiceAwareTrait;

    public function init ()
    {
        $ignore = ['tauxRemu'];
        $this->spec(TauxRemuValeur::class, $ignore);
        $this->addSecurity();
        $this->build();
        $this->setLabels([
            'dateEffet' => 'Date d\'effet',
        ]);
        $this->addSubmit();

        return $this;
    }



    public function bind ($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object TauxRemuValeur */
        parent::bind($object, $flags);

        return $this;
    }
}
